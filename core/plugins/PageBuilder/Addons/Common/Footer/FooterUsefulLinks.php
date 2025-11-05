<?php

namespace Plugins\PageBuilder\Addons\Common\Footer;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\PageBuilderBase;

class FooterUsefulLinks extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Common/Footer/footer-useful-links.jpg';
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
            'value' => $widget_saved_values['title'] ?? __('Useful Links'),
        ]);

        $output .= Switcher::get([
            'name' => 'enable_home',
            'label' => __('Enable Home Link'),
            'value' => $widget_saved_values['enable_home'] ?? true,
        ]);

        $output .= Switcher::get([
            'name' => 'enable_products',
            'label' => __('Enable Products Link'),
            'value' => $widget_saved_values['enable_products'] ?? true,
            'info' => __('Only shows if Products module is active'),
        ]);

        $output .= Switcher::get([
            'name' => 'enable_categories',
            'label' => __('Enable Categories Link'),
            'value' => $widget_saved_values['enable_categories'] ?? true,
            'info' => __('Only shows if Products module is active'),
        ]);

        $output .= Switcher::get([
            'name' => 'enable_blog',
            'label' => __('Enable Blog Link'),
            'value' => $widget_saved_values['enable_blog'] ?? true,
            'info' => __('Only shows if Blog module is active'),
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
        $title = SanitizeInput::esc_html($this->setting_item('title') ?? __('Useful Links'));
        $enable_home = (bool)($this->setting_item('enable_home') ?? true);
        $enable_products = (bool)($this->setting_item('enable_products') ?? true);
        $enable_categories = (bool)($this->setting_item('enable_categories') ?? true);
        $enable_blog = (bool)($this->setting_item('enable_blog') ?? true);
        $alignment = SanitizeInput::esc_html($this->setting_item('alignment') ?? 'left');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id')) ?? '';

        // Get Home URL
        $home_url = null;
        $home_label = __('Home');
        if ($enable_home) {
            $home_page_id = get_static_option('home_page');
            if ($home_page_id) {
                $home_page = \App\Models\Page::find($home_page_id);
                if ($home_page && $home_page->slug) {
                    $home_url = url('/' . $home_page->slug->slug);
                    $home_label = $home_page->title ?? __('Home');
                } else {
                    $home_url = url('/');
                }
            } else {
                $home_url = url('/');
            }
        }

        // Get Products URL
        $products_url = null;
        $products_label = __('Products');
        if ($enable_products && isPluginActive('Product')) {
            try {
                if (\Illuminate\Support\Facades\Route::has('tenant.frontend.products')) {
                    $products_url = route('tenant.frontend.products');
                } else {
                    $products_url = url('/products');
                }
            } catch (\Exception $e) {
                $products_url = url('/products');
            }
        }

        // Get Categories URL
        $categories_url = null;
        $categories_label = __('Categories');
        if ($enable_categories && isPluginActive('Product')) {
            try {
                if (\Illuminate\Support\Facades\Route::has('tenant.frontend.categories')) {
                    $categories_url = route('tenant.frontend.categories');
                } else {
                    $categories_url = url('/categories');
                }
            } catch (\Exception $e) {
                $categories_url = url('/categories');
            }
        }

        // Get Blog URL
        $blog_url = null;
        $blog_label = __('Blog');
        if ($enable_blog && isPluginActive('Blog')) {
            $blog_page_id = get_static_option('blog_page');
            if ($blog_page_id) {
                $blog_page = \App\Models\Page::find($blog_page_id);
                if ($blog_page && $blog_page->slug) {
                    $blog_url = url('/' . $blog_page->slug->slug);
                    $blog_label = $blog_page->title ?? __('Blog');
                } else {
                    $blog_url = url('/blog');
                }
            } else {
                $blog_url = url('/blog');
            }
        }

        $links = [];
        if ($home_url) {
            $links[] = ['url' => $home_url, 'label' => $home_label];
        }
        if ($products_url) {
            $links[] = ['url' => $products_url, 'label' => $products_label];
        }
        if ($categories_url) {
            $links[] = ['url' => $categories_url, 'label' => $categories_label];
        }
        if ($blog_url) {
            $links[] = ['url' => $blog_url, 'label' => $blog_label];
        }

        $data = [
            'title' => $title,
            'links' => $links,
            'alignment' => $alignment,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'section_id' => $section_id,
        ];

        return self::renderView('common.footer.footer-useful-links', $data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Footer Useful Links');
    }
}

