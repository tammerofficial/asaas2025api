@php
    $page = $page ?? null;
    $location = $location ?? 'dynamic_page';
    $pageId = $page ? $page->id : null;
    $pageType = $location;
@endphp

<div class="pb-preview-content">
    <!-- Preview Toolbar -->
    <div class="pb-preview-toolbar">
        <div class="pb-preview-toolbar-left">
            <button type="button" class="pb-preview-btn active" data-device="desktop" title="{{__('Desktop')}}">
                <i class="mdi mdi-monitor"></i>
            </button>
            <button type="button" class="pb-preview-btn" data-device="tablet" title="{{__('Tablet')}}">
                <i class="mdi mdi-tablet"></i>
            </button>
            <button type="button" class="pb-preview-btn" data-device="mobile" title="{{__('Mobile')}}">
                <i class="mdi mdi-cellphone"></i>
            </button>
        </div>
        <div class="pb-preview-toolbar-right">
            <button type="button" class="pb-preview-btn" id="pb-preview-refresh" title="{{__('Refresh Preview')}}">
                <i class="mdi mdi-refresh"></i>
            </button>
            <button type="button" class="pb-preview-btn" id="pb-preview-zoom-out" title="{{__('Zoom Out')}}">
                <i class="mdi mdi-magnify-minus"></i>
            </button>
            <button type="button" class="pb-preview-btn" id="pb-preview-zoom-in" title="{{__('Zoom In')}}">
                <i class="mdi mdi-magnify-plus"></i>
            </button>
            <span class="pb-preview-zoom-level" id="pb-preview-zoom-level">100%</span>
        </div>
    </div>

    <!-- Preview Frame -->
    <div class="pb-preview-frame-wrapper">
        <div class="pb-preview-frame" id="pb-preview-frame">
            <div class="pb-preview-inner" id="pb-preview-inner">
                <!-- Preview Content will be loaded here via AJAX -->
                <div class="pb-preview-loading" id="pb-preview-loading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{__('Loading...')}}</span>
                    </div>
                    <p class="mt-3">{{__('Loading preview...')}}</p>
                </div>

                <!-- Preview Content -->
                <div class="pb-preview-content-area" id="pb-preview-content-area">
                    {{-- Header --}}
                    <div class="pb-preview-header" id="pb-preview-header">
                        @php
                            $headerContent = \App\Facades\ThemeDataFacade::renderPageBuilderHeader();
                            if(empty($headerContent)){
                                $headerContent = \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_by_location('header');
                            }
                            // Remove any @section/@endsection directives that might break parent view
                            $headerContent = preg_replace('/@section\s*\([^)]+\)/i', '', $headerContent);
                            $headerContent = preg_replace('/@endsection/i', '', $headerContent);
                        @endphp
                        {!! $headerContent !!}
                    </div>

                    {{-- Page Content --}}
                    <div class="pb-preview-page-content" id="pb-preview-page-content">
                        @php
                            if($page){
                                $pageContent = \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page($pageType, $pageId);
                            } else {
                                $pageContent = \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_by_location($location);
                            }
                            // Remove any @section/@endsection directives that might break parent view
                            $pageContent = preg_replace('/@section\s*\([^)]+\)/i', '', $pageContent);
                            $pageContent = preg_replace('/@endsection/i', '', $pageContent);
                        @endphp
                        {!! $pageContent !!}
                    </div>

                    {{-- Footer --}}
                    <div class="pb-preview-footer" id="pb-preview-footer">
                        @php
                            $footerContent = \App\Facades\ThemeDataFacade::renderPageBuilderFooter();
                            if(empty($footerContent)){
                                $footerContent = \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_by_location('footer');
                            }
                            // Remove any @section/@endsection directives that might break parent view
                            $footerContent = preg_replace('/@section\s*\([^)]+\)/i', '', $footerContent);
                            $footerContent = preg_replace('/@endsection/i', '', $footerContent);
                        @endphp
                        {!! $footerContent !!}
                    </div>
                </div>

                <!-- Empty State -->
                <div class="pb-preview-empty" id="pb-preview-empty" style="display: none;">
                    <div class="text-center p-5">
                        <i class="mdi mdi-puzzle-outline" style="font-size: 64px; opacity: 0.3;"></i>
                        <h4 class="mt-4">{{__('No widgets yet')}}</h4>
                        <p class="text-muted">{{__('Drag widgets from the sidebar to start building')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget Overlay (shown on hover/click) -->
    <div class="pb-widget-overlay" id="pb-widget-overlay" style="display: none;">
        <div class="pb-widget-overlay-content">
            <button type="button" class="pb-widget-overlay-btn" data-action="edit">
                <i class="mdi mdi-pencil"></i> {{__('Edit')}}
            </button>
            <button type="button" class="pb-widget-overlay-btn" data-action="duplicate">
                <i class="mdi mdi-content-copy"></i> {{__('Duplicate')}}
            </button>
            <button type="button" class="pb-widget-overlay-btn" data-action="delete">
                <i class="mdi mdi-delete"></i> {{__('Delete')}}
            </button>
        </div>
    </div>
</div>

