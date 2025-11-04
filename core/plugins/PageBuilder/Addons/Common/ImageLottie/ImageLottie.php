<?php

namespace Plugins\PageBuilder\Addons\Common\ImageLottie;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class ImageLottie extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/image-lottie.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'lottie_json_url',
            'label' => __('Lottie JSON URL (Optional)'),
            'value' => $widget_saved_values['lottie_json_url'] ?? null,
            'info' => __('If provided, Lottie animation will be used instead of image'),
        ]);

        $output .= Select::get([
            'name' => 'animation_type',
            'label' => __('Animation Type'),
            'options' => [
                'none' => __('None'),
                'fade' => __('Fade'),
                'slide' => __('Slide'),
                'zoom' => __('Zoom'),
                'bounce' => __('Bounce'),
            ],
            'value' => $widget_saved_values['animation_type'] ?? 'none',
        ]);

        $output .= Slider::get([
            'name' => 'animation_speed',
            'label' => __('Animation Speed (ms)'),
            'value' => $widget_saved_values['animation_speed'] ?? 1000,
            'min' => 100,
            'max' => 5000,
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
        $image = $this->setting_item('image') ?? '';
        $lottie_json_url = SanitizeInput::esc_url($this->setting_item('lottie_json_url')) ?? '';
        $animation_type = SanitizeInput::esc_html($this->setting_item('animation_type')) ?? 'none';
        $animation_speed = (int)($this->setting_item('animation_speed') ?? 1000);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'image' => $image,
            'lottie_json_url' => $lottie_json_url,
            'animation_type' => $animation_type,
            'animation_speed' => $animation_speed,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.image-lottie', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Image / Lottie Animation');
    }
}

