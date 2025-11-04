<?php

namespace Plugins\PageBuilder\Addons\Common\StepsTimeline;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class StepsTimeline extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/steps-timeline.jpg';
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
            'id' => 'steps_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::NUMBER,
                    'name' => 'repeater_step_number',
                    'label' => __('Step Number')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_description',
                    'label' => __('Description')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::DATE,
                    'name' => 'repeater_date',
                    'label' => __('Date (Optional)')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'layout',
            'label' => __('Layout'),
            'options' => [
                'vertical' => __('Vertical'),
                'horizontal' => __('Horizontal'),
            ],
            'value' => $widget_saved_values['layout'] ?? 'vertical',
        ]);

        // add padding option
        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $repeater_data = $this->setting_item('steps_repeater');
        $layout = SanitizeInput::esc_html($this->setting_item('layout')) ?? 'vertical';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'repeater_data' => $repeater_data,
            'layout' => $layout,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.steps-timeline', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Steps / Timeline');
    }
}

