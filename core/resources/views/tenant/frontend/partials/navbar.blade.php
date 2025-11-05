@php
    // Check if PageBuilder header exists first
    $pagebuilder_header = \App\Facades\ThemeDataFacade::renderPageBuilderHeader();
@endphp

@if(!empty($pagebuilder_header))
    {{-- Render PageBuilder Header with Grid Layout --}}
    {!! $pagebuilder_header !!}
@else
    {{-- Fallback to Blade template (backward compatibility) --}}
    @php
        $current_theme_slug = getSelectedThemeSlug();
        $navbar_area_name = getHeaderNavbarArea();
        $navbar_view = 'themes.'.$current_theme_slug.'.headerNavbarArea.'.$navbar_area_name;
    @endphp
    
    @if(View::exists($navbar_view))
        @include($navbar_view)
    @else
        @include('tenant.frontend.partials.pages-portion.navbars.navbar-01')
    @endif
@endif
