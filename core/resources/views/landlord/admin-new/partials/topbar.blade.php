<nav class="topbar-modern mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="topbar-logo">
            <a href="{{route('landlord.admin.home')}}" class="navbar-brand">
                @php
                    $logo_type = !empty(get_static_option('dark_mode_for_admin_panel')) ? 'site_white_logo' : 'site_logo';
                @endphp
                {!! render_image_markup_by_attachment_id(get_static_option($logo_type)) !!}
            </a>
        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center gap-3">
            <!-- Mobile Menu Toggle -->
            <button class="btn btn-link d-lg-none" @click="sidebarOpen = !sidebarOpen">
                <i class="mdi mdi-menu"></i>
            </button>

            <!-- Fullscreen -->
            <button class="btn btn-link d-none d-lg-block" id="fullscreen-button">
                <i class="mdi mdi-fullscreen"></i>
            </button>

            <!-- Messages -->
            @if($new_message ?? 0)
            <div class="dropdown">
                <a class="btn btn-link position-relative" data-bs-toggle="dropdown">
                    <i class="mdi mdi-email-outline"></i>
                    @if($new_message > 0)
                        <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">{{$new_message}}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <h6 class="dropdown-header">{{$new_message}} {{__('Messages')}}</h6>
                    <div class="dropdown-divider"></div>
                    @foreach($all_messages ?? [] as $message)
                        <a class="dropdown-item" href="{{route(route_prefix().'admin.contact.message.view', $message->id)}}">
                            {{optional($message->form)->title ?? __('New Message')}}
                        </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="{{route(route_prefix().'admin.contact.message.all')}}">
                        {{__('See All')}}
                    </a>
                </div>
            </div>
            @endif

            <!-- Blog Comments (if active) -->
            @if(isPluginActive('Blog') && isset($new_comments))
            <div class="dropdown">
                <a class="btn btn-link position-relative" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell"></i>
                    @php
                        $comments = $new_comments->where('status', 'unread')?->count() ?? 0;
                    @endphp
                    @if($comments > 0)
                        <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">{{$comments}}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <h6 class="dropdown-header">{{$comments}} {{__('Unread Comments')}}</h6>
                    <div class="dropdown-divider"></div>
                    @foreach($new_comments ?? [] as $comment)
                        <a class="dropdown-item" href="{{route(route_prefix().'admin.blog.comments.view', $comment->blog_id)}}">
                            {{__('New comment in')}} {{Str::words($comment->blog?->title)}}
                        </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="{{route(route_prefix().'admin.blog')}}">
                        {{__('See All')}}
                    </a>
                </div>
            </div>
            @endif

            <!-- Health Check -->
            @inject('healthHelper', 'App\Helpers\SiteHealthHelper')
            <a class="btn {{$healthHelper->getWarning() ? 'btn-danger' : 'btn-success'}}" href="{{route('landlord.admin.health')}}">
                <i class="mdi mdi-stethoscope"></i> {{__('Health')}}
            </a>

            <!-- Visit Site -->
            <a class="btn btn-outline-danger" href="{{route('landlord.homepage')}}" target="_blank">
                <i class="mdi mdi-upload"></i> {{__('Visit Site')}}
            </a>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <a class="btn btn-link d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="profile-img-small me-2">
                        @php
                            if (auth('admin')->user()->image != null) {
                                $image_id = auth('admin')->user()->image;
                            } else {
                                $image_id = get_static_option('placeholder_image');
                            }
                        @endphp
                        @if($image_id != null)
                            {!! render_image_markup_by_attachment_id($image_id,'','full',true) !!}
                        @else
                            <img src="{{asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        @endif
                    </div>
                    <span class="d-none d-md-inline">{{auth('admin')->user()->name}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{route('landlord.admin.tenant.activity.log')}}">
                        <i class="mdi mdi-cached me-2 text-success"></i> {{__('Activity Log')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('landlord.admin.edit.profile')}}">
                        <i class="mdi mdi-account-settings me-2 text-success"></i> {{__('Edit Profile')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('landlord.admin.change.password')}}">
                        <i class="mdi mdi-key me-2 text-success"></i> {{__('Change Password')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout me-2 text-primary"></i> {{__('Signout')}}
                    </a>
                    <form id="logout-form" action="{{ route('landlord.admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Fullscreen functionality
    document.getElementById('fullscreen-button')?.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    });
</script>

