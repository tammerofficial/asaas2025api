<?php

namespace Plugins\PageBuilder\Addons\Common\FlipBox;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class FlipBox extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/flipbox.jpg';
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
            'id' => 'flipbox_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_front_image',
                    'label' => __('Front Image (Optional)')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_front_icon',
                    'label' => __('Front Icon (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_front_title',
                    'label' => __('Front Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_front_description',
                    'label' => __('Front Description')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_back_title',
                    'label' => __('Back Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_back_description',
                    'label' => __('Back Description')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_back_button_text',
                    'label' => __('Back Button Text (Optional)')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_back_button_url',
                    'label' => __('Back Button URL (Optional)')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'flip_direction',
            'label' => __('Flip Direction'),
            'options' => [
                'horizontal' => __('Horizontal'),
                'vertical' => __('Vertical'),
            ],
            'value' => $widget_saved_values['flip_direction'] ?? 'horizontal',
        ]);

        $output .= Select::get([
            'name' => 'flip_trigger',
            'label' => __('Flip Trigger'),
            'options' => [
                'hover' => __('Hover'),
                'click' => __('Click'),
            ],
            'value' => $widget_saved_values['flip_trigger'] ?? 'hover',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Flip Box Style'),
            'options' => [
                'default' => __('Default'),
                '3d' => __('3D Effect'),
                'minimal' => __('Minimal'),
            ],
            'value' => $widget_saved_values['style'] ?? 'default',
        ]);

        $output .= Select::get([
            'name' => 'columns',
            'label' => __('Columns'),
            'options' => [
                '1' => __('1 Column'),
                '2' => __('2 Columns'),
                '3' => __('3 Columns'),
                '4' => __('4 Columns'),
            ],
            'value' => $widget_saved_values['columns'] ?? '3',
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
            $repeater_data = $this->setting_item('flipbox_repeater');
            $flip_direction = SanitizeInput::esc_html($this->setting_item('flip_direction')) ?? 'horizontal';
            $flip_trigger = SanitizeInput::esc_html($this->setting_item('flip_trigger')) ?? 'hover';
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $columns = SanitizeInput::esc_html($this->setting_item('columns')) ?? '3';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Sanitize repeater data
            if (!empty($repeater_data['repeater_front_title_'])) {
                foreach ($repeater_data['repeater_front_title_'] as $key => $title) {
                    $repeater_data['repeater_front_title_'][$key] = SanitizeInput::esc_html($title);
                    $repeater_data['repeater_back_title_'][$key] = SanitizeInput::esc_html($repeater_data['repeater_back_title_'][$key] ?? '');
                }
            }

            $data = [
                'repeater_data' => $repeater_data,
                'flip_direction' => $flip_direction,
                'flip_trigger' => $flip_trigger,
                'style' => $style,
                'columns' => $columns,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.flipbox', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder FlipBox Frontend Render Error', [
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
        return __('FlipBox / Flip Card');
    }
}

