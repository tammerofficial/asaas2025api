<?php

namespace Plugins\PageBuilder\Addons\Common\Header;

use App\Helpers\SanitizeInput;
use App\Models\Menu;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderMenu extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/header-menu.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        // Menu select
        $menus = Menu::all();
        $options = [];
        foreach ($menus as $menu) {
            $options[(string)$menu->id] = $menu->title;
        }

        $output .= Select::get([
            'name' => 'menu_id',
            'label' => __('Navigation Menu'),
            'options' => $options,
            'value' => $widget_saved_values['menu_id'] ?? ''
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

        $output .= Text::get([
            'name' => 'extra_class',
            'label' => __('Extra CSS Class'),
            'value' => $widget_saved_values['extra_class'] ?? '',
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
        $menu_id = SanitizeInput::esc_html($this->setting_item('menu_id') ?? '');
        $alignment = SanitizeInput::esc_html($this->setting_item('alignment') ?? 'center');
        $extra_class = SanitizeInput::esc_html($this->setting_item('extra_class') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'menu_id' => $menu_id,
            'alignment' => $alignment,
            'extra_class' => $extra_class,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.header-menu', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header Menu');
    }
}


