<?php

namespace Plugins\PageBuilder\Addons\Common\Footer;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class FooterSocialMedia extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/Footer/footer-social-media.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Widget Title'),
            'value' => $widget_saved_values['title'] ?? '',
        ]);

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'footer_social_media_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('URL')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title/Tooltip')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'icon_style',
            'label' => __('Icon Style'),
            'options' => [
                'rounded' => __('Rounded'),
                'square' => __('Square'),
                'circle' => __('Circle'),
            ],
            'value' => $widget_saved_values['icon_style'] ?? 'circle',
        ]);

        $output .= Select::get([
            'name' => 'icon_size',
            'label' => __('Icon Size'),
            'options' => [
                'small' => __('Small'),
                'medium' => __('Medium'),
                'large' => __('Large'),
            ],
            'value' => $widget_saved_values['icon_size'] ?? 'medium',
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
        $widget_saved_values = $this->get_settings();
        $title = SanitizeInput::esc_html($widget_saved_values['title'] ?? '');
        $repeater_data = $widget_saved_values['footer_social_media_repeater'] ?? [];
        $icon_style = SanitizeInput::esc_html($widget_saved_values['icon_style'] ?? 'circle');
        $icon_size = SanitizeInput::esc_html($widget_saved_values['icon_size'] ?? 'medium');
        $alignment = SanitizeInput::esc_html($widget_saved_values['alignment'] ?? 'left');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $social_links = [];
        if (!empty($repeater_data) && is_array($repeater_data)) {
            $icons = $repeater_data['repeater_icon_'] ?? [];
            $urls = $repeater_data['repeater_url_'] ?? [];
            $titles = $repeater_data['repeater_title_'] ?? [];

            foreach ($icons as $key => $icon) {
                if (!empty($icon) && !empty($urls[$key])) {
                    $social_links[] = [
                        'icon' => SanitizeInput::esc_html($icon),
                        'url' => SanitizeInput::esc_url($urls[$key]),
                        'title' => SanitizeInput::esc_html($titles[$key] ?? ''),
                    ];
                }
            }
        }

        $data = [
            'title' => $title,
            'social_links' => $social_links,
            'icon_style' => $icon_style,
            'icon_size' => $icon_size,
            'alignment' => $alignment,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.footer.footer-social-media', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Footer Social Media');
    }
}

