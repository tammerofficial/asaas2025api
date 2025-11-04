<?php

namespace Plugins\PageBuilder\Addons\Common\Spacer;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Spacer extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/spacer.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Slider::get([
            'name' => 'height',
            'label' => __('Height (px)'),
            'value' => $widget_saved_values['height'] ?? 50,
            'max' => 500,
            'min' => 10,
        ]);

        $output .= Slider::get([
            'name' => 'height_tablet',
            'label' => __('Height - Tablet (px)'),
            'value' => $widget_saved_values['height_tablet'] ?? null,
            'max' => 500,
            'min' => 10,
        ]);

        $output .= Slider::get([
            'name' => 'height_mobile',
            'label' => __('Height - Mobile (px)'),
            'value' => $widget_saved_values['height_mobile'] ?? null,
            'max' => 500,
            'min' => 10,
        ]);

        $output .= Select::get([
            'name' => 'visibility',
            'label' => __('Visibility'),
            'options' => [
                'all' => __('All Devices'),
                'desktop' => __('Desktop Only'),
                'mobile' => __('Mobile Only'),
            ],
            'value' => $widget_saved_values['visibility'] ?? 'all',
        ]);

        $output .= ColorPicker::get([
            'name' => 'background_color',
            'label' => __('Background Color (Optional)'),
            'value' => $widget_saved_values['background_color'] ?? '',
        ]);

        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        try {
            $height = SanitizeInput::esc_html($this->setting_item('height')) ?? 50;
            $height_tablet = SanitizeInput::esc_html($this->setting_item('height_tablet'));
            $height_mobile = SanitizeInput::esc_html($this->setting_item('height_mobile'));
            $visibility = SanitizeInput::esc_html($this->setting_item('visibility')) ?? 'all';
            $background_color = SanitizeInput::esc_html($this->setting_item('background_color')) ?? '';
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            $data = [
                'height' => $height,
                'height_tablet' => $height_tablet,
                'height_mobile' => $height_mobile,
                'visibility' => $visibility,
                'background_color' => $background_color,
                'section_id' => $section_id,
            ];

            return self::renderView('common.spacer', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Spacer Frontend Render Error', [
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
        return __('Spacer');
    }
}

