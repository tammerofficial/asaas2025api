<header class="header-style-01">
    <!-- Topbar area Starts -->
    @if(get_static_option('topbar_show_hide'))
        <div class="topbar-area index-07 color-04 topbar-bg-1">
            <div class="container-three">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="topbar-left-contents">
                            <div class="topbar-left-flex">
                                @if(get_static_option('social_info_show_hide'))
                                    @php
                                        $social_links = \App\Models\TopbarInfo::all();
                                    @endphp
                                    <ul class="topbar-social">
                                        @foreach($social_links as $item)
                                            <li>
                                                <a href="{{$item->url ?? '#'}}">
                                                    <i class="{{$item->icon}}"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="topbar-right-contents">
                            <div class="topbar-right-flex">
                                @if(get_static_option('topbar_menu_show_hide'))
                                    @php
                                        $topbar_menu_id = get_static_option('topbar_menu') ?? 1;

                                    @endphp
                                    <div class="topbar-faq text-white">
                                        <ul class="d-flex gap-3">
                                            {!! render_frontend_menu($topbar_menu_id) !!}
                                        </ul>
                                    </div>
                                @endif

                                @if(get_static_option('contact_info_show_hide'))
                                    @php
                                        $topbar_phone = get_static_option('topbar_phone');
                                    @endphp
                                    <span class="call-us text-white"> {{__('Call Us:')}} <a href="tel:{{$topbar_phone}}"> {{$topbar_phone}} </a> </span>
                                @endif

                                <div class="login-account">
                                    <a href="javascript:void(0)" class="accounts hover-color-four text-white"> Account
                                        <i class="las la-user"></i> </a>
                                    <ul class="account-list-item hover-color-four">
                                        @auth('web')
                                            <li class="list">
                                                <a href="{{route('tenant.user.home')}}"> {{__('Dashboard')}} </a>
                                            </li>
                                            <li class="list">
                                                <a href="{{route('tenant.user.logout')}}"> {{__('Log Out')}} </a>
                                            </li>
                                        @else
                                            <li class="list">
                                                <a href="{{route('tenant.user.login')}}"> {{__('Sign In')}} </a>
                                            </li>
                                            <li class="list">
                                                <a href="{{route('tenant.user.register')}}"> {{__('Sign Up')}} </a>
                                            </li>
                                        @endauth
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Topbar area Ends -->

    <div class="searchbar-area">
        <!-- Menu area Starts -->
        <nav class="navbar navbar-area nav-color-four index-07 nav-two navbar-expand-lg navbar-border">
            <div class="container container-three nav-container">
                <div class="responsive-mobile-menu">
                    <div class="logo-wrapper">
                        <a href="{{url('/')}}" class="logo">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bizcoxx_main_menu"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                    <ul class="navbar-nav">
                        {!! render_frontend_menu($primary_menu) !!}
                    </ul>
                </div>
                <div class="nav-right-content">
                    <ul>
                        <li>
                            <div class="info-bar-item">
                                <div class="track-icon-list style-02">
                                    <a href="javascript:void(0)" class="single-icon search-open">
                                        <span class="icon"> <i class="las la-search"></i> </span>
                                    </a>

                                    @include('themes.components.cart-shopping')


                                </div>

                                @if(!get_static_option('topbar_show_hide'))
                                    <div class="login-account">
                                        <a href="javascript:void(0)" class="accounts">
                                            <i class="las la-user"></i>
                                        </a>
                                        <ul class="account-list-item">
                                            @auth('web')
                                                <li class="list">
                                                    <a href="{{route('tenant.user.home')}}"> {{__('Dashboard')}} </a>
                                                </li>
                                                <li class="list">
                                                    <a href="{{route('tenant.user.logout')}}"> {{__('Log Out')}} </a>
                                                </li>
                                            @else
                                                <li class="list">
                                                    <a href="{{route('tenant.user.login')}}"> {{__('Sign In')}} </a>
                                                </li>
                                                <li class="list">
                                                    <a href="{{route('tenant.user.register')}}"> {{__('Sign Up')}} </a>
                                                </li>
                                            @endauth
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Menu area end -->

        <!-- Search Bar -->
        <div class="search-bar">
            <form class="menu-search-form" action="#">
                <div class="search-open-form">
                    <div class="search-close"><i class="las la-times"></i></div>
                    <input class="item-search" type="text" placeholder="{{__('Search Here....')}}"
                           id="search_form_input">
                    <button type="submit">{{__('Search Now')}}</button>
                </div>
                <div class="search-suggestions" id="search_suggestions_wrap">
                    <div class="search-suggestions-inner">
                        <h6 class="search-suggestions-title">{{__('Product Suggestions')}}</h6>
                        <ul class="product-suggestion-list mt-4">

                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>
