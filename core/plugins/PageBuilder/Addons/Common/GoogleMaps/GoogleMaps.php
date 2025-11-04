<?php

namespace Plugins\PageBuilder\Addons\Common\GoogleMaps;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class GoogleMaps extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/google-maps.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'address',
            'label' => __('Address'),
            'value' => $widget_saved_values['address'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'latitude',
            'label' => __('Latitude'),
            'value' => $widget_saved_values['latitude'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'longitude',
            'label' => __('Longitude'),
            'value' => $widget_saved_values['longitude'] ?? '',
        ]);

        $output .= Slider::get([
            'name' => 'zoom_level',
            'label' => __('Zoom Level'),
            'value' => $widget_saved_values['zoom_level'] ?? 15,
            'max' => 20,
            'min' => 1,
        ]);

        $output .= Select::get([
            'name' => 'map_type',
            'label' => __('Map Type'),
            'options' => [
                'roadmap' => __('Roadmap'),
                'satellite' => __('Satellite'),
                'hybrid' => __('Hybrid'),
                'terrain' => __('Terrain'),
            ],
            'value' => $widget_saved_values['map_type'] ?? 'roadmap',
        ]);

        $output .= Image::get([
            'name' => 'custom_marker',
            'label' => __('Custom Marker Image (Optional)'),
            'value' => $widget_saved_values['custom_marker'] ?? '',
        ]);

        $output .= Slider::get([
            'name' => 'map_height',
            'label' => __('Map Height (px)'),
            'value' => $widget_saved_values['map_height'] ?? 400,
            'max' => 1000,
            'min' => 200,
        ]);

        $output .= Select::get([
            'name' => 'show_info_window',
            'label' => __('Show Info Window'),
            'options' => [
                'yes' => __('Yes'),
                'no' => __('No'),
            ],
            'value' => $widget_saved_values['show_info_window'] ?? 'yes',
        ]);

        $output .= Select::get([
            'name' => 'fullscreen',
            'label' => __('Fullscreen Map'),
            'options' => [
                'no' => __('No'),
                'yes' => __('Yes'),
            ],
            'value' => $widget_saved_values['fullscreen'] ?? 'no',
        ]);

        $output .= Text::get([
            'name' => 'api_key',
            'label' => __('Google Maps API Key (Optional)'),
            'value' => $widget_saved_values['api_key'] ?? '',
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
            $address = SanitizeInput::esc_html($this->setting_item('address')) ?? '';
            $latitude = SanitizeInput::esc_html($this->setting_item('latitude')) ?? '';
            $longitude = SanitizeInput::esc_html($this->setting_item('longitude')) ?? '';
            $zoom_level = (int)($this->setting_item('zoom_level') ?? 15);
            $map_type = SanitizeInput::esc_html($this->setting_item('map_type')) ?? 'roadmap';
            $custom_marker = $this->setting_item('custom_marker') ?? '';
            $map_height = (int)($this->setting_item('map_height') ?? 400);
            $show_info_window = SanitizeInput::esc_html($this->setting_item('show_info_window')) ?? 'yes';
            $fullscreen = SanitizeInput::esc_html($this->setting_item('fullscreen')) ?? 'no';
            $api_key = SanitizeInput::esc_html($this->setting_item('api_key')) ?? '';
            $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
            $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
            $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

            // If no coordinates, try to use address
            if (empty($latitude) && empty($longitude) && !empty($address)) {
                // Address will be used in embed URL
            }

            $unique_id = 'google-map-' . ($section_id ?: uniqid());

            $data = [
                'unique_id' => $unique_id,
                'address' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'zoom_level' => $zoom_level,
                'map_type' => $map_type,
                'custom_marker' => $custom_marker,
                'map_height' => $map_height,
                'show_info_window' => $show_info_window,
                'fullscreen' => $fullscreen,
                'api_key' => $api_key,
                'padding_top' => $padding_top,
                'padding_bottom' => $padding_bottom,
                'section_id' => $section_id,
            ];

            return self::renderView('common.google-maps', $data);
        } catch (\Exception $e) {
            \Log::error('PageBuilder GoogleMaps Frontend Render Error', [
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
        return __('Google Maps');
    }
}

