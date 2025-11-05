<?php

namespace Plugins\PageBuilder\Addons\Common\Header;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderSearch extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/header-search.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'placeholder',
            'label' => __('Placeholder Text'),
            'value' => $widget_saved_values['placeholder'] ?? __('Search...'),
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Style'),
            'options' => [
                'inline' => __('Inline'),
                'icon' => __('Icon Toggle'),
            ],
            'value' => $widget_saved_values['style'] ?? 'icon',
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
        $placeholder = SanitizeInput::esc_html($this->setting_item('placeholder') ?? __('Search...'));
        $style = SanitizeInput::esc_html($this->setting_item('style') ?? 'icon');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'placeholder' => $placeholder,
            'style' => $style,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.header-search', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header Search');
    }
}


