<?php

namespace Plugins\PageBuilder\Addons\Common\Typewriter;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Typewriter extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/typewriter.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'static_text_before',
            'label' => __('Static Text (Before)'),
            'value' => $widget_saved_values['static_text_before'] ?? '',
        ]);

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'typewriter_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_text',
                    'label' => __('Animated Text/Phrase')
                ],
            ]
        ]);

        $output .= Text::get([
            'name' => 'static_text_after',
            'label' => __('Static Text (After)'),
            'value' => $widget_saved_values['static_text_after'] ?? '',
        ]);

        $output .= Slider::get([
            'name' => 'typing_speed',
            'label' => __('Typing Speed (ms per character)'),
            'value' => $widget_saved_values['typing_speed'] ?? 100,
            'max' => 500,
            'min' => 20,
        ]);

        $output .= Slider::get([
            'name' => 'deleting_speed',
            'label' => __('Deleting Speed (ms per character)'),
            'value' => $widget_saved_values['deleting_speed'] ?? 50,
            'max' => 500,
            'min' => 20,
        ]);

        $output .= Select::get([
            'name' => 'cursor_style',
            'label' => __('Cursor Style'),
            'options' => [
                'pipe' => __('| (Pipe)'),
                'underscore' => __('_ (Underscore)'),
                'block' => __('█ (Block)'),
            ],
            'value' => $widget_saved_values['cursor_style'] ?? 'pipe',
        ]);

        $output .= Select::get([
            'name' => 'loop',
            'label' => __('Loop'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['loop'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'show_cursor',
            'label' => __('Show Cursor'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_cursor'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'text_align',
            'label' => __('Text Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['text_align'] ?? 'center',
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
            $static_text_before = SanitizeInput::esc_html($this->setting_item('static_text_before')) ?? '';
            $repeater_data = $this->setting_item('typewriter_repeater');
            $static_text_after = SanitizeInput::esc_html($this->setting_item('static_text_after')) ?? '';
            $typing_speed = (int)($this->setting_item('typing_speed') ?? 100);
            $deleting_speed = (int)($this->setting_item('deleting_speed') ?? 50);
            $cursor_style = SanitizeInput::esc_html($this->setting_item('cursor_style')) ?? 'pipe';
            $loop = SanitizeInput::esc_html($this->setting_item('loop')) ?? 'yes';
            $show_cursor = SanitizeInput::esc_html($this->setting_item('show_cursor')) ?? 'yes';
            $text_align = SanitizeInput::esc_html($this->setting_item('text_align')) ?? 'center';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Get animated texts array
            $animated_texts = [];
            if (!empty($repeater_data['repeater_text_'])) {
                foreach ($repeater_data['repeater_text_'] as $text) {
                    $animated_texts[] = SanitizeInput::esc_html($text);
                }
            }

            // Get cursor character
            $cursor_chars = [
                'pipe' => '|',
                'underscore' => '_',
                'block' => '█',
            ];
            $cursor_char = $cursor_chars[$cursor_style] ?? '|';

            $data = [
                'static_text_before' => $static_text_before,
                'animated_texts' => $animated_texts,
                'static_text_after' => $static_text_after,
                'typing_speed' => $typing_speed,
                'deleting_speed' => $deleting_speed,
                'cursor_char' => $cursor_char,
                'show_cursor' => $show_cursor === 'yes',
                'loop' => $loop === 'yes',
                'text_align' => $text_align,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.typewriter', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Typewriter Frontend Render Error', [
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
        return __('Typewriter / Animated Text');
    }
}

