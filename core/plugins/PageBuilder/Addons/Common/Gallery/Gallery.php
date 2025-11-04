<?php

namespace Plugins\PageBuilder\Addons\Common\Gallery;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Gallery extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/gallery.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'gallery_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_description',
                    'label' => __('Description (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_link',
                    'label' => __('Link (Optional)')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'layout',
            'label' => __('Gallery Layout'),
            'options' => [
                'grid' => __('Grid'),
                'masonry' => __('Masonry'),
                'carousel' => __('Carousel'),
            ],
            'value' => $widget_saved_values['layout'] ?? 'grid',
        ]);

        $output .= Select::get([
            'name' => 'columns',
            'label' => __('Columns'),
            'options' => [
                '1' => __('1 Column'),
                '2' => __('2 Columns'),
                '3' => __('3 Columns'),
                '4' => __('4 Columns'),
                '5' => __('5 Columns'),
                '6' => __('6 Columns'),
            ],
            'value' => $widget_saved_values['columns'] ?? '3',
        ]);

        $output .= Select::get([
            'name' => 'lightbox',
            'label' => __('Enable Lightbox'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['lightbox'] ?? 'yes',
        ]);

        $output .= Slider::get([
            'name' => 'spacing',
            'label' => __('Spacing (px)'),
            'value' => $widget_saved_values['spacing'] ?? 15,
            'max' => 50,
            'min' => 0,
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
            $repeater_data = $this->setting_item('gallery_repeater');
            $layout = SanitizeInput::esc_html($this->setting_item('layout')) ?? 'grid';
            $columns = SanitizeInput::esc_html($this->setting_item('columns')) ?? '3';
            $lightbox = SanitizeInput::esc_html($this->setting_item('lightbox')) ?? 'yes';
            $spacing = SanitizeInput::esc_html($this->setting_item('spacing')) ?? 15;
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Sanitize repeater data
            if (!empty($repeater_data['repeater_title_'])) {
                foreach ($repeater_data['repeater_title_'] as $key => $title) {
                    $repeater_data['repeater_title_'][$key] = SanitizeInput::esc_html($title);
                    if (isset($repeater_data['repeater_description_'][$key])) {
                        $repeater_data['repeater_description_'][$key] = SanitizeInput::esc_html($repeater_data['repeater_description_'][$key]);
                    }
                    if (isset($repeater_data['repeater_link_'][$key])) {
                        $repeater_data['repeater_link_'][$key] = SanitizeInput::esc_url($repeater_data['repeater_link_'][$key]);
                    }
                }
            }

            $data = [
                'repeater_data' => $repeater_data,
                'layout' => $layout,
                'columns' => $columns,
                'lightbox' => $lightbox,
                'spacing' => $spacing,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.gallery', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Gallery Frontend Render Error', [
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
        return __('Gallery');
    }
}

