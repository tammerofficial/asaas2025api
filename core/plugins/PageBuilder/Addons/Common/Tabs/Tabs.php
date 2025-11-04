<?php

namespace Plugins\PageBuilder\Addons\Common\Tabs;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Tabs extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/tabs.jpg';
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
            'id' => 'tabs_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_tab_title',
                    'label' => __('Tab Title')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'repeater_tab_content',
                    'label' => __('Tab Content')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'default_tab',
            'label' => __('Default Tab'),
            'options' => [
                '0' => __('First Tab'),
                '1' => __('Second Tab'),
                '2' => __('Third Tab'),
            ],
            'value' => $widget_saved_values['default_tab'] ?? '0',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Tab Style'),
            'options' => [
                'default' => __('Default'),
                'pills' => __('Pills'),
                'underline' => __('Underline'),
            ],
            'value' => $widget_saved_values['style'] ?? 'default',
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
        $repeater_data = $this->setting_item('tabs_repeater');
        $default_tab = (int)($this->setting_item('default_tab') ?? 0);
        $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'repeater_data' => $repeater_data,
            'default_tab' => $default_tab,
            'style' => $style,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.tabs', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Tabs / Toggle');
    }
}

