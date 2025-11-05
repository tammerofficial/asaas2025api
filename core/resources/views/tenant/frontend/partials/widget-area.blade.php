@php
    // Check if PageBuilder footer exists first
    $pagebuilder_footer = \App\Facades\ThemeDataFacade::renderPageBuilderFooter();
@endphp

@if(!empty($pagebuilder_footer))
    {{-- Render PageBuilder Footer --}}
    {!! $pagebuilder_footer !!}
@else
    {{-- Fallback to Blade template (backward compatibility) --}}
    @php
        $current_theme_slug = getSelectedThemeSlug();
        $widget_area_name = getFooterWidgetArea();
        $footer_view = 'themes.'.$current_theme_slug.'.footerWidgetArea.'.$widget_area_name;
    @endphp
    
    @if(View::exists($footer_view))
        @include($footer_view)
    @else
        @include('tenant.frontend.partials.pages-portion.footers.footer-medicom')
    @endif
@endif
