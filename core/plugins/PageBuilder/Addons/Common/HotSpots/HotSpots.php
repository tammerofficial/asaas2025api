<?php

namespace Plugins\PageBuilder\Addons\Common\HotSpots;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class HotSpots extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/hotspots.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'background_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['background_image'] ?? '',
        ]);

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'hotspots_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::SLIDER,
                    'name' => 'repeater_x_position',
                    'label' => __('X Position (%)'),
                    'min' => 0,
                    'max' => 100,
                ],
                [
                    'type' => RepeaterField::SLIDER,
                    'name' => 'repeater_y_position',
                    'label' => __('Y Position (%)'),
                    'min' => 0,
                    'max' => 100,
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_tooltip_title',
                    'label' => __('Tooltip Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_tooltip_content',
                    'label' => __('Tooltip Content')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_link',
                    'label' => __('Link (Optional)')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'tooltip_style',
            'label' => __('Tooltip Style'),
            'options' => [
                'default' => __('Default'),
                'popup' => __('Popup'),
                'always-visible' => __('Always Visible'),
            ],
            'value' => $widget_saved_values['tooltip_style'] ?? 'default',
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
            $background_image = $this->setting_item('background_image') ?? '';
            $repeater_data = $this->setting_item('hotspots_repeater');
            $tooltip_style = SanitizeInput::esc_html($this->setting_item('tooltip_style')) ?? 'default';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            if (empty($background_image)) {
                return '';
            }

            // Sanitize repeater data
            if (!empty($repeater_data['repeater_tooltip_title_'])) {
                foreach ($repeater_data['repeater_tooltip_title_'] as $key => $title) {
                    $repeater_data['repeater_tooltip_title_'][$key] = SanitizeInput::esc_html($title);
                    if (isset($repeater_data['repeater_tooltip_content_'][$key])) {
                        $repeater_data['repeater_tooltip_content_'][$key] = SanitizeInput::esc_html($repeater_data['repeater_tooltip_content_'][$key]);
                    }
                    if (isset($repeater_data['repeater_link_'][$key])) {
                        $repeater_data['repeater_link_'][$key] = SanitizeInput::esc_url($repeater_data['repeater_link_'][$key]);
                    }
                }
            }

            $data = [
                'background_image' => $background_image,
                'repeater_data' => $repeater_data,
                'tooltip_style' => $tooltip_style,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.hotspots', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder HotSpots Frontend Render Error', [
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
        return __('HotSpots / Interactive Hotspots');
    }
}

