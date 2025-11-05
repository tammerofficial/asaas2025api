<?php

namespace Plugins\PageBuilder\Addons\Common\Header;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderLogo extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/header-logo.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'logo_id',
            'label' => __('Logo Image'),
            'value' => $widget_saved_values['logo_id'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'alt_text',
            'label' => __('Alt Text'),
            'value' => $widget_saved_values['alt_text'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'link_url',
            'label' => __('Link URL'),
            'value' => $widget_saved_values['link_url'] ?? url('/'),
        ]);

        $output .= Select::get([
            'name' => 'alignment',
            'label' => __('Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['alignment'] ?? 'left',
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
        $logo_id = $this->setting_item('logo_id') ?? null;
        $alt_text = SanitizeInput::esc_html($this->setting_item('alt_text') ?? '');
        $link_url = SanitizeInput::esc_html($this->setting_item('link_url') ?? url('/'));
        $alignment = SanitizeInput::esc_html($this->setting_item('alignment') ?? 'left');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'logo_id' => $logo_id,
            'alt_text' => $alt_text,
            'link_url' => $link_url,
            'alignment' => $alignment,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.header-logo', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header Logo');
    }
}


