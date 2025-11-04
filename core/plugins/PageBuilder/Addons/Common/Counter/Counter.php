<?php

namespace Plugins\PageBuilder\Addons\Common\Counter;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Counter extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/counter.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Slider::get([
            'name' => 'number',
            'label' => __('Target Number'),
            'value' => $widget_saved_values['number'] ?? 100,
            'max' => 1000000,
            'min' => 0,
        ]);

        $output .= Text::get([
            'name' => 'prefix',
            'label' => __('Prefix (e.g., $, +)'),
            'value' => $widget_saved_values['prefix'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'suffix',
            'label' => __('Suffix (e.g., +, K, M)'),
            'value' => $widget_saved_values['suffix'] ?? '',
        ]);

        $output .= IconPicker::get([
            'name' => 'icon',
            'label' => __('Icon (Optional)'),
            'value' => $widget_saved_values['icon'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'description',
            'label' => __('Description (Optional)'),
            'value' => $widget_saved_values['description'] ?? '',
        ]);

        $output .= Slider::get([
            'name' => 'animation_speed',
            'label' => __('Animation Speed (ms)'),
            'value' => $widget_saved_values['animation_speed'] ?? 2000,
            'max' => 10000,
            'min' => 500,
        ]);

        $output .= Slider::get([
            'name' => 'starting_number',
            'label' => __('Starting Number'),
            'value' => $widget_saved_values['starting_number'] ?? 0,
            'max' => 10000,
            'min' => 0,
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Counter Style'),
            'options' => [
                'default' => __('Default'),
                'boxed' => __('Boxed'),
                'minimal' => __('Minimal'),
            ],
            'value' => $widget_saved_values['style'] ?? 'default',
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
            $number = (int)($this->setting_item('number') ?? 100);
            $prefix = SanitizeInput::esc_html($this->setting_item('prefix')) ?? '';
            $suffix = SanitizeInput::esc_html($this->setting_item('suffix')) ?? '';
            $icon = SanitizeInput::esc_html($this->setting_item('icon')) ?? '';
            $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
            $description = SanitizeInput::esc_html($this->setting_item('description')) ?? '';
            $animation_speed = (int)($this->setting_item('animation_speed') ?? 2000);
            $starting_number = (int)($this->setting_item('starting_number') ?? 0);
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            $data = [
                'number' => $number,
                'prefix' => $prefix,
                'suffix' => $suffix,
                'icon' => $icon,
                'title' => $title,
                'description' => $description,
                'animation_speed' => $animation_speed,
                'starting_number' => $starting_number,
                'style' => $style,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.counter', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Counter Frontend Render Error', [
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
        return __('Counter / Number Counter');
    }
}

