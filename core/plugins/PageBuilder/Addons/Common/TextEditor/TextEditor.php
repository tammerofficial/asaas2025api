<?php

namespace Plugins\PageBuilder\Addons\Common\TextEditor;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Summernote;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class TextEditor extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/text-editor.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Summernote::get([
            'name' => 'content',
            'label' => __('Content'),
            'value' => $widget_saved_values['content'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'align',
            'label' => __('Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['align'] ?? 'left',
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
        $content = $this->setting_item('content') ?? '';
        $align = SanitizeInput::esc_html($this->setting_item('align')) ?? 'left';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'content' => $content,
            'align' => $align,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.text-editor', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Text Editor');
    }
}

