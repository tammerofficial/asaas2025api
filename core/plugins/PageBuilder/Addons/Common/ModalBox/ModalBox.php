<?php

namespace Plugins\PageBuilder\Addons\Common\ModalBox;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class ModalBox extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/modal-box.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Select::get([
            'name' => 'trigger_type',
            'label' => __('Trigger Type'),
            'options' => [
                'button' => __('Button'),
                'image' => __('Image'),
                'custom' => __('Custom HTML'),
            ],
            'value' => $widget_saved_values['trigger_type'] ?? 'button',
        ]);

        $output .= Text::get([
            'name' => 'trigger_text',
            'label' => __('Trigger Text'),
            'value' => $widget_saved_values['trigger_text'] ?? __('Open Modal'),
        ]);

        $output .= Image::get([
            'name' => 'trigger_image',
            'label' => __('Trigger Image'),
            'value' => $widget_saved_values['trigger_image'] ?? '',
        ]);

        $output .= Textarea::get([
            'name' => 'trigger_custom_html',
            'label' => __('Custom HTML Trigger'),
            'value' => $widget_saved_values['trigger_custom_html'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'modal_title',
            'label' => __('Modal Title'),
            'value' => $widget_saved_values['modal_title'] ?? '',
        ]);

        $output .= Textarea::get([
            'name' => 'modal_content',
            'label' => __('Modal Content (HTML/WYSIWYG)'),
            'value' => $widget_saved_values['modal_content'] ?? '',
        ]);

        $output .= Select::get([
            'name' => 'modal_size',
            'label' => __('Modal Size'),
            'options' => [
                'small' => __('Small'),
                'medium' => __('Medium'),
                'large' => __('Large'),
                'fullscreen' => __('Fullscreen'),
            ],
            'value' => $widget_saved_values['modal_size'] ?? 'medium',
        ]);

        $output .= Select::get([
            'name' => 'show_close_button',
            'label' => __('Show Close Button'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_close_button'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'close_on_outside_click',
            'label' => __('Close on Outside Click'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['close_on_outside_click'] ?? 'yes',
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
            $trigger_type = SanitizeInput::esc_html($this->setting_item('trigger_type')) ?? 'button';
            $trigger_text = SanitizeInput::esc_html($this->setting_item('trigger_text')) ?? __('Open Modal');
            $trigger_image = $this->setting_item('trigger_image') ?? '';
            $trigger_custom_html = $this->setting_item('trigger_custom_html') ?? '';
            $modal_title = SanitizeInput::esc_html($this->setting_item('modal_title')) ?? '';
            $modal_content = $this->setting_item('modal_content') ?? '';
            $modal_size = SanitizeInput::esc_html($this->setting_item('modal_size')) ?? 'medium';
            $show_close_button = SanitizeInput::esc_html($this->setting_item('show_close_button')) ?? 'yes';
            $close_on_outside_click = SanitizeInput::esc_html($this->setting_item('close_on_outside_click')) ?? 'yes';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            $unique_id = 'modal-' . ($section_id ?: uniqid());

            $data = [
                'unique_id' => $unique_id,
                'trigger_type' => $trigger_type,
                'trigger_text' => $trigger_text,
                'trigger_image' => $trigger_image,
                'trigger_custom_html' => $trigger_custom_html,
                'modal_title' => $modal_title,
                'modal_content' => $modal_content,
                'modal_size' => $modal_size,
                'show_close_button' => $show_close_button,
                'close_on_outside_click' => $close_on_outside_click,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.modal-box', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder ModalBox Frontend Render Error', [
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
        return __('Modal Box / Popup');
    }
}

