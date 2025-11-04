<?php

namespace Plugins\PageBuilder\Addons\Common\Table;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Table extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/table.jpg';
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
            'id' => 'table_rows_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::REPEATER,
                    'name' => 'repeater_cells',
                    'label' => __('Cells'),
                    'fields' => [
                        [
                            'type' => RepeaterField::TEXT,
                            'name' => 'cell_content',
                            'label' => __('Cell Content')
                        ],
                    ]
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'header_row',
            'label' => __('Header Row'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['header_row'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'striped_rows',
            'label' => __('Striped Rows'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['striped_rows'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'border_style',
            'label' => __('Border Style'),
            'options' => [
                'solid' => __('Solid'),
                'dashed' => __('Dashed'),
                'none' => __('None'),
            ],
            'value' => $widget_saved_values['border_style'] ?? 'solid',
        ]);

        $output .= Select::get([
            'name' => 'responsive',
            'label' => __('Responsive Behavior'),
            'options' => [
                'scroll' => __('Horizontal Scroll'),
                'stack' => __('Stack on Mobile'),
            ],
            'value' => $widget_saved_values['responsive'] ?? 'scroll',
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
            $repeater_data = $this->setting_item('table_rows_repeater');
            $header_row = SanitizeInput::esc_html($this->setting_item('header_row')) ?? 'yes';
            $striped_rows = SanitizeInput::esc_html($this->setting_item('striped_rows')) ?? 'yes';
            $border_style = SanitizeInput::esc_html($this->setting_item('border_style')) ?? 'solid';
            $responsive = SanitizeInput::esc_html($this->setting_item('responsive')) ?? 'scroll';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // Sanitize cell content
            if (!empty($repeater_data['repeater_cells_'])) {
                foreach ($repeater_data['repeater_cells_'] as $row_index => $row) {
                    if (is_array($row)) {
                        foreach ($row as $cell_index => $cell) {
                            if (isset($cell['cell_content_'])) {
                                $repeater_data['repeater_cells_'][$row_index][$cell_index]['cell_content_'] = 
                                    SanitizeInput::esc_html($cell['cell_content_']);
                            }
                        }
                    }
                }
            }

            $data = [
                'repeater_data' => $repeater_data,
                'header_row' => $header_row,
                'striped_rows' => $striped_rows,
                'border_style' => $border_style,
                'responsive' => $responsive,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.table', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Table Frontend Render Error', [
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
        return __('Table');
    }
}

