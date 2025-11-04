<?php

namespace Plugins\PageBuilder\Addons\Common\SocialIcons;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class SocialIcons extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/social-icons.jpg';
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
            'id' => 'social_icons_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::SELECT,
                    'name' => 'repeater_platform',
                    'label' => __('Platform'),
                    'options' => [
                        'facebook' => __('Facebook'),
                        'instagram' => __('Instagram'),
                        'twitter' => __('Twitter'),
                        'linkedin' => __('LinkedIn'),
                        'youtube' => __('YouTube'),
                        'pinterest' => __('Pinterest'),
                        'whatsapp' => __('WhatsApp'),
                        'tiktok' => __('TikTok'),
                        'snapchat' => __('Snapchat'),
                        'custom' => __('Custom'),
                    ]
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon (Optional - uses platform default if empty)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('URL')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Icon Style'),
            'options' => [
                'rounded' => __('Rounded'),
                'square' => __('Square'),
                'circle' => __('Circle'),
            ],
            'value' => $widget_saved_values['style'] ?? 'circle',
        ]);

        $output .= Select::get([
            'name' => 'size',
            'label' => __('Icon Size'),
            'options' => [
                'small' => __('Small'),
                'medium' => __('Medium'),
                'large' => __('Large'),
            ],
            'value' => $widget_saved_values['size'] ?? 'medium',
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

        $output .= Slider::get([
            'name' => 'spacing',
            'label' => __('Spacing (px)'),
            'value' => $widget_saved_values['spacing'] ?? 15,
            'max' => 50,
            'min' => 0,
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
            $repeater_data = $this->setting_item('social_icons_repeater');
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'circle';
            $size = SanitizeInput::esc_html($this->setting_item('size')) ?? 'medium';
            $alignment = SanitizeInput::esc_html($this->setting_item('alignment')) ?? 'center';
            $spacing = SanitizeInput::esc_html($this->setting_item('spacing')) ?? 15;
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Get platform default icons
            $platform_icons = [
                'facebook' => 'lab la-facebook',
                'instagram' => 'lab la-instagram',
                'twitter' => 'lab la-twitter',
                'linkedin' => 'lab la-linkedin',
                'youtube' => 'lab la-youtube',
                'pinterest' => 'lab la-pinterest',
                'whatsapp' => 'lab la-whatsapp',
                'tiktok' => 'lab la-tiktok',
                'snapchat' => 'lab la-snapchat',
            ];

            // Sanitize repeater data
            if (!empty($repeater_data['repeater_platform_'])) {
                foreach ($repeater_data['repeater_platform_'] as $key => $platform) {
                    // Set default icon if not provided
                    if (empty($repeater_data['repeater_icon_'][$key]) && isset($platform_icons[$platform])) {
                        $repeater_data['repeater_icon_'][$key] = $platform_icons[$platform];
                    }
                    // Sanitize URL
                    if (isset($repeater_data['repeater_url_'][$key])) {
                        $repeater_data['repeater_url_'][$key] = SanitizeInput::esc_url($repeater_data['repeater_url_'][$key]);
                    }
                }
            }

            $data = [
                'repeater_data' => $repeater_data,
                'style' => $style,
                'size' => $size,
                'alignment' => $alignment,
                'spacing' => $spacing,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.social-icons', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder SocialIcons Frontend Render Error', [
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
        return __('Social Icons');
    }
}

