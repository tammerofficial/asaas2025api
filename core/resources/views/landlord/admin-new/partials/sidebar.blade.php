<aside class="dashboard-sidebar" :class="{ 'open': sidebarOpen }" @click.away="if (window.innerWidth < 992) sidebarOpen = false">
    <nav class="sidebar-modern" x-data="{ openMenus: [] }">
        <!-- Profile Section -->
        <div class="sidebar-profile mb-4 px-4">
            <div class="d-flex align-items-center py-3">
                <div class="profile-image me-3">
                    @php
                        if (auth('admin')->user()->image != null) {
                            $image_id = auth('admin')->user()->image;
                        } else {
                            $image_id = get_static_option('placeholder_image');
                        }
                    @endphp

                    @if($image_id != null)
                        {!! render_image_markup_by_attachment_id($image_id, 'Profile Image', 'full', true) !!}
                    @else
                        <img src="{{asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="Profile Image" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="profile-text">
                    <div class="font-weight-bold">{{auth('admin')->user()->name}}</div>
                    <div class="text-secondary text-small">{{auth('admin')->user()->username}}</div>
                </div>
                @if(auth('admin')->user()->email_verified === 1)
                    <i class="mdi mdi-bookmark-check text-success ms-auto"></i>
                @endif
            </div>
        </div>

        <!-- Search -->
        <div class="px-4 mb-3">
            <input class="form-control" id="menuSearch" type="text" placeholder="{{__('Search...')}}" oninput="searchMenu(this.value)">
        </div>

        <!-- Menu -->
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-item {{request()->routeIs('landlord.admin.home') ? 'active' : ''}}">
                <a href="{{route('landlord.admin.home')}}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>{{__('Dashboard')}}</span>
                </a>
            </li>

            <!-- Admin Role Manage -->
            @if(auth('admin')->user()->hasRole('Super Admin'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.role.manage')}}">
                    <i class="mdi mdi-account-settings"></i>
                    <span>{{__('Admin Role Manage')}}</span>
                </a>
            </li>
            @endif

            <!-- Users Manage -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.tenant')}}">
                    <i class="mdi mdi-account-multiple"></i>
                    <span>{{__('Users Manage')}}</span>
                </a>
            </li>

            <!-- Blogs -->
            @if(isPluginActive('Blog'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.blog')}}">
                    <i class="mdi mdi-book-open"></i>
                    <span>{{__('Blogs')}}</span>
                </a>
            </li>
            @endif

            <!-- Pages -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.pages')}}">
                    <i class="mdi mdi-file"></i>
                    <span>{{__('Pages')}}</span>
                </a>
            </li>

            <!-- Themes -->
            @if(isPluginActive('ThemeManage'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.theme')}}">
                    <i class="mdi mdi-shape-plus"></i>
                    <span>{{__('Themes')}}</span>
                </a>
            </li>
            @endif

            <!-- Price Plan -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.price.plan')}}">
                    <i class="mdi mdi-cash-multiple"></i>
                    <span>{{__('Price Plan')}}</span>
                </a>
            </li>

            <!-- Package Order Manage -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.package.order.manage.all')}}">
                    <i class="mdi mdi-package-variant"></i>
                    <span>{{__('Package Order Manage')}}</span>
                </a>
            </li>

            <!-- Wallet Manage -->
            @if(isPluginActive('Wallet'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.wallet.manage')}}">
                    <i class="mdi mdi-wallet"></i>
                    <span>{{__('Wallet Manage')}}</span>
                </a>
            </li>
            @endif

            <!-- Custom Domain -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.custom.domain.requests.all')}}">
                    <i class="mdi mdi-domain"></i>
                    <span>{{__('Custom Domain')}}</span>
                </a>
            </li>

            <!-- Support Tickets -->
            @if(isPluginActive('SupportTicket'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.support.ticket.all')}}">
                    <i class="mdi mdi-ticket"></i>
                    <span>{{__('Support Tickets')}}</span>
                </a>
            </li>
            @endif

            <!-- Form Builder -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.form.builder.all')}}">
                    <i class="mdi mdi-form-select"></i>
                    <span>{{__('Form Builder')}}</span>
                </a>
            </li>

            <!-- Appearance Settings -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.general.basic.settings')}}">
                    <i class="mdi mdi-palette"></i>
                    <span>{{__('Appearance Settings')}}</span>
                </a>
            </li>

            <!-- Domain Reseller -->
            @if(isPluginActive('DomainReseller'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.domain.reseller')}}">
                    <i class="mdi mdi-domain"></i>
                    <span>{{__('Domain Reseller')}}</span>
                </a>
            </li>
            @endif

            <!-- Plugins Manage -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.plugins')}}">
                    <i class="mdi mdi-puzzle"></i>
                    <span>{{__('Plugins Manage')}}</span>
                </a>
            </li>

            <!-- Site Analytics -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.analytics')}}">
                    <i class="mdi mdi-chart-line"></i>
                    <span>{{__('Site Analytics')}}</span>
                </a>
            </li>

            <!-- Webhook Manage -->
            @if(isPluginActive('Webhook'))
            <li class="menu-item">
                <a href="{{route('landlord.admin.webhook.manage')}}">
                    <i class="mdi mdi-webhook"></i>
                    <span>{{__('Webhook Manage')}}</span>
                </a>
            </li>
            @endif

            <!-- General Settings -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.general.basic.settings')}}">
                    <i class="mdi mdi-cog"></i>
                    <span>{{__('General Settings')}}</span>
                </a>
            </li>

            <!-- Payment Settings -->
            <li class="menu-item">
                <a href="{{route('landlord.admin.payment.settings')}}">
                    <i class="mdi mdi-credit-card"></i>
                    <span>{{__('Payment Settings')}}</span>
                </a>
            </li>
        </ul>

        <!-- No Results -->
        <div id="noResults" class="d-none text-center text-muted p-3">
            <i class="mdi mdi-magnify"></i>
            <p>{{__('No menu items found')}}</p>
        </div>
    </nav>
</aside>

<script>
    function searchMenu(term) {
        const menuItems = document.querySelectorAll('.menu-item');
        const noResults = document.getElementById('noResults');
        let hasMatch = false;
        
        term = term.toLowerCase().trim();
        
        if (!term) {
            menuItems.forEach(item => item.style.display = '');
            noResults.classList.add('d-none');
            return;
        }
        
        menuItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(term)) {
                item.style.display = '';
                hasMatch = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (!hasMatch) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
</script>

