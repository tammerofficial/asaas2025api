<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav sticky-top bg-sidebar-color">
        <li class="nav-item nav-profile">
            <a href="{{tenant_url_with_protocol(tenant()?->domain?->domain)}}" class="nav-link" target="_blank">
                <div class="nav-profile-image">
                    @if(auth('admin')->user()->image != null)
                        {!! render_image_markup_by_attachment_id(optional(auth('admin')->user())->image,'','full',true) !!}
                    @else
                        <img src="{{global_asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="Profile Image">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{optional(auth('admin')->user())->name}}</span>
                    <span class="text-secondary text-small">{{optional(auth('admin')->user())->username}}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item my-2 search-container">
            <input class="global-search-input form-control border-1 mb-2" id="menuSearch" type="text" placeholder="Search..">
        </li>
    </ul>
    <ul class="nav">
        {!! \App\Facades\LandlordAdminMenu::render_tenant_sidebar_menus() !!}
        <div id="noResults" class="no-results d-none text-center text-muted p-3">
            <i class="mdi mdi-magnify"></i>
            <p>{{__('No menu items found')}}</p>
        </div>
    </ul>
</nav>
