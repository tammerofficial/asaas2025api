<!DOCTYPE html>
<html lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}"
      dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}">

<head>
    {!! renderHeadStartHooks() !!}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="canonical" href="{{canonical_url()}}" />

    @php
        $theme_slug = getSelectedThemeSlug();
        $theme_header_css_files = \App\Facades\ThemeDataFacade::getHeaderHookCssFiles();
        $theme_header_rtl_css_files = \App\Facades\ThemeDataFacade::getHeaderHookRtlCssFiles();
        $theme_header_js_files = \App\Facades\ThemeDataFacade::getHeaderHookJsFiles();

    @endphp

    {!! load_google_fonts($theme_slug) !!}
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}

    <title>
        @if(!request()->routeIs('tenant.frontend.homepage'))
            @yield('title')
            -
            {{get_static_option('site_title')}}
        @else
            {{get_static_option('site_title')}}
            @if(!empty(get_static_option('site_tag_line')))
                - {{get_static_option('site_tag_line')}}
            @endif
        @endif
    </title>

    {!! render_favicon_by_id(filter_static_option_value('site_favicon', $global_static_field_data)) !!}

    @php
        $loadCoreStyle = loadCoreStyle();
    @endphp


{{--    {!! /* render themes styles */ !!}--}}

    @if(in_array('bootstrap.min', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/bootstrap.min.css')}}">
    @endif
    @if(in_array('animate', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/animate.css')}}">
    @endif
    @if(in_array('slick', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/slick.css')}}">
    @endif
    @if(in_array('nice-select', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/nice-select.css')}}">
    @endif
    @if(in_array('line-awesome.min', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/line-awesome.min.css')}}">
    @endif
    @if(in_array('jquery.ihavecookies', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/common/css/jquery.ihavecookies.css')}}">
    @endif
    @if(in_array('odometer', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/odometer.css')}}">
    @endif
    @if(in_array('common', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/common.css')}}">
    @endif
    @if(in_array('magnific-popup', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/magnific-popup.css')}}">
    @endif
    @if(in_array('helpers', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/helpers.css')}}">
    @endif
    @if(in_array('toastr', $loadCoreStyle))
        <link rel="stylesheet" href="{{ global_asset('assets/common/css/toastr.css') }}">
    @endif
    @if(in_array('loader', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/common/css/loader.css')}}">
    @endif

    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/footer-style.css')}}">

    @foreach($theme_header_css_files ?? [] as $cssFile)
        <link rel="stylesheet" href="{{ loadCss($cssFile) }}" type="text/css" />
    @endforeach

    @if(\App\Facades\GlobalLanguage::user_lang_dir() == 'rtl')
        @foreach($theme_header_rtl_css_files ?? [] as $cssFile)
            <link rel="stylesheet" href="{{ loadCss($cssFile) }}" type="text/css" />
        @endforeach
    @endif

    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/custom-style.css')}}">

    @if(request()->routeIs('tenant.frontend.homepage'))
        @include('tenant.frontend.partials.meta-data')
    @else
        @yield('meta-data')
    @endif

    @include('tenant.frontend.partials.css-variable', ['theme_slug' => $theme_slug])

    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/shop-order-custom.css')}}">
    @if(getSelectedThemeSlug() == 'bookpoint')
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/digital-shop-common.css')}}">
    @else
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/digital-shop-common.css')}}">
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/shop-common.css')}}">
    @endif


    @yield('style')

    @php
        $tenant_id = !empty(tenant()) ? tenant()->id : '';
        $file = file_exists('assets/tenant/frontend/css/'.$tenant_id.'/dynamic-style.css');
    @endphp
    @if($file)
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/'. $tenant_id .'/dynamic-style.css')}}">
    @endif

    {{-- Header Overlay Fix for PageBuilder --}}
    <style>
        /* Ensure Header appears above PageBuilder content (especially video backgrounds) */
        .header-style-01,
        .navbar-area,
        header {
            position: relative;
            z-index: 9999 !important;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .navbar-area.nav-absolute {
            position: absolute;
            z-index: 9999 !important;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        /* Ensure horizontal layout for Logo, Menu, Search */
        .navbar-area .nav-container {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
        }
        
        .navbar-area .nav-container .responsive-mobile-menu {
            display: flex !important;
            align-items: center !important;
            flex: 0 0 auto;
            order: 1;
        }
        
        .navbar-area .nav-container .navbar-collapse {
            flex: 1 1 auto;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            order: 2;
        }
        
        .navbar-area .nav-container .navbar-collapse .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
        }
        
        .navbar-area .nav-container .nav-right-content,
        .navbar-area .nav-container .navbar-right-content {
            flex: 0 0 auto;
            display: flex !important;
            align-items: center !important;
            order: 3;
        }
        
        /* Ensure all navbar items are horizontal */
        @media (min-width: 992px) {
            .navbar-area .nav-container > * {
                display: flex !important;
                align-items: center !important;
            }
        }
        
        /* Ensure PageBuilder content is below header */
        .common-video-box-section,
        .video-section-background,
        .page-builder-content {
            position: relative;
            z-index: 1;
        }
        
        /* Header should be visible on top of video backgrounds */
        .video-background-fullscreen,
        .video-section-background {
            z-index: 0 !important;
        }
        
        /* Make sure header is always visible */
        @media (min-width: 992px) {
            .navbar-area .nav-container {
                flex-direction: row;
            }
            
            .navbar-area .nav-container .logo-wrapper,
            .navbar-area .nav-container .navbar-collapse,
            .navbar-area .nav-container .nav-right-content {
                display: flex;
                align-items: center;
            }
        }
    </style>

    @foreach($theme_header_js_files ?? [] as $jsFile)
        <script src="{{loadJs($jsFile)}}"></script>
    @endforeach
    {!! renderHeadEndHooks() !!}
</head>

<body class="{{$theme_slug}}">
{!! renderBodyStartHooks() !!}

@include('tenant.frontend.partials.loader')
@include('tenant.frontend.partials.navbar')

<div class="search-suggestion-overlay"></div>
