<?php

namespace Plugins\PageBuilder\Addons\Common\VideoBox;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class VideoBox extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/video-box.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'video_url',
            'label' => __('Video URL (YouTube/Vimeo)'),
            'value' => $widget_saved_values['video_url'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'thumbnail',
            'label' => __('Thumbnail Image'),
            'value' => $widget_saved_values['thumbnail'] ?? null,
        ]);

        $output .= Switcher::get([
            'name' => 'autoplay',
            'label' => __('Autoplay'),
            'value' => $widget_saved_values['autoplay'] ?? false,
        ]);

        $output .= Switcher::get([
            'name' => 'controls',
            'label' => __('Show Controls'),
            'value' => $widget_saved_values['controls'] ?? true,
        ]);

        $output .= Switcher::get([
            'name' => 'background_video',
            'label' => __('Background Video'),
            'value' => $widget_saved_values['background_video'] ?? false,
            'info' => __('Enable to make video as section background with overlay')
        ]);

        $output .= Switcher::get([
            'name' => 'gradient_overlay',
            'label' => __('Gradient Overlay'),
            'value' => $widget_saved_values['gradient_overlay'] ?? false,
            'info' => __('Add black gradient overlay from top and bottom')
        ]);

        $output .= Slider::get([
            'name' => 'overlay_opacity',
            'label' => __('Overlay Opacity (0-100)'),
            'value' => $widget_saved_values['overlay_opacity'] ?? 50,
            'min' => 0,
            'max' => 100,
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
        $video_url = $this->setting_item('video_url') ?? '';
        $thumbnail = $this->setting_item('thumbnail') ?? '';
        $autoplay = (bool)($this->setting_item('autoplay') ?? false);
        $controls = (bool)($this->setting_item('controls') ?? true);
        $background_video = (bool)($this->setting_item('background_video') ?? false);
        $gradient_overlay = (bool)($this->setting_item('gradient_overlay') ?? false);
        $overlay_opacity = (int)($this->setting_item('overlay_opacity') ?? 50);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        // Build YouTube URL with parameters for background video
        $embed_url = $this->getEmbedUrl($video_url);
        $youtube_id = $this->getYouTubeVideoId($video_url);
        
        if ($background_video && !empty($youtube_id)) {
            // For background video, add loop, mute, and autoplay
            $embed_url = 'https://www.youtube.com/embed/' . $youtube_id . '?autoplay=1&mute=1&loop=1&playlist=' . $youtube_id . '&controls=0&modestbranding=1&rel=0';
        }
        
        $data = [
            'video_url' => $embed_url,
            'youtube_id' => $youtube_id,
            'thumbnail' => $thumbnail,
            'autoplay' => $autoplay,
            'controls' => $controls,
            'background_video' => $background_video,
            'gradient_overlay' => $gradient_overlay,
            'overlay_opacity' => $overlay_opacity,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.video-box', $data);
    }

    private function getEmbedUrl($url)
    {
        $video_id = '';
        
        // YouTube
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches) || 
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $video_id = $matches[1];
            return 'https://www.youtube.com/embed/' . $video_id;
        }
        
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }
        
        return $url;
    }
    
    private function getYouTubeVideoId($url)
    {
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches) || 
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        return '';
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Video Box');
    }
}

