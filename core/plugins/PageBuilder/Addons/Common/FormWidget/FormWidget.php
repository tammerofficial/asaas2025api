<?php

namespace Plugins\PageBuilder\Addons\Common\FormWidget;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;

class FormWidget extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/form-widget.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Form Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Form Description'),
            'value' => $widget_saved_values['description'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'success_message',
            'label' => __('Success Message'),
            'value' => $widget_saved_values['success_message'] ?? __('Thank you for contacting us!'),
        ]);

        $output .= Select::get([
            'name' => 'form_style',
            'label' => __('Form Style'),
            'options' => [
                'default' => __('Default'),
                'inline' => __('Inline'),
                'boxed' => __('Boxed'),
            ],
            'value' => $widget_saved_values['form_style'] ?? 'default',
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
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $description = SanitizeInput::esc_html($this->setting_item('description')) ?? '';
        $success_message = SanitizeInput::esc_html($this->setting_item('success_message')) ?? '';
        $form_style = SanitizeInput::esc_html($this->setting_item('form_style')) ?? 'default';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'title' => $title,
            'description' => $description,
            'success_message' => $success_message,
            'form_style' => $form_style,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.form-widget', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Form Widget');
    }
}

