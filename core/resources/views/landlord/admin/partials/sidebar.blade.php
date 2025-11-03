<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav sticky-top bg-sidebar-color">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    @php
                        if (auth('admin')->user()->image != null)
                        {
                            $image_id = auth('admin')->user()->image;
                        } else {
                            $image_id = get_static_option('placeholder_image');
                        }
                    @endphp

                    @if($image_id != null)
                        {!! render_image_markup_by_attachment_id($image_id, 'Profile Image', 'full', true) !!}
                    @else
                        <img src="{{asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="Profile Image">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{auth('admin')->user()->name}}</span>
                    <span class="text-secondary text-small">{{auth('admin')->user()->username}}</span>
                </div>
                @if(auth('admin')->user()->email_verified === 1)
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                @endif
            </a>
        </li>

        <li class="nav-item my-2 search-container">
            <div class="search-wrapper">
                <input class="global-search-input form-control border-1 mb-2" id="menuSearch" type="text" placeholder="Search..">
            </div>
        </li>
    </ul>
    <ul class="nav">
        {!! \App\Facades\LandlordAdminMenu::render_sidebar_menus() !!}

        <div id="noResults" class="no-results d-none text-center text-muted p-3">
            <i class="mdi mdi-magnify"></i>
            <p>{{__('No menu items found')}}</p>
        </div>
    </ul>
</nav>
