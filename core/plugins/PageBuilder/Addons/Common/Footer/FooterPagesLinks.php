<?php

namespace Plugins\PageBuilder\Addons\Common\Footer;

use App\Helpers\SanitizeInput;
use App\Models\Page;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class FooterPagesLinks extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/Footer/footer-pages-links.jpg';
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
            'value' => $widget_saved_values['title'] ?? __('Important Pages'),
        ]);

        $output .= Text::get([
            'name' => 'excluded_page_ids',
            'label' => __('Excluded Page IDs'),
            'value' => $widget_saved_values['excluded_page_ids'] ?? '',
            'info' => __('Comma-separated page IDs to exclude (e.g., 1,2,3). Leave empty to show all active pages.'),
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
        $title = SanitizeInput::esc_html($this->setting_item('title') ?? __('Important Pages'));
        $alignment = SanitizeInput::esc_html($this->setting_item('alignment') ?? 'left');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        $excluded_page_ids = $this->setting_item('excluded_page_ids') ?? '';
        $excluded_ids = [];
        if (!empty($excluded_page_ids)) {
            $excluded_ids = array_filter(array_map('trim', explode(',', $excluded_page_ids)));
            $excluded_ids = array_map('intval', $excluded_ids);
        }

        // Get pages
        $pages_query = Page::where('status', 1)->with('slug');
        
        if (!empty($excluded_ids)) {
            $pages_query->whereNotIn('id', $excluded_ids);
        }
        
        $pages = $pages_query->get();

        $links = [];
        foreach ($pages as $page) {
            if ($page->slug) {
                $url = url('/' . $page->slug->slug);
                $links[] = [
                    'url' => $url,
                    'label' => SanitizeInput::esc_html($page->title),
                ];
            }
        }

        $data = [
            'title' => $title,
            'links' => $links,
            'alignment' => $alignment,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.footer.footer-pages-links', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Footer Pages Links');
    }
}

