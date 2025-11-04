<?php

namespace Plugins\PageBuilder\Addons\Common\Divider;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class Divider extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/divider.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Divider Style'),
            'options' => [
                'solid' => __('Solid'),
                'dashed' => __('Dashed'),
                'dotted' => __('Dotted'),
                'double' => __('Double'),
                'wavy' => __('Wavy'),
            ],
            'value' => $widget_saved_values['style'] ?? 'solid',
        ]);

        $output .= Select::get([
            'name' => 'width',
            'label' => __('Width'),
            'options' => [
                '100' => __('100%'),
                '75' => __('75%'),
                '50' => __('50%'),
                '25' => __('25%'),
                'custom' => __('Custom'),
            ],
            'value' => $widget_saved_values['width'] ?? '100',
        ]);

        $output .= Slider::get([
            'name' => 'custom_width',
            'label' => __('Custom Width (px)'),
            'value' => $widget_saved_values['custom_width'] ?? 100,
            'max' => 2000,
            'min' => 10,
        ]);

        $output .= ColorPicker::get([
            'name' => 'color',
            'label' => __('Color'),
            'value' => $widget_saved_values['color'] ?? '#e0e0e0',
        ]);

        $output .= Slider::get([
            'name' => 'height',
            'label' => __('Height/Thickness (px)'),
            'value' => $widget_saved_values['height'] ?? 1,
            'max' => 20,
            'min' => 1,
        ]);

        $output .= Select::get([
            'name' => 'alignment',
            'label' => __('Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['alignment'] ?? 'center',
        ]);

        $output .= Slider::get([
            'name' => 'margin_top',
            'label' => __('Margin Top (px)'),
            'value' => $widget_saved_values['margin_top'] ?? 20,
            'max' => 200,
        ]);

        $output .= Slider::get([
            'name' => 'margin_bottom',
            'label' => __('Margin Bottom (px)'),
            'value' => $widget_saved_values['margin_bottom'] ?? 20,
            'max' => 200,
        ]);

        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        try {
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'solid';
            $width = SanitizeInput::esc_html($this->setting_item('width')) ?? '100';
            $custom_width = SanitizeInput::esc_html($this->setting_item('custom_width')) ?? 100;
            $color = SanitizeInput::esc_html($this->setting_item('color')) ?? '#e0e0e0';
            $height = SanitizeInput::esc_html($this->setting_item('height')) ?? 1;
            $alignment = SanitizeInput::esc_html($this->setting_item('alignment')) ?? 'center';
            $margin_top = SanitizeInput::esc_html($this->setting_item('margin_top')) ?? 20;
            $margin_bottom = SanitizeInput::esc_html($this->setting_item('margin_bottom')) ?? 20;
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Calculate width
            $divider_width = ($width === 'custom') ? $custom_width . 'px' : $width . '%';

            $data = [
                'style' => $style,
                'width' => $divider_width,
                'color' => $color,
                'height' => $height,
                'alignment' => $alignment,
                'margin_top' => $margin_top,
                'margin_bottom' => $margin_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.divider', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Divider Frontend Render Error', [
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
        return __('Divider / Separator');
    }
}

