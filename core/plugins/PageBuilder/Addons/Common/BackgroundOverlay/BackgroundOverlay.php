<?php

namespace Plugins\PageBuilder\Addons\Common\BackgroundOverlay;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class BackgroundOverlay extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/background-overlay.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= ColorPicker::get([
            'name' => 'overlay_color',
            'label' => __('Overlay Color'),
            'value' => $widget_saved_values['overlay_color'] ?? '#000000',
        ]);

        $output .= Slider::get([
            'name' => 'opacity',
            'label' => __('Opacity (0-100)'),
            'value' => $widget_saved_values['opacity'] ?? 50,
            'max' => 100,
        ]);

        $output .= Select::get([
            'name' => 'gradient_type',
            'label' => __('Gradient Type'),
            'options' => [
                'none' => __('None'),
                'linear' => __('Linear'),
                'radial' => __('Radial'),
            ],
            'value' => $widget_saved_values['gradient_type'] ?? 'none',
        ]);

        $output .= ColorPicker::get([
            'name' => 'gradient_color',
            'label' => __('Gradient Color (Optional)'),
            'value' => $widget_saved_values['gradient_color'] ?? '#000000',
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
        $overlay_color = SanitizeInput::esc_html($this->setting_item('overlay_color')) ?? '#000000';
        $opacity = (int)($this->setting_item('opacity') ?? 50);
        $gradient_type = SanitizeInput::esc_html($this->setting_item('gradient_type')) ?? 'none';
        $gradient_color = SanitizeInput::esc_html($this->setting_item('gradient_color')) ?? '#000000';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'overlay_color' => $overlay_color,
            'opacity' => $opacity,
            'gradient_type' => $gradient_type,
            'gradient_color' => $gradient_color,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.background-overlay', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Background Overlay');
    }
}

