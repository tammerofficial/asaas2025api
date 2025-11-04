<!doctype html>
<html lang="{{ \App\Facades\GlobalLanguage::default_slug() }}" dir="{{ \App\Facades\GlobalLanguage::default_dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Local assets only (CDN preconnect/prefetch removed) -->
    
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
    <!-- 🎛️ LOCAL STYLESHEETS (Reverted from CDN) -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    
    <!-- Select2 (local) -->
    <link href="{{ global_asset('assets/common/css/select2.min.css') }}" rel="stylesheet">
    
    <!-- Flatpickr (local) -->
    <link href="{{ global_asset('assets/common/css/flatpickr.min.css') }}" rel="stylesheet">
    
    <!-- Toastr (local) -->
    <link href="{{ global_asset('assets/common/css/toastr.css') }}" rel="stylesheet">
    
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    <!-- 📦 LOCAL STYLESHEETS (Required Custom Styles) -->
    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
    
    <!-- Critical CSS - Inline or load first -->
    <link href="{{ global_asset('assets/landlord/admin/css/vendor.bundle.base.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/style.css') }}" rel="stylesheet">
    
    <!-- Non-Critical CSS - Can be deferred -->
    <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ global_asset('assets/landlord/frontend/css/line-awesome.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
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
    </noscript>
</head>
<body>

<div class="container-scroller">
@include('landlord.admin.partials.topbar')
    <div class="container-fluid page-body-wrapper">
@include('landlord.admin.partials.sidebar')

