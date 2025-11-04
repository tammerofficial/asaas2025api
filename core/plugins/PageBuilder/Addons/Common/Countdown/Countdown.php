<?php

namespace Plugins\PageBuilder\Addons\Common\Countdown;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\DateTime;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class Countdown extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/countdown.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'target_date',
            'label' => __('Target Date/Time (YYYY-MM-DD HH:MM:SS)'),
            'value' => $widget_saved_values['target_date'] ?? date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);

        $output .= Text::get([
            'name' => 'label',
            'label' => __('Label (e.g., "Sale Ends In", "Event Starts In")'),
            'value' => $widget_saved_values['label'] ?? __('Time Remaining'),
        ]);

        $output .= Select::get([
            'name' => 'format',
            'label' => __('Display Format'),
            'options' => [
                'full' => __('Days, Hours, Minutes, Seconds'),
                'no-days' => __('Hours, Minutes, Seconds'),
                'no-seconds' => __('Days, Hours, Minutes'),
                'compact' => __('Compact (DD:HH:MM:SS)'),
            ],
            'value' => $widget_saved_values['format'] ?? 'full',
        ]);

        $output .= Select::get([
            'name' => 'style',
            'label' => __('Countdown Style'),
            'options' => [
                'default' => __('Default'),
                'compact' => __('Compact'),
                'minimal' => __('Minimal'),
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
            $target_date = SanitizeInput::esc_html($this->setting_item('target_date')) ?? date('Y-m-d H:i:s', strtotime('+30 days'));
            $label = SanitizeInput::esc_html($this->setting_item('label')) ?? __('Time Remaining');
            $format = SanitizeInput::esc_html($this->setting_item('format')) ?? 'full';
            $style = SanitizeInput::esc_html($this->setting_item('style')) ?? 'default';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Convert target date to timestamp
            $target_timestamp = strtotime($target_date);
            if ($target_timestamp === false) {
                $target_timestamp = strtotime('+30 days');
            }

            $data = [
                'target_timestamp' => $target_timestamp,
                'target_date' => $target_date,
                'label' => $label,
                'format' => $format,
                'style' => $style,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.countdown', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Countdown Frontend Render Error', [
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
        return __('Countdown Timer');
    }
}

