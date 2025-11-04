<?php

namespace Plugins\PageBuilder\Addons\Common\Accordion;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Accordion extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/accordion.jpg';
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
            'id' => 'accordion_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'repeater_content',
                    'label' => __('Content')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon (Optional)')
                ],
                [
                    'type' => RepeaterField::SELECT,
                    'name' => 'repeater_default_open',
                    'label' => __('Default Open'),
                    'options' => [
                        'yes' => __('Yes'),
                        'no' => __('No'),
                    ]
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Accordion Style'),
            'options' => [
                'default' => __('Default'),
                'boxed' => __('Boxed'),
                'minimal' => __('Minimal'),
            ],
            'value' => $widget_saved_values['style'] ?? 'default',
        ]);

        $output .= Select::get([
            'name' => 'allow_multiple',
            'label' => __('Allow Multiple Open'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['allow_multiple'] ?? 'no',
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
            $repeater_data = $this->setting_item('accordion_repeater');
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $allow_multiple = SanitizeInput::esc_html($this->setting_item('allow_multiple')) ?? 'no';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            $data = [
                'repeater_data' => $repeater_data,
                'style' => $style,
                'allow_multiple' => $allow_multiple,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.accordion', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Accordion Frontend Render Error', [
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
        return __('Accordion');
    }
}

