<?php

namespace Plugins\PageBuilder\Addons\Common\Heading;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class Heading extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/heading.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'text',
            'label' => __('Heading Text'),
            'value' => $widget_saved_values['text'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'size',
            'label' => __('Heading Size'),
            'options' => [
                'h1' => __('H1'),
                'h2' => __('H2'),
                'h3' => __('H3'),
                'h4' => __('H4'),
                'h5' => __('H5'),
                'h6' => __('H6'),
            ],
            'value' => $widget_saved_values['size'] ?? 'h2',
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

        $output .= ColorPicker::get([
            'name' => 'color',
            'label' => __('Text Color'),
            'value' => $widget_saved_values['color'] ?? '#000000',
        ]);

        $output .= Slider::get([
            'name' => 'margin_top',
            'label' => __('Margin Top'),
            'value' => $widget_saved_values['margin_top'] ?? 0,
            'max' => 200,
        ]);

        $output .= Slider::get([
            'name' => 'margin_bottom',
            'label' => __('Margin Bottom'),
            'value' => $widget_saved_values['margin_bottom'] ?? 20,
            'max' => 200,
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
        $size = SanitizeInput::esc_html($this->setting_item('size')) ?? 'h2';
        $align = SanitizeInput::esc_html($this->setting_item('align')) ?? 'left';
        $color = SanitizeInput::esc_html($this->setting_item('color')) ?? '#000000';
        $margin_top = SanitizeInput::esc_html($this->setting_item('margin_top')) ?? 0;
        $margin_bottom = SanitizeInput::esc_html($this->setting_item('margin_bottom')) ?? 20;
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'text' => $text,
            'size' => $size,
            'align' => $align,
            'color' => $color,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.heading', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Heading');
    }
}

