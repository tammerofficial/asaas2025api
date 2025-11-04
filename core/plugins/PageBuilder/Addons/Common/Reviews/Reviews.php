<?php

namespace Plugins\PageBuilder\Addons\Common\Reviews;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Reviews extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/reviews.jpg';
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
            'id' => 'reviews_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_name',
                    'label' => __('Name')
                ],
                [
                    'type' => RepeaterField::NUMBER,
                    'name' => 'repeater_rating',
                    'label' => __('Rating (1-5)')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_review_text',
                    'label' => __('Review Text')
                ],
                [
                    'type' => RepeaterField::DATE,
                    'name' => 'repeater_date',
                    'label' => __('Date')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'columns',
            'label' => __('Columns'),
            'options' => [
                '1' => __('1 Column'),
                '2' => __('2 Columns'),
                '3' => __('3 Columns'),
            ],
            'value' => $widget_saved_values['columns'] ?? '3',
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
        $repeater_data = $this->setting_item('reviews_repeater');
        $columns = SanitizeInput::esc_html($this->setting_item('columns')) ?? '3';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'repeater_data' => $repeater_data,
            'columns' => $columns,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.reviews', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Reviews + Stars');
    }
}

