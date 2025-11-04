<?php

namespace Plugins\PageBuilder\Addons\Common\PricingTable;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class PricingTable extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/pricing-table.jpg';
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
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'subtitle',
            'label' => __('Subtitle'),
            'value' => $widget_saved_values['subtitle'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
                'price' => __('Price'),
            ],
            'value' => $widget_saved_values['order_by'] ?? 'id',
        ]);

        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Ascending'),
                'desc' => __('Descending'),
            ],
            'value' => $widget_saved_values['order'] ?? 'asc',
        ]);

        // add padding option
        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle')) ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by')) ?? 'id';
        $order = SanitizeInput::esc_html($this->setting_item('order')) ?? 'asc';

        // Get tenant's price plans
        $all_price_plan = \App\Models\PricePlan::with('plan_features')
            ->where('status', 1)
            ->orderBy($order_by, $order)
            ->get()
            ->groupBy('type');
        
        $plan_types = \App\Models\PricePlan::where('status', 1)
            ->orderBy('type', 'asc')
            ->select('type')
            ->distinct()
            ->pluck('type');

        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'all_price_plan' => $all_price_plan,
            'plan_types' => $plan_types,
            'section_id' => $section_id,
        ];

        return self::renderView('common.pricing-table', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Pricing Table');
    }
}

