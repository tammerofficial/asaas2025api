<?php

namespace Plugins\PageBuilder\Addons\Common\Footer;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\PageBuilderBase;

class FooterDescription extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/Footer/footer-description.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Description'),
            'value' => $widget_saved_values['description'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'alignment',
            'label' => __('Text Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['alignment'] ?? 'left',
        ]);

        $output .= ColorPicker::get([
            'name' => 'text_color',
            'label' => __('Text Color'),
            'value' => $widget_saved_values['text_color'] ?? null,
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
        $description = SanitizeInput::kses_basic($this->setting_item('description') ?? '');
        $alignment = SanitizeInput::esc_html($this->setting_item('alignment') ?? 'left');
        $text_color = SanitizeInput::esc_html($this->setting_item('text_color') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'description' => $description,
            'alignment' => $alignment,
            'text_color' => $text_color,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.footer.footer-description', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Footer Description');
    }
}

