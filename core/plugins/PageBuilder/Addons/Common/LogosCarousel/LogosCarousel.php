<?php

namespace Plugins\PageBuilder\Addons\Common\LogosCarousel;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class LogosCarousel extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/logos-carousel.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'logos_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_logo_image',
                    'label' => __('Logo Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_link',
                    'label' => __('Link (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_alt_text',
                    'label' => __('Alt Text')
                ],
            ]
        ]);

        $output .= Switcher::get([
            'name' => 'autoplay',
            'label' => __('Autoplay'),
            'value' => $widget_saved_values['autoplay'] ?? true,
        ]);

        $output .= Slider::get([
            'name' => 'speed',
            'label' => __('Carousel Speed (ms)'),
            'value' => $widget_saved_values['speed'] ?? 3000,
            'min' => 1000,
            'max' => 10000,
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
        $repeater_data = $this->setting_item('logos_repeater');
        $autoplay = (bool)($this->setting_item('autoplay') ?? true);
        $speed = (int)($this->setting_item('speed') ?? 3000);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'repeater_data' => $repeater_data,
            'autoplay' => $autoplay,
            'speed' => $speed,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.logos-carousel', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Logos Carousel');
    }
}

