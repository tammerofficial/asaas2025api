<?php

namespace Plugins\PageBuilder\Addons\Common\Breadcrumb;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Breadcrumb extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/breadcrumb.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Select::get([
            'name' => 'separator',
            'label' => __('Separator'),
            'options' => [
                '/' => __('/ (Slash)'),
                '>' => __('> (Greater Than)'),
                '|' => __('| (Pipe)'),
                '→' => __('→ (Arrow)'),
                'custom' => __('Custom'),
            ],
            'value' => $widget_saved_values['separator'] ?? '/',
        ]);

        $output .= Text::get([
            'name' => 'custom_separator',
            'label' => __('Custom Separator'),
            'value' => $widget_saved_values['custom_separator'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'home_text',
            'label' => __('Home Text'),
            'value' => $widget_saved_values['home_text'] ?? __('Home'),
        ]);

        $output .= Select::get([
            'name' => 'show_home',
            'label' => __('Show Home'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_home'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Breadcrumb Style'),
            'options' => [
                'default' => __('Default'),
                'minimal' => __('Minimal'),
                'arrows' => __('Arrows'),
            ],
            'value' => $widget_saved_values['style'] ?? 'default',
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
        try {
            $separator_type = SanitizeInput::esc_html($this->setting_item('separator')) ?? '/';
            $custom_separator = SanitizeInput::esc_html($this->setting_item('custom_separator')) ?? '';
            $home_text = SanitizeInput::esc_html($this->setting_item('home_text')) ?? __('Home');
            $show_home = SanitizeInput::esc_html($this->setting_item('show_home')) ?? 'yes';
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $alignment = SanitizeInput::esc_html($this->setting_item('alignment')) ?? 'left';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Get separator
            $separator = ($separator_type === 'custom') ? $custom_separator : $separator_type;

            // Get current page breadcrumbs
            $breadcrumbs = $this->generateBreadcrumbs($home_text, $show_home === 'yes');

            $data = [
                'breadcrumbs' => $breadcrumbs,
                'separator' => $separator,
                'style' => $style,
                'alignment' => $alignment,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.breadcrumb', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Breadcrumb Frontend Render Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }

    private function generateBreadcrumbs($homeText, $showHome)
    {
        $breadcrumbs = [];
        
        try {
            if ($showHome) {
                $breadcrumbs[] = [
                    'title' => SanitizeInput::esc_html($homeText),
                    'url' => url('/'),
                    'active' => false,
                ];
            }

            // Get current page info
            $currentPage = null;
            if (function_exists('request')) {
                $currentPage = request()->route('page') ?? null;
            }
            
            if ($currentPage && isset($currentPage->title)) {
                $breadcrumbs[] = [
                    'title' => SanitizeInput::esc_html($currentPage->title),
                    'url' => url()->current(),
                    'active' => true,
                ];
            } else {
                // Fallback: use URL segments
                $segments = [];
                if (function_exists('request')) {
                    $segments = request()->segments() ?? [];
                }
                $url = '';
                
                foreach ($segments as $index => $segment) {
                    $url .= '/' . $segment;
                    $breadcrumbs[] = [
                        'title' => SanitizeInput::esc_html(ucfirst(str_replace(['-', '_'], ' ', $segment))),
                        'url' => url($url),
                        'active' => ($index === count($segments) - 1),
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Breadcrumb generation error', ['error' => $e->getMessage()]);
        }

        return $breadcrumbs;
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Breadcrumb');
    }
}

