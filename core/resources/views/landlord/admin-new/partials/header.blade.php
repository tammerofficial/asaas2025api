<!doctype html>
<html lang="{{ \App\Facades\GlobalLanguage::default_slug() }}" dir="{{ \App\Facades\GlobalLanguage::default_dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(!request()->routeIs('landlord.admin.home'))
            @yield('title') -
        @endif
        {{get_static_option('site_title', __('Xgenious'))}}
        @if(!empty(get_static_option('site_tag_line')))
            - {{get_static_option('site_tag_line')}}
        @endif
    </title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <!-- Favicon -->
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    
    <!-- Styles -->
    <link href="{{ global_asset('assets/landlord/admin/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/frontend/css/line-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/modern-dashboard-v2.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/toastr.css') }}" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @yield('style')
</head>
<body>
    <div class="dashboard-modern" x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', value => { if (value && window.innerWidth < 992) { document.body.style.overflow = 'hidden' } else { document.body.style.overflow = '' } })">
        @include('landlord.admin-new.partials.sidebar')
        
        <div class="dashboard-content">
            @include('landlord.admin-new.partials.topbar')
            
            <main>
                @yield('content')
            </main>
            
            @include('landlord.admin-new.partials.footer')
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{global_asset('assets/landlord/common/js/axios.min.js')}}"></script>
    <script src="{{global_asset('assets/landlord/common/js/sweetalert2.js')}}"></script>
    <script src="{{global_asset('assets/common/js/flatpickr.js')}}"></script>
    <x-flatpicker.flatpickr-locale/>
    <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/chart.js')}}" defer></script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>

