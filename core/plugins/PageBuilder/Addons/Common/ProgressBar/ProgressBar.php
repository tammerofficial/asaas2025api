<?php

namespace Plugins\PageBuilder\Addons\Common\ProgressBar;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class ProgressBar extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/progress-bar.jpg';
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
            'id' => 'progress_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_label',
                    'label' => __('Label')
                ],
                [
                    'type' => RepeaterField::SLIDER,
                    'name' => 'repeater_percentage',
                    'label' => __('Percentage (0-100)'),
                    'min' => 0,
                    'max' => 100,
                ],
                [
                    'type' => RepeaterField::COLOR_PICKER,
                    'name' => 'repeater_color',
                    'label' => __('Color')
                ],
                [
                    'type' => RepeaterField::SELECT,
                    'name' => 'repeater_animated',
                    'label' => __('Animated'),
                    'options' => [
                        'yes' => __('Yes'),
                        'no' => __('No'),
                    ]
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Progress Bar Style'),
            'options' => [
                'horizontal' => __('Horizontal'),
                'vertical' => __('Vertical'),
                'circular' => __('Circular'),
            ],
            'value' => $widget_saved_values['style'] ?? 'horizontal',
        ]);

        $output .= Select::get([
            'name' => 'show_percentage',
            'label' => __('Show Percentage Text'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_percentage'] ?? 'yes',
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
            $repeater_data = $this->setting_item('progress_repeater');
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'horizontal';
            $show_percentage = SanitizeInput::esc_html($this->setting_item('show_percentage')) ?? 'yes';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Sanitize repeater data
            if (!empty($repeater_data['repeater_label_'])) {
                foreach ($repeater_data['repeater_label_'] as $key => $label) {
                    $repeater_data['repeater_label_'][$key] = SanitizeInput::esc_html($label);
                }
            }

            $data = [
                'repeater_data' => $repeater_data,
                'style' => $style,
                'show_percentage' => $show_percentage,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.progress-bar', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder ProgressBar Frontend Render Error', [
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
        return __('Progress Bar');
    }
}

