<header class="header-style-01">
    <!-- Menu area Starts -->
    <nav class="navbar navbar-area navbar-expand-lg">
        <div class="container container-one nav-container">
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
                                <h2 class="site-title">{{filter_static_option_value('site_'.$user_select_lang_slug.'_title',$global_static_field_data)}}</h2>
                            @endif
                        </a>
                    @endif
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#multi_tenancy_menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="multi_tenancy_menu">
                <ul class="navbar-nav">
                    {!! render_frontend_menu($primary_menu) !!}
                </ul>
            </div>
            <div class="navbar-right-content show-nav-content">
                <div class="single-right-content">
                    <div class="search-suggestions-icon-wrapper">
                        <div class="search-click-icon">
                            <i class="las la-search"></i>
                        </div>
                        <div class="search-suggetions-show">
                            <div class="navbar-input searchbar-suggetions">
                                <form action="">
                                    <div class="search-open-form">
                                        <input autocomplete="off" class="form--control" id="search_form_input" type="text" placeholder="{{__('Search here....')}}">
                                        <button type="submit"> <i class="las la-search"></i> </button>
                                        <span class="suggetions-icon-close"> <i class="las la-times"></i> </span>
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
                    </div>
                    <div class="navbar-right-flex">
                        <div class="track-icon-list">
                            @include('themes.components.cart-shopping')
                        </div>
                        <div class="login-account">
                            <a class="accounts" href="javascript:void(0)"> <i class="las la-user"></i> </a>
                            <ul class="account-list-item">
                                @auth('web')
                                    <li class="list"> <a href="{{route('tenant.user.home')}}"> {{__('Dashboard')}} </a> </li>
                                    <li class="list"> <a href="{{route('tenant.user.logout')}}"> {{__('Log Out')}} </a> </li>
                                @else
                                    <li class="list"> <a href="{{route('tenant.user.login')}}"> {{__('Sign In')}} </a> </li>
                                    <li class="list"> <a href="{{route('tenant.user.register')}}"> {{__('Sign Up')}} </a> </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Menu area end -->
</header>
