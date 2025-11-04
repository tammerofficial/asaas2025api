<?php

namespace Plugins\PageBuilder\Addons\Common\Newsletter;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Newsletter extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/newsletter.jpg';
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
            'value' => $widget_saved_values['title'] ?? __('Subscribe to Our Newsletter'),
        ]);

        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Description'),
            'value' => $widget_saved_values['description'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'placeholder_text',
            'label' => __('Placeholder Text'),
            'value' => $widget_saved_values['placeholder_text'] ?? __('Enter your email address'),
        ]);

        $output .= Text::get([
            'name' => 'button_text',
            'label' => __('Button Text'),
            'value' => $widget_saved_values['button_text'] ?? __('Subscribe'),
        ]);

        $output .= Text::get([
            'name' => 'success_message',
            'label' => __('Success Message'),
            'value' => $widget_saved_values['success_message'] ?? __('Thank you for subscribing!'),
        ]);

        $output .= Text::get([
            'name' => 'error_message',
            'label' => __('Error Message'),
            'value' => $widget_saved_values['error_message'] ?? __('Something went wrong. Please try again.'),
        ]);

        $output .= Select::get([
            'name' => 'api_integration',
            'label' => __('API Integration'),
            'options' => [
                'none' => __('None (Email Only)'),
                'mailchimp' => __('Mailchimp'),
                'email' => __('Email'),
            ],
            'value' => $widget_saved_values['api_integration'] ?? 'email',
        ]);

        $output .= Text::get([
            'name' => 'api_key',
            'label' => __('API Key (Optional)'),
            'value' => $widget_saved_values['api_key'] ?? '',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Newsletter Style'),
            'options' => [
                'default' => __('Default'),
                'inline' => __('Inline'),
                'boxed' => __('Boxed'),
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
            $title = SanitizeInput::esc_html($this->setting_item('title')) ?? __('Subscribe to Our Newsletter');
            $description = SanitizeInput::esc_html($this->setting_item('description')) ?? '';
            $placeholder_text = SanitizeInput::esc_html($this->setting_item('placeholder_text')) ?? __('Enter your email address');
            $button_text = SanitizeInput::esc_html($this->setting_item('button_text')) ?? __('Subscribe');
            $success_message = SanitizeInput::esc_html($this->setting_item('success_message')) ?? __('Thank you for subscribing!');
            $error_message = SanitizeInput::esc_html($this->setting_item('error_message')) ?? __('Something went wrong. Please try again.');
            $api_integration = SanitizeInput::esc_html($this->setting_item('api_integration')) ?? 'email';
            $api_key = SanitizeInput::esc_html($this->setting_item('api_key')) ?? '';
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            $unique_id = 'newsletter-' . ($section_id ?: uniqid());

            $data = [
                'unique_id' => $unique_id,
                'title' => $title,
                'description' => $description,
                'placeholder_text' => $placeholder_text,
                'button_text' => $button_text,
                'success_message' => $success_message,
                'error_message' => $error_message,
                'api_integration' => $api_integration,
                'api_key' => $api_key,
                'style' => $style,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.newsletter', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Newsletter Frontend Render Error', [
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
        return __('Newsletter / Email Subscription');
    }
}

