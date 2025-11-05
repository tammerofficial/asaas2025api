@php
    $page = $page ?? null;
    $location = $location ?? 'dynamic_page';
    $type = $type ?? 'landlord';
@endphp

<div class="pagebuilder-elementor-wrapper" id="pb-elementor-wrapper">
    <!-- Top Bar - Minimal -->
    <div class="pb-top-bar">
        <div class="pb-top-left">
            <button type="button" class="pb-btn pb-btn-toggle-sidebar" id="pb-btn-toggle-sidebar" title="{{__('Widgets')}}">
                <i class="mdi mdi-menu"></i>
            </button>
            @if($page)
                <span class="pb-page-title">{{$page->title ?? __('Untitled')}}</span>
            @endif
        </div>
        <div class="pb-top-right">
            <button type="button" class="pb-btn pb-btn-preview" id="pb-btn-preview-toggle">
                <i class="mdi mdi-eye"></i> <span>{{__('Preview')}}</span>
            </button>
            <button type="button" class="pb-btn pb-btn-save" id="pb-btn-save">
                <i class="mdi mdi-content-save"></i> <span>{{__('Publish')}}</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-main-content" id="pb-main-content">
        <!-- Sidebar - Widgets (Left) -->
        <div class="pb-sidebar" id="pb-sidebar">
            @include('pagebuilder::components.elementor.sidebar', ['type' => $type])
        </div>

        <!-- Preview Area (Center - Full Width) -->
        <div class="pb-preview-area" id="pb-preview-area">
            @include('pagebuilder::components.elementor.preview', [
                'page' => $page,
                'location' => $location
            ])
        </div>

        <!-- Settings Panel (Right - Slide from right) -->
        <div class="pb-settings-panel hidden" id="pb-settings-panel">
            @include('pagebuilder::components.elementor.settings-panel', [
                'page' => $page,
                'location' => $location
            ])
        </div>
    </div>

    <!-- Bottom Bar - Status -->
    <div class="pb-bottom-bar" id="pb-bottom-bar">
        <div class="pb-bottom-left">
            <span class="pb-widget-count" id="pb-widget-count">0 {{__('Widgets')}}</span>
        </div>
        <div class="pb-bottom-right">
            <span class="pb-status" id="pb-status">{{__('Ready to edit')}}</span>
        </div>
    </div>
</div>

