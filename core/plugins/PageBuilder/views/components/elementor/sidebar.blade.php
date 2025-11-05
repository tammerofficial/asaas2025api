<div class="pb-sidebar-content">
    <!-- Search -->
    <div class="pb-sidebar-search">
        <div class="form-group">
            <input 
                type="text" 
                class="form-control" 
                id="pb-search-addon" 
                placeholder="{{__('Search Widgets...')}}" 
                autocomplete="off"
            >
            <i class="mdi mdi-magnify"></i>
        </div>
    </div>

    <!-- Widget Categories (Tabs) -->
    <div class="pb-sidebar-tabs">
        <ul class="nav nav-tabs" id="pb-widget-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pb-all-widgets" type="button">
                    <i class="mdi mdi-view-grid"></i> {{__('All')}}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pb-common-widgets" type="button">
                    <i class="mdi mdi-puzzle"></i> {{__('Common')}}
                </button>
            </li>
            @if($type === 'tenant')
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pb-tenant-widgets" type="button">
                        <i class="mdi mdi-store"></i> {{__('Tenant')}}
                    </button>
                </li>
            @endif
        </ul>
    </div>

    <!-- Widgets List -->
    <div class="pb-sidebar-widgets">
        <div class="tab-content" id="pb-widget-tab-content">
            <!-- All Widgets -->
            <div class="tab-pane fade show active" id="pb-all-widgets" role="tabpanel">
                <ul id="pb-sortable-widgets" class="pb-widget-list sortable_02">
                    @if(isset($type) && $type === 'tenant')
                        {!! \Plugins\PageBuilder\PageBuilderSetup::get_tenant_admin_panel_widgets() !!}
                    @else
                        {!! \Plugins\PageBuilder\PageBuilderSetup::get_admin_panel_widgets() !!}
                    @endif
                </ul>
            </div>

            <!-- Common Widgets -->
            <div class="tab-pane fade" id="pb-common-widgets" role="tabpanel">
                <ul class="pb-widget-list sortable_02" data-category="common">
                    {!! \Plugins\PageBuilder\PageBuilderSetup::get_admin_panel_widgets() !!}
                </ul>
            </div>

            @if($type === 'tenant')
                <!-- Tenant Widgets -->
                <div class="tab-pane fade" id="pb-tenant-widgets" role="tabpanel">
                    <ul class="pb-widget-list sortable_02" data-category="tenant">
                        {!! \Plugins\PageBuilder\PageBuilderSetup::get_tenant_admin_panel_widgets() !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Empty State -->
    <div class="pb-sidebar-empty" id="pb-sidebar-empty" style="display: none;">
        <div class="text-center p-4">
            <i class="mdi mdi-magnify" style="font-size: 48px; opacity: 0.3;"></i>
            <p class="mt-3 text-muted">{{__('No widgets found')}}</p>
        </div>
    </div>
</div>

