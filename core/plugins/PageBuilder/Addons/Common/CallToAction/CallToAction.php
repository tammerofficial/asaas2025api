<?php

namespace Plugins\PageBuilder\Addons\Common\CallToAction;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class CallToAction extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/call-to-action.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'subtitle',
            'label' => __('Subtitle'),
            'value' => $widget_saved_values['subtitle'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'primary_button_text',
            'label' => __('Primary Button Text'),
            'value' => $widget_saved_values['primary_button_text'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'primary_button_url',
            'label' => __('Primary Button URL'),
            'value' => $widget_saved_values['primary_button_url'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'secondary_button_text',
            'label' => __('Secondary Button Text'),
            'value' => $widget_saved_values['secondary_button_text'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'secondary_button_url',
            'label' => __('Secondary Button URL'),
            'value' => $widget_saved_values['secondary_button_url'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'background_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['background_image'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Style'),
            'options' => [
                'default' => __('Default'),
                'centered' => __('Centered'),
                'split' => __('Split Layout'),
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
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle')) ?? '';
        $primary_button_text = SanitizeInput::esc_html($this->setting_item('primary_button_text')) ?? '';
        $primary_button_url = SanitizeInput::esc_url($this->setting_item('primary_button_url')) ?? '#';
        $secondary_button_text = SanitizeInput::esc_html($this->setting_item('secondary_button_text')) ?? '';
        $secondary_button_url = SanitizeInput::esc_url($this->setting_item('secondary_button_url')) ?? '#';
        $background_image = $this->setting_item('background_image') ?? '';
        $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'primary_button_text' => $primary_button_text,
            'primary_button_url' => $primary_button_url,
            'secondary_button_text' => $secondary_button_text,
            'secondary_button_url' => $secondary_button_url,
            'background_image' => $background_image,
            'style' => $style,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.call-to-action', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Call To Action');
    }
}

