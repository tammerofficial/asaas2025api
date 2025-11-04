<?php

namespace Plugins\PageBuilder\Addons\Common\ImageComparison;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class ImageComparison extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/image-comparison.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'before_image',
            'label' => __('Before Image'),
            'value' => $widget_saved_values['before_image'] ?? '',
        ]);

        $output .= Image::get([
            'name' => 'after_image',
            'label' => __('After Image'),
            'value' => $widget_saved_values['after_image'] ?? '',
        ]);

        $output .= Slider::get([
            'name' => 'slider_position',
            'label' => __('Default Slider Position (%)'),
            'value' => $widget_saved_values['slider_position'] ?? 50,
            'max' => 100,
            'min' => 0,
        ]);

        $output .= Select::get([
            'name' => 'orientation',
            'label' => __('Orientation'),
            'options' => [
                'horizontal' => __('Horizontal'),
                'vertical' => __('Vertical'),
            ],
            'value' => $widget_saved_values['orientation'] ?? 'horizontal',
        ]);

        $output .= Text::get([
            'name' => 'label_before',
            'label' => __('Before Label (Optional)'),
            'value' => $widget_saved_values['label_before'] ?? __('Before'),
        ]);

        $output .= Text::get([
            'name' => 'label_after',
            'label' => __('After Label (Optional)'),
            'value' => $widget_saved_values['label_after'] ?? __('After'),
        ]);

        $output .= Select::get([
            'name' => 'show_labels',
            'label' => __('Show Labels'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_labels'] ?? 'yes',
        ]);

        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        try {
            $before_image = $this->setting_item('before_image') ?? '';
            $after_image = $this->setting_item('after_image') ?? '';
            $slider_position = (int)($this->setting_item('slider_position') ?? 50);
            $orientation = SanitizeInput::esc_html($this->setting_item('orientation')) ?? 'horizontal';
            $label_before = SanitizeInput::esc_html($this->setting_item('label_before')) ?? __('Before');
            $label_after = SanitizeInput::esc_html($this->setting_item('label_after')) ?? __('After');
            $show_labels = SanitizeInput::esc_html($this->setting_item('show_labels')) ?? 'yes';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            if (empty($before_image) || empty($after_image)) {
                return '';
            }

            $data = [
                'before_image' => $before_image,
                'after_image' => $after_image,
                'slider_position' => $slider_position,
                'orientation' => $orientation,
                'label_before' => $label_before,
                'label_after' => $label_after,
                'show_labels' => $show_labels,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.image-comparison', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder ImageComparison Frontend Render Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Image Comparison / Before After');
    }
}

