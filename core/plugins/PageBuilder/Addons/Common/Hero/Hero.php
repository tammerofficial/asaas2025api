<?php

namespace Plugins\PageBuilder\Addons\Common\Hero;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class Hero extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/hero.jpg';
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

        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Description'),
            'value' => $widget_saved_values['description'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'button_text',
            'label' => __('Button Text'),
            'value' => $widget_saved_values['button_text'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'button_url',
            'label' => __('Button URL'),
            'value' => $widget_saved_values['button_url'] ?? null,
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
            'dimensions' => '1920x1080 px'
        ]);

        $output .= ColorPicker::get([
            'name' => 'overlay_color',
            'label' => __('Overlay Color'),
            'value' => $widget_saved_values['overlay_color'] ?? '#000000',
        ]);

        $output .= Slider::get([
            'name' => 'overlay_opacity',
            'label' => __('Overlay Opacity'),
            'value' => $widget_saved_values['overlay_opacity'] ?? 50,
            'max' => 100,
        ]);

        $output .= Select::get([
            'name' => 'content_align',
            'label' => __('Content Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['content_align'] ?? 'center',
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
        $description = SanitizeInput::esc_html($this->setting_item('description')) ?? '';
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text')) ?? '';
        $button_url = SanitizeInput::esc_url($this->setting_item('button_url')) ?? '';
        $secondary_button_text = SanitizeInput::esc_html($this->setting_item('secondary_button_text')) ?? '';
        $secondary_button_url = SanitizeInput::esc_url($this->setting_item('secondary_button_url')) ?? '';
        $background_image = $this->setting_item('background_image') ?? '';
        $overlay_color = SanitizeInput::esc_html($this->setting_item('overlay_color')) ?? '#000000';
        $overlay_opacity = SanitizeInput::esc_html($this->setting_item('overlay_opacity')) ?? 50;
        $content_align = SanitizeInput::esc_html($this->setting_item('content_align')) ?? 'center';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => $description,
            'button_text' => $button_text,
            'button_url' => $button_url,
            'secondary_button_text' => $secondary_button_text,
            'secondary_button_url' => $secondary_button_url,
            'background_image' => $background_image,
            'overlay_color' => $overlay_color,
            'overlay_opacity' => $overlay_opacity,
            'content_align' => $content_align,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.hero', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Hero Section');
    }
}

