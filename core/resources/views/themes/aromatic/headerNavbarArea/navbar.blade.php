<header class="header-style-01">
    <div class="searchbar-area">
        <!-- Menu area Starts -->
        <nav class="navbar navbar-area index-05 nav-absolute nav-two navbar-expand-lg navbar-border">
            <div class="container container-three nav-container">
                <div class="responsive-mobile-menu">
                    <div class="logo-wrapper">
                        @if(\App\Facades\GlobalLanguage::user_lang_dir() == 'rtl')
                            <a href="{{url('/')}}" class="logo">
                                {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                            </a>
                        @else
                            <a href="{{url('/')}}" class="logo">
                                @if(!empty(get_static_option('site_white_logo')))
                                    {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                                @else
                                    <h2 class="site-title">{{filter_static_option_value('site_title', $global_static_field_data)}}</h2>
                                @endif
                            </a>
                        @endif
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
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Menu area end -->

        <!-- SearcBar -->
        <div class="search-bar">
            <form class="menu-search-form" action="#">
                <div class="search-open-form">
                    <div class="search-close"><i class="las la-times"></i></div>
                    <input class="item-search" type="text" placeholder="{{__('Search Here....')}}" id="search_form_input">
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
