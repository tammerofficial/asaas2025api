@php
    $page = $page ?? null;
    $location = $location ?? 'dynamic_page';
@endphp

<div class="pb-settings-panel-content">
    <!-- Panel Header -->
    <div class="pb-settings-header">
        <h3 class="pb-settings-title" id="pb-settings-title">
            <i class="mdi mdi-cog"></i> {{__('Widget Settings')}}
        </h3>
        <button type="button" class="pb-settings-close" id="pb-settings-close">
            <i class="mdi mdi-close"></i>
        </button>
    </div>

    <!-- Empty State -->
    <div class="pb-settings-empty" id="pb-settings-empty">
        <div class="text-center py-5">
            <i class="mdi mdi-cursor-pointer" style="font-size: 64px; opacity: 0.3; color: #cbd5e0;"></i>
            <h5 class="mt-4" style="color: #4a5568; font-weight: 600;">{{__('Select a widget')}}</h5>
            <p class="text-muted" style="color: #a0aec0;">{{__('Click on a widget in the preview to edit it')}}</p>
        </div>
    </div>

    <!-- Loading State -->
    <div class="pb-settings-loading" id="pb-settings-loading" style="display: none;">
        <div class="text-center py-5">
            <i class="mdi mdi-loading mdi-spin" style="font-size: 48px; color: #667eea;"></i>
            <p class="mt-3" style="color: #4a5568; font-weight: 500;">{{__('Loading settings...')}}</p>
        </div>
    </div>

    <!-- Settings Body -->
    <div class="pb-settings-body" id="pb-settings-form-wrapper" style="display: none;">
        <form id="pb-settings-form" class="pb-settings-form">
            @csrf
            <input type="hidden" name="widget_id" id="pb-settings-widget-id">
            <input type="hidden" name="addon_location" value="{{$location}}">
            @if($page)
                <input type="hidden" name="addon_page_id" value="{{$page->id}}">
                <input type="hidden" name="addon_page_type" value="{{$location}}">
            @endif

            <!-- Form Content (dynamically loaded) -->
            <div class="pb-settings-form-content" id="pb-settings-form-content">
                <!-- Widget settings will be loaded here via AJAX -->
            </div>
        </form>
    </div>

    <!-- Form Footer -->
    <div class="pb-settings-footer" id="pb-settings-footer" style="display: none;">
        <button type="button" class="btn btn-danger" id="pb-btn-delete-widget">
            <i class="mdi mdi-delete"></i> {{__('Delete')}}
        </button>
        <button type="button" class="btn btn-secondary" id="pb-btn-cancel">
            {{__('Cancel')}}
        </button>
        <button type="button" class="btn btn-primary" id="pb-btn-update">
            <i class="mdi mdi-content-save"></i> {{__('Update')}}
        </button>
    </div>
</div>

