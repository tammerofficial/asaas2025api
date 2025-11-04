<?php

namespace Plugins\PageBuilder\Addons\Common\Button;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Button extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/button.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'text',
            'label' => __('Button Text'),
            'value' => $widget_saved_values['text'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'url',
            'label' => __('Button URL'),
            'value' => $widget_saved_values['url'] ?? null,
        ]);

        $output .= IconPicker::get([
            'name' => 'icon',
            'label' => __('Icon'),
            'value' => $widget_saved_values['icon'] ?? '',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Button Style'),
            'options' => [
                'primary' => __('Primary'),
                'secondary' => __('Secondary'),
                'outline-primary' => __('Outline Primary'),
                'outline-secondary' => __('Outline Secondary'),
            ],
            'value' => $widget_saved_values['style'] ?? 'primary',
        ]);

        $output .= Select::get([
            'name' => 'size',
            'label' => __('Button Size'),
            'options' => [
                'sm' => __('Small'),
                'md' => __('Medium'),
                'lg' => __('Large'),
            ],
            'value' => $widget_saved_values['size'] ?? 'md',
        ]);

        $output .= Select::get([
            'name' => 'target',
            'label' => __('Link Target'),
            'options' => [
                '_self' => __('Same Window'),
                '_blank' => __('New Window'),
            ],
            'value' => $widget_saved_values['target'] ?? '_self',
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
        $text = SanitizeInput::esc_html($this->setting_item('text')) ?? '';
        $url = SanitizeInput::esc_url($this->setting_item('url')) ?? '#';
        $icon = SanitizeInput::esc_html($this->setting_item('icon')) ?? '';
        $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'primary';
        $size = SanitizeInput::esc_html($this->setting_item('size')) ?? 'md';
        $target = SanitizeInput::esc_html($this->setting_item('target')) ?? '_self';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'text' => $text,
            'url' => $url,
            'icon' => $icon,
            'style' => $style,
            'size' => $size,
            'target' => $target,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.button', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Button');
    }
}

