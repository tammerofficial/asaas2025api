<!doctype html>
<html lang="{{ \App\Facades\GlobalLanguage::default_slug() }}" dir="{{ \App\Facades\GlobalLanguage::default_dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- DNS Prefetch for CDNs -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <!-- Preconnect for faster CDN loading -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>
        @if(!request()->routeIs('landlord.admin.home'))
            @yield('title')  -
        @endif
        {{get_static_option('site_title', __('Xgenious'))}}
        @if(!empty(get_static_option('site_tag_line')))
            - {{get_static_option('site_tag_line')}}
        @endif
    </title>
    
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <!-- 🚀 LCP IMAGE PRELOAD (Critical for Performance) -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    @if(request()->routeIs('landlord.admin.home'))
        <!-- Preload LCP image for dashboard -->
        <link rel="preload" 
              href="{{global_asset('assets/landlord/admin/images/circle.png')}}" 
              as="image" 
              fetchpriority="high">
    @endif
    
    <!-- Preload critical fonts -->
    <link rel="preload" href="{{global_asset('assets/landlord/admin/fonts/Ubuntu/Ubuntu-Regular.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{global_asset('assets/landlord/admin/fonts/Ubuntu/Ubuntu-Medium.woff2')}}" as="font" type="font/woff2" crossorigin>
    
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <!-- 🚀 CDN STYLESHEETS (Fast Loading) -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    
    <!-- Google Fonts (with display=swap for performance) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Material Design Icons CDN -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" as="style">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    
    <!-- Select2 CDN -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" as="style">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <!-- Flatpickr CDN -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" as="style">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet">
    
    <!-- Toastr CDN -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" as="style">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
    
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <!-- 📦 LOCAL STYLESHEETS (Required Custom Styles) -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    
    <!-- Critical CSS - Inline or load first -->
    <link href="{{ global_asset('assets/landlord/admin/css/vendor.bundle.base.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/style.css') }}" rel="stylesheet">
    
    <!-- Non-Critical CSS - Can be deferred -->
    <link rel="preload" href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet"></noscript>
    
    <link rel="preload" href="{{ global_asset('assets/landlord/frontend/css/line-awesome.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="{{ global_asset('assets/landlord/frontend/css/line-awesome.min.css') }}" rel="stylesheet"></noscript>
    
    <link rel="preload" href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet"></noscript>
    
    <link href="{{ global_asset('assets/common/css/custom-style.css') }}" rel="stylesheet">
    
    <!-- Dark mode CSS -->
    @if(!empty(get_static_option('dark_mode_for_admin_panel')))
        <link href="{{ global_asset('assets/landlord/admin/css/dark-mode.css') }}" rel="stylesheet">
    @endif
    
    <!-- RTL mode -->
    @if(\App\Enums\LanguageEnums::getdirection(get_user_lang_direction()) === 'rtl')
        <link href="{{ global_asset('assets/landlord/admin/css/rtl.css') }}" rel="stylesheet">
    @endif
    
    @yield('style')
    
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <!-- ⚡ Performance Optimization -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <noscript>
        <!-- Fallback for deferred CSS -->
        <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
        <link href="{{ global_asset('assets/landlord/frontend/css/line-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet">
        <link href="{{ global_asset('assets/common/css/custom-style.css') }}" rel="stylesheet">
    </noscript>
</head>
<body>

<div class="container-scroller">
@include('landlord.admin.partials.topbar')
    <div class="container-fluid page-body-wrapper">
@include('landlord.admin.partials.sidebar')

