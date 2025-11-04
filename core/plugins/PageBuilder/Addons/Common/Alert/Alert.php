<?php

namespace Plugins\PageBuilder\Addons\Common\Alert;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\PageBuilderBase;

class Alert extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/alert.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Select::get([
            'name' => 'type',
            'label' => __('Alert Type'),
            'options' => [
                'success' => __('Success'),
                'error' => __('Error'),
                'warning' => __('Warning'),
                'info' => __('Info'),
            ],
            'value' => $widget_saved_values['type'] ?? 'info',
        ]);

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title (Optional)'),
            'value' => $widget_saved_values['title'] ?? '',
        ]);

        $output .= Textarea::get([
            'name' => 'message',
            'label' => __('Message'),
            'value' => $widget_saved_values['message'] ?? '',
        ]);

        $output .= Select::get([
            'name' => 'show_icon',
            'label' => __('Show Icon'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
                'custom' => __('Custom Icon'),
            ],
            'value' => $widget_saved_values['show_icon'] ?? 'yes',
        ]);

        $output .= IconPicker::get([
            'name' => 'custom_icon',
            'label' => __('Custom Icon'),
            'value' => $widget_saved_values['custom_icon'] ?? '',
        ]);

        $output .= Select::get([
            'name' => 'dismissible',
            'label' => __('Dismissible'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['dismissible'] ?? 'no',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Alert Style'),
            'options' => [
                'default' => __('Default'),
                'filled' => __('Filled'),
                'outlined' => __('Outlined'),
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
            $type = SanitizeInput::esc_html($this->setting_item('type')) ?? 'info';
            $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
            $message = SanitizeInput::esc_html($this->setting_item('message')) ?? '';
            $show_icon = SanitizeInput::esc_html($this->setting_item('show_icon')) ?? 'yes';
            $custom_icon = SanitizeInput::esc_html($this->setting_item('custom_icon')) ?? '';
            $dismissible = SanitizeInput::esc_html($this->setting_item('dismissible')) ?? 'no';
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Get icon based on type
            $icon = '';
            if ($show_icon === 'custom' && !empty($custom_icon)) {
                $icon = $custom_icon;
            } elseif ($show_icon === 'yes') {
                $icons = [
                    'success' => 'las la-check-circle',
                    'error' => 'las la-exclamation-circle',
                    'warning' => 'las la-exclamation-triangle',
                    'info' => 'las la-info-circle',
                ];
                $icon = $icons[$type] ?? 'las la-info-circle';
            }

            $data = [
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'dismissible' => $dismissible,
                'style' => $style,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.alert', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Alert Frontend Render Error', [
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
        return __('Alert / Notification');
    }
}

