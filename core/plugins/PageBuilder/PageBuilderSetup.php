<?php


namespace Plugins\PageBuilder;

use App\Helpers\ModuleMetaData;
use App\Models\PageBuilder;
use Plugins\PageBuilder\Addons\Landlord\Blog\BlogSliderOne;
use Plugins\PageBuilder\Addons\Landlord\Blog\BlogStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Common\Brand;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactArea;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactCards;
use Plugins\PageBuilder\Addons\Landlord\Common\FaqOne;
use Plugins\PageBuilder\Addons\Landlord\Common\Feedback;
use Plugins\PageBuilder\Addons\Landlord\Common\HowItWorks;
use Plugins\PageBuilder\Addons\Landlord\Common\Newsletter;
use Plugins\PageBuilder\Addons\Landlord\Common\NumberCounter;
use Plugins\PageBuilder\Addons\Landlord\Common\PricePlan;
use Plugins\PageBuilder\Addons\Landlord\Common\RawHTML;
use Plugins\PageBuilder\Addons\Landlord\Common\TemplateDesign;
use Plugins\PageBuilder\Addons\Landlord\Common\Themes;
use Plugins\PageBuilder\Addons\Landlord\Common\VideoArea;
use Plugins\PageBuilder\Addons\Landlord\Common\WhyChooseUs;
use Plugins\PageBuilder\Addons\Common\Hero\Hero;
use Plugins\PageBuilder\Addons\Common\Heading\Heading;
use Plugins\PageBuilder\Addons\Common\TextEditor\TextEditor;
use Plugins\PageBuilder\Addons\Common\Button\Button;
use Plugins\PageBuilder\Addons\Common\IconBox\IconBox;
use Plugins\PageBuilder\Addons\Common\PricingTable\PricingTable;
use Plugins\PageBuilder\Addons\Common\Tabs\Tabs;
use Plugins\PageBuilder\Addons\Common\Testimonials\Testimonials;
use Plugins\PageBuilder\Addons\Common\Reviews\Reviews;
use Plugins\PageBuilder\Addons\Common\CallToAction\CallToAction;
use Plugins\PageBuilder\Addons\Common\LogosCarousel\LogosCarousel;
use Plugins\PageBuilder\Addons\Common\StepsTimeline\StepsTimeline;
use Plugins\PageBuilder\Addons\Common\VideoBox\VideoBox;
use Plugins\PageBuilder\Addons\Common\FormWidget\FormWidget;
use Plugins\PageBuilder\Addons\Common\BackgroundOverlay\BackgroundOverlay;
use Plugins\PageBuilder\Addons\Common\ImageLottie\ImageLottie;
use Plugins\PageBuilder\Addons\Common\Divider\Divider;
use Plugins\PageBuilder\Addons\Common\Spacer\Spacer;
use Plugins\PageBuilder\Addons\Common\Accordion\Accordion;
use Plugins\PageBuilder\Addons\Common\Table\Table;
use Plugins\PageBuilder\Addons\Common\Breadcrumb\Breadcrumb;
use Plugins\PageBuilder\Addons\Common\Alert\Alert;
use Plugins\PageBuilder\Addons\Common\Counter\Counter;
use Plugins\PageBuilder\Addons\Common\ProgressBar\ProgressBar;
use Plugins\PageBuilder\Addons\Common\FlipBox\FlipBox;
use Plugins\PageBuilder\Addons\Common\Typewriter\Typewriter;
use Plugins\PageBuilder\Addons\Common\Countdown\Countdown;
use Plugins\PageBuilder\Addons\Common\Gallery\Gallery;
use Plugins\PageBuilder\Addons\Common\ImageComparison\ImageComparison;
use Plugins\PageBuilder\Addons\Common\ModalBox\ModalBox;
use Plugins\PageBuilder\Addons\Common\HotSpots\HotSpots;
use Plugins\PageBuilder\Addons\Common\GoogleMaps\GoogleMaps;
use Plugins\PageBuilder\Addons\Common\Newsletter\Newsletter as CommonNewsletter;
use Plugins\PageBuilder\Addons\Common\SocialIcons\SocialIcons;
use Plugins\PageBuilder\Addons\Common\Header\HeaderLogo;
use Plugins\PageBuilder\Addons\Common\Header\HeaderMenu;
use Plugins\PageBuilder\Addons\Common\Header\HeaderSearch;
use Plugins\PageBuilder\Addons\Landlord\Header\AboutHeaderStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Header\FeaturesStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Header\HeaderStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Header\HeroBanner;
use Plugins\PageBuilder\Addons\Tenants\Aromatic\About\AboutImage;
use Plugins\PageBuilder\Addons\Tenants\Aromatic\Common\BrandTwo;
use Plugins\PageBuilder\Addons\Tenants\Aromatic\Common\InstagramWidget;
use Plugins\PageBuilder\Addons\Tenants\Aromatic\Product\BestProduct;
use Plugins\PageBuilder\Addons\Tenants\Bookpoint\Blog\RecentBlog;
use Plugins\PageBuilder\Addons\Tenants\Bookpoint\Common\TopAuthor;
use Plugins\PageBuilder\Addons\Tenants\Casual\Common\CampaignSale;
use Plugins\PageBuilder\Addons\Tenants\Casual\Common\Categories;
use Plugins\PageBuilder\Addons\Tenants\Casual\Product\PopularCollection;
use Plugins\PageBuilder\Addons\Tenants\Casual\Product\PopularProduct;
use Plugins\PageBuilder\Addons\Tenants\Electro\Common\CampaignCard;
use Plugins\PageBuilder\Addons\Tenants\Electro\Common\NewReleaseCard;
use Plugins\PageBuilder\Addons\Tenants\Electro\Product\FeaturedCollection;
use Plugins\PageBuilder\Addons\Tenants\Electro\Product\NewProducts;
use Plugins\PageBuilder\Addons\Tenants\Electro\Product\PopularProducts;
use Plugins\PageBuilder\Addons\Tenants\Furnito\Product\TrendingProducts;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Contact\ContactAreaOne;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Contact\GoogleMap;
use Plugins\PageBuilder\Addons\Tenants\Medicom\Header\Header;
use Plugins\PageBuilder\Addons\Tenants\Service\ServiceOne;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\CollectionArea;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\DealArea;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\Services;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\Testimonial;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\TestimonialTwo;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\Team;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Header\HeaderOne;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Product\FeaturedProductSlider;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Product\FlashStore;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\Product\ProductTypeList;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\About\AboutStory;
use Plugins\PageBuilder\Addons\Tenants\Hexfashion\About\AboutCounter;
use Plugins\PageBuilder\Addons\Tenants\Furnito\Blog\BlogOne;
use Plugins\PageBuilder\Addons\Tenants\Furnito\Common\CategoriesSlider;
use Plugins\PageBuilder\Addons\Tenants\Furnito\Common\CollectionCard;
use Plugins\PageBuilder\Addons\Tenants\Furnito\Product\NewCollection;


class PageBuilderSetup
{
    private static function registerd_widgets(): array
    {
        $customAddons = [];
        $addons = [];

        if (!is_null(tenant())) {
            $theme = tenant()->theme_slug;

            // Tenant Register

            if ($theme == 'hexfashion') {
                // Theme Hexfashion
                $addons = [
                    HeaderOne::class,
                    Addons\Tenants\Hexfashion\Blog\BlogOne::class,
                    Addons\Tenants\Hexfashion\Common\Brand::class,
                    DealArea::class,
                    ContactAreaOne::class,
                    GoogleMap::class,
                    ServiceOne::class,
                    CollectionArea::class,
                    FeaturedProductSlider::class,
                    ProductTypeList::class,
                    FlashStore::class,
                    Services::class,
                    Testimonial::class,
                    TestimonialTwo::class,
                    AboutStory::class,
                    AboutCounter::class,
                    Team::class,
                ];
            } elseif ($theme == 'furnito') {
                // Theme Furnito
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Header\HeaderOne::class,
                    CollectionCard::class,
                    TrendingProducts::class,
                    CategoriesSlider::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\Brand::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\Testimonial::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\CollectionArea::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Product\ProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Contact\ContactAreaOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Contact\GoogleMap::class,
                    BlogOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\About\AboutStory::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\About\AboutCounter::class
                ];
            } elseif ($theme == 'medicom') {
                // Theme Medicom
                $addons = [
                    Header::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Product\FeaturedProductSlider::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Product\CategoriesSlider::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Common\CollectionCard::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Product\ProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\Hexfashion\Common\Brand::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\About\AboutStory::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\About\AboutCounter::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Contact\ContactAreaOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Medicom\Contact\GoogleMap::class

                ];
            } elseif ($theme == 'bookpoint') {
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Header\Header::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Product\ProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Product\PhysicalProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Common\CollectionCard::class,
                    TopAuthor::class,
                    RecentBlog::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Product\FeaturedProductSlider::class,
                    \Plugins\PageBuilder\Addons\Tenants\Bookpoint\Product\FeaturedPhysicalProductSlider::class,

                    //temporary addons
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\About\AboutCounter::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\About\AboutStory::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\Testimonial::class,
                    \Plugins\PageBuilder\Addons\Tenants\Furnito\Common\Services::class,
                    ContactAreaOne::class,
                ];
            } elseif ($theme == 'aromatic') {
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Header\HeaderOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Product\NewCollection::class,
                    BestProduct::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Product\ProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Common\Brand::class,
                    BrandTwo::class,
                    InstagramWidget::class,
                    AboutImage::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Contact\GoogleMap::class,
                    \Plugins\PageBuilder\Addons\Tenants\Aromatic\Contact\ContactArea::class
                ];
            } elseif ($theme == 'casual') {
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\Casual\Header\Header::class,
                    Categories::class,
                    PopularCollection::class,
                    \Plugins\PageBuilder\Addons\Tenants\Casual\Product\ProductTypeList::class,
                    CampaignSale::class,
                    \Plugins\PageBuilder\Addons\Tenants\Casual\Product\FlashStore::class,
                    \Plugins\PageBuilder\Addons\Tenants\Casual\Blog\BlogOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Casual\Common\Brand::class
                ];
            } elseif ($theme == 'electro') {
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Header\Header::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Common\CollectionCard::class,
                    FeaturedCollection::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Product\ProductTypeList::class,
                    CampaignCard::class,
                    PopularProducts::class,
                    NewReleaseCard::class,
                    NewProducts::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Common\Brand::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Blog\BlogOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\About\AboutImage::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Common\Brand::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Contact\ContactArea::class,
                    \Plugins\PageBuilder\Addons\Tenants\Electro\Contact\GoogleMap::class
                ];
            }

            // Global addons for all theme
            $globalAddons = [
                RawHTML::class,
                FaqOne::class,
                // Common theme-agnostic addons
                HeaderLogo::class,
                HeaderMenu::class,
                HeaderSearch::class,
                Hero::class,
                Heading::class,
                TextEditor::class,
                Button::class,
                IconBox::class,
                PricingTable::class,
                Tabs::class,
                Testimonials::class,
                Reviews::class,
                CallToAction::class,
                LogosCarousel::class,
                StepsTimeline::class,
                VideoBox::class,
                FormWidget::class,
                BackgroundOverlay::class,
                ImageLottie::class,
                // Phase 1 - Safest addons (no external dependencies)
                Divider::class,
                Spacer::class,
                Accordion::class,
                Table::class,
                Breadcrumb::class,
                Alert::class,
                // Phase 2 - Moderate addons (light JavaScript)
                Counter::class,
                ProgressBar::class,
                FlipBox::class,
                Typewriter::class,
                // Phase 3 - Higher complexity addons
                Countdown::class,
                Gallery::class,
                ImageComparison::class,
                ModalBox::class,
                HotSpots::class,
                // Phase 4 - External dependencies
                GoogleMaps::class,
                CommonNewsletter::class,
                SocialIcons::class,
            ];

            foreach ($globalAddons as $globalItem) {
                array_push($addons, $globalItem);
            }

            // Third party custom addons
            $customAddons = (new ModuleMetaData())->getTenantPageBuilderAddonList();
        } else {
            //Admin Register
            $addons = [
                HeaderStyleOne::class,
                FeaturesStyleOne::class,
                Themes::class,
                HowItWorks::class,
                WhyChooseUs::class,
                TemplateDesign::class,
                PricePlan::class,
                Feedback::class,
                FaqOne::class,
                ContactArea::class,
                BlogSliderOne::class,
                NumberCounter::class,
                Newsletter::class,
                AboutHeaderStyleOne::class,
                ContactCards::class,
                BlogStyleOne::class,
                RawHTML::class,
                VideoArea::class
            ];

            // Third party custom addons
            $customAddons = (new ModuleMetaData())->getLandlordPageBuilderAddonList();
        }

        //check module wise widget by set condition
        return array_merge($addons, $customAddons);
    }

    public static function get_tenant_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget) {
            try {
                $widget_instance = new  $widget();
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }

            if ($widget_instance->enable()) {
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }
        }
        return $widgets_markup;
    }

    public static function get_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget) {
            try {
                $widget_instance = new  $widget();
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }

            if ($widget_instance->enable()) {
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }
        }
        return $widgets_markup;
    }

    private static function render_admin_addon_item($args): string
    {
        return '<li class="ui-state-default widget-handler" data-name="' . $args['addon_name'] . '" data-namespace="' . base64_encode($args['addon_namespace']) . '" >
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' . $args['addon_title'] . $args['preview_image'] . '</h4>
                </li>';
    }

    public static function render_widgets_by_name_for_admin($args)
    {
        $widget_class = $args['namespace'];
        if (class_exists($widget_class)) {
            $instance = new $widget_class($args);
            if ($instance->enable()) {
                return $instance->admin_render();
            }
        }
    }

    public static function render_widgets_by_name_for_frontend($args)
    {
        $widget_class = $args['namespace'];
        if (class_exists($widget_class)) {
            try {
                $instance = new $widget_class($args);
                if ($instance->enable()) {
                    try {
                        return $instance->frontend_render();
                    } catch (\Exception $e) {
                        \Log::error('PageBuilder Frontend Render Error', [
                            'addon' => $widget_class,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return ''; // Graceful degradation - don't break page
                    }
                }
            } catch (\Exception $e) {
                \Log::error('PageBuilder Addon Instantiation Error', [
                    'addon' => $widget_class,
                    'error' => $e->getMessage()
                ]);
                return '';
            }
        }
        return '';
    }

    public static function render_frontend_pagebuilder_content_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'ASC')->get();
        
        // Special wrapper for header location to create grid layout
        if ($location === 'header') {
            // Get header settings
            $is_sticky = get_static_option('header_builder_sticky') === 'yes';
            $is_transparent = get_static_option('header_builder_transparent') === 'yes';
            
            $sticky_class = $is_sticky ? 'pagebuilder-header-sticky' : '';
            $transparent_class = $is_transparent ? 'pagebuilder-header-transparent' : '';
            
            // Add special class if transparent but not sticky (for absolute positioning)
            if ($is_transparent && !$is_sticky) {
                $transparent_class .= ' pagebuilder-header-transparent-only';
            }
            
            $wrapper_classes = 'pagebuilder-header-wrapper ' . trim($sticky_class . ' ' . $transparent_class);
            
            $output .= '<style>
/* Header Builder Grid Layout */
.pagebuilder-header-wrapper {
    width: 100%;
    background: var(--header-bg-color, #fff);
    padding: 10px 20px;
    border-bottom: 1px solid var(--header-border-color, #e5e5e5);
    z-index: 1000;
    transition: all 0.3s ease;
}

/* Reduce padding for header items */
.pagebuilder-header-wrapper .pagebuilder-header-item {
    padding: 0 !important;
}

/* Remove padding from header widgets when inside header */
.pagebuilder-header-wrapper .common-header-logo-section,
.pagebuilder-header-wrapper .common-header-menu-section,
.pagebuilder-header-wrapper .common-header-search-section {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

/* Ignore data-padding attributes when inside header - Override all values including 110 */
.pagebuilder-header-wrapper [data-padding-top],
.pagebuilder-header-wrapper [data-padding-bottom],
.pagebuilder-header-wrapper [data-padding-top="110"],
.pagebuilder-header-wrapper [data-padding-top="100"],
.pagebuilder-header-wrapper [data-padding-top="90"],
.pagebuilder-header-wrapper [data-padding-top="80"],
.pagebuilder-header-wrapper [data-padding-top="70"],
.pagebuilder-header-wrapper [data-padding-top="60"],
.pagebuilder-header-wrapper [data-padding-top="50"],
.pagebuilder-header-wrapper [data-padding-bottom="110"],
.pagebuilder-header-wrapper [data-padding-bottom="100"] {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

/* More specific override for all data-padding values inside header */
.pagebuilder-header-wrapper [data-padding-top] {
    padding-top: 0 !important;
}

.pagebuilder-header-wrapper [data-padding-bottom] {
    padding-bottom: 0 !important;
}

/* Ensure header starts from top of page */
.pagebuilder-header-wrapper {
    margin-top: 0 !important;
    top: 0;
}

/* Remove any spacing above header */
body > .pagebuilder-header-wrapper:first-child,
.pagebuilder-header-wrapper:first-of-type {
    margin-top: 0 !important;
}

/* Sticky Header */
.pagebuilder-header-sticky {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    box-shadow: 0 2px 10px rgba(245, 237, 237, 0.1);
}

/* Transparent Header */
.pagebuilder-header-transparent {
    background: transparent !important;
    border-bottom: none !important;
}

/* Transparent Header (without sticky) - overlay on top */
.pagebuilder-header-transparent:not(.pagebuilder-header-sticky) {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

/* Remove margin/padding from content when header is transparent (not sticky) */
.pagebuilder-header-transparent:not(.pagebuilder-header-sticky) + *,
.pagebuilder-header-transparent:not(.pagebuilder-header-sticky) ~ * {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Body padding when sticky */
body.pagebuilder-header-sticky-active {
    padding-top: var(--header-height, 80px);
}

/* Adjust content spacing when sticky */
.pagebuilder-header-sticky + * {
    margin-top: 0;
}

/* Ensure body starts from top when transparent header (not sticky) */
body:has(.pagebuilder-header-transparent:not(.pagebuilder-header-sticky)) {
    padding-top: 0 !important;
}

/* Alternative class for transparent header without sticky */
.pagebuilder-header-transparent-only {
    position: absolute;
    top: 0 !important;
    left: 0;
    right: 0;
    z-index: 1000;
    margin-top: 0 !important;
}

/* Ensure header is always at top */
.pagebuilder-header-wrapper.pagebuilder-header-transparent-only {
    top: 0 !important;
}

/* Remove any gap before header */
body:has(.pagebuilder-header-wrapper) {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
.pagebuilder-header-container {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 20px;
    align-items: center;
    max-width: var(--container-width, 1200px);
    margin: 0 auto;
    width: 100%;
}
.pagebuilder-header-item {
    display: flex;
    align-items: center;
    min-height: 50px;
}
.pagebuilder-header-item:first-child { justify-content: flex-start; }
.pagebuilder-header-item:nth-child(2) { justify-content: center; }
.pagebuilder-header-item:last-child,
.pagebuilder-header-item:nth-child(3) { justify-content: flex-end; }
.pagebuilder-header-wrapper .common-header-logo-section,
.pagebuilder-header-wrapper .common-header-menu-section,
.pagebuilder-header-wrapper .common-header-search-section {
    width: 100%;
    justify-content: inherit !important;
}
.pagebuilder-header-wrapper .common-header-logo-section { justify-content: flex-start !important; }
.pagebuilder-header-wrapper .common-header-menu-section { justify-content: center !important; }
.pagebuilder-header-wrapper .common-header-search-section { justify-content: flex-end !important; }
@media (max-width: 991px) {
    .pagebuilder-header-container {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 10px 0;
    }
    .pagebuilder-header-item {
        justify-content: center !important;
        width: 100%;
    }
    .pagebuilder-header-wrapper .common-header-logo-section,
    .pagebuilder-header-wrapper .common-header-menu-section,
    .pagebuilder-header-wrapper .common-header-search-section {
        justify-content: center !important;
    }
    .pagebuilder-header-wrapper .header-menu-nav ul {
        justify-content: center;
        flex-wrap: wrap;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .pagebuilder-header-wrapper .header-menu-nav ul li {
        list-style: none !important;
        list-style-type: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: inline-block !important;
        unicode-bidi: normal !important;
    }
    .pagebuilder-header-wrapper .header-menu-nav ul li a {
        position: relative;
        text-decoration: none;
        display: inline-block;
        padding: 8px 0;
        transition: all 0.3s ease;
    }
    .pagebuilder-header-wrapper .header-menu-nav ul li a::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #fff;
        transition: width 0.3s ease;
    }
    .pagebuilder-header-wrapper .header-menu-nav ul li a:hover::after {
        width: 100%;
    }
}
@media (min-width: 992px) and (max-width: 1199px) {
    .pagebuilder-header-container {
        grid-template-columns: 1fr 1.5fr 1fr;
        gap: 15px;
    }
}
[dir="rtl"] .pagebuilder-header-container { direction: rtl; }
[dir="rtl"] .pagebuilder-header-item:first-child { justify-content: flex-end; }
[dir="rtl"] .pagebuilder-header-item:last-child,
[dir="rtl"] .pagebuilder-header-item:nth-child(3) { justify-content: flex-start; }

/* Ensure header content is visible on transparent background */
.pagebuilder-header-transparent .common-header-logo-section a,
.pagebuilder-header-transparent .common-header-menu-section a,
.pagebuilder-header-transparent .common-header-search-section {
    color: var(--header-text-color, #fff);
}

.pagebuilder-header-transparent .common-header-menu-section .header-menu-nav ul li a {
    color: var(--header-text-color, #fff);
}

.pagebuilder-header-transparent .common-header-menu-section .header-menu-nav ul li a:hover {
    color: var(--header-hover-color, rgba(255,255,255,0.8));
}

/* Ensure menu items are horizontal and remove list style */
.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul {
    display: flex !important;
    flex-direction: row !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    gap: 24px;
    align-items: center;
}

.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul li {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    display: inline-block !important;
    unicode-bidi: normal !important;
}

/* Override any list-item display */
.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul li,
.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav li {
    display: inline-block !important;
    list-style: none !important;
    list-style-type: none !important;
}

.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul li a {
    position: relative;
    text-decoration: none;
    display: inline-block;
    padding: 8px 0;
    transition: all 0.3s ease;
}

/* White underline on hover */
.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul li a::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #fff;
    transition: width 0.3s ease;
}

.pagebuilder-header-wrapper .common-header-menu-section .header-menu-nav ul li a:hover::after {
    width: 100%;
}

/* Add shadow on scroll for sticky transparent header */
.pagebuilder-header-sticky.pagebuilder-header-transparent.scrolled {
    background: rgba(255,255,255,0.95) !important;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
}

.pagebuilder-header-sticky.pagebuilder-header-transparent.scrolled .common-header-menu-section .header-menu-nav ul li a {
    color: var(--heading-color, #111);
}
</style>';

            // Add JavaScript for sticky transparent header scroll effect
            if ($is_sticky && $is_transparent) {
                $output .= '<script>
                (function() {
                    var header = document.querySelector(".pagebuilder-header-wrapper");
                    if (header) {
                        var scrolled = false;
                        window.addEventListener("scroll", function() {
                            if (window.scrollY > 50 && !scrolled) {
                                header.classList.add("scrolled");
                                scrolled = true;
                            } else if (window.scrollY <= 50 && scrolled) {
                                header.classList.remove("scrolled");
                                scrolled = false;
                            }
                        });
                    }
                })();
                </script>';
            }
            
            // Add body class and padding if sticky
            if ($is_sticky) {
                $output .= '<script>
                (function() {
                    document.body.classList.add("pagebuilder-header-sticky-active");
                    var header = document.querySelector(".pagebuilder-header-wrapper");
                    if (header) {
                        var height = header.offsetHeight;
                        document.documentElement.style.setProperty("--header-height", height + "px");
                    }
                })();
                </script>';
            } else if ($is_transparent) {
                // If transparent but not sticky, remove any padding/margin from next element
                $output .= '<script>
                (function() {
                    var header = document.querySelector(".pagebuilder-header-wrapper");
                    if (header) {
                        // Ensure header is at top
                        header.style.top = "0";
                        header.style.marginTop = "0";
                        header.style.paddingTop = "10px";
                        
                        // Remove padding from header items
                        var items = header.querySelectorAll(".pagebuilder-header-item");
                        items.forEach(function(item) {
                            item.style.padding = "0";
                        });
                        
                        // Remove padding from widgets
                        var widgets = header.querySelectorAll("[data-padding-top], [data-padding-bottom]");
                        widgets.forEach(function(widget) {
                            widget.style.paddingTop = "0";
                            widget.style.paddingBottom = "0";
                        });
                        
                        // Remove margin/padding from next element
                        if (header.nextElementSibling) {
                            header.nextElementSibling.style.marginTop = "0";
                            header.nextElementSibling.style.paddingTop = "0";
                        }
                    }
                    // Also remove body padding if exists
                    document.body.style.paddingTop = "0";
                    document.body.style.marginTop = "0";
                })();
                </script>';
            } else {
                // For regular header, also reduce padding
                $output .= '<script>
                (function() {
                    var header = document.querySelector(".pagebuilder-header-wrapper");
                    if (header) {
                        header.style.paddingTop = "10px";
                        header.style.paddingBottom = "10px";
                        
                        // Remove padding from header items
                        var items = header.querySelectorAll(".pagebuilder-header-item");
                        items.forEach(function(item) {
                            item.style.padding = "0";
                        });
                        
                        // Remove padding from widgets
                        var widgets = header.querySelectorAll("[data-padding-top], [data-padding-bottom]");
                        widgets.forEach(function(widget) {
                            widget.style.paddingTop = "0";
                            widget.style.paddingBottom = "0";
                        });
                    }
                })();
                </script>';
            }
            
            $output .= '<div class="' . $wrapper_classes . '">';
            $output .= '<div class="pagebuilder-header-container">';
        }
        
        foreach ($all_widgets as $widget) {
            $widget_output = self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'location' => $location,
                'id' => $widget->id,
            ]);
            
            if ($location === 'header') {
                $output .= '<div class="pagebuilder-header-item">' . $widget_output . '</div>';
            } else {
                $output .= $widget_output;
            }
        }
        
        // Close header wrapper
        if ($location === 'header') {
            $output .= '</div></div>';
        }
        
        return $output;
    }

    public static function get_saved_addons_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }

    public static function get_saved_addons_for_dynamic_page($page_type, $page_id): string
    {
        $output = '';
        $all_widgets = \Cache::remember('page_id-' . $page_id, 60 * 60 * 24, function () use ($page_type, $page_id) {
            return PageBuilder::where(['addon_page_type' => $page_type, 'addon_page_id' => $page_id])->orderBy('addon_order', 'asc')->get();
        });

        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }

    public static function render_frontend_pagebuilder_content_for_dynamic_page($page_type, $page_id): string
    {
        $output = '';
        $all_widgets = \Cache::remember('page_id-' . $page_id, 24 * 60 * 60, function () use ($page_type, $page_id) {
            return PageBuilder::where(['addon_page_type' => $page_type, 'addon_page_id' => $page_id])->orderBy('addon_order', 'asc')->get();
        });

        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }
}
