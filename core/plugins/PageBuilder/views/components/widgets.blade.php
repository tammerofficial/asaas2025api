@php
    // Helper function to clean section directives
    $cleanSectionDirectives = function($content) {
        $content = preg_replace('/@section\s*\([^)]+\)/i', '', $content);
        $content = preg_replace('/@endsection/i', '', $content);
        return $content;
    };
    
    if(isset($type) && $type === 'tenant'){
        $widgetsContent = \Plugins\PageBuilder\PageBuilderSetup::get_tenant_admin_panel_widgets();
    } else {
        $widgetsContent = \Plugins\PageBuilder\PageBuilderSetup::get_admin_panel_widgets();
    }
    // Remove any @section/@endsection directives that might break parent view
    $widgetsContent = $cleanSectionDirectives($widgetsContent);
@endphp
<div class="search-wrap">
    <div class="form-group">
        <input type="text" class="form-control" id="search_addon_field" placeholder="{{__('Search Addon')}}" name="s">
    </div>
</div>
<div class="all-addons-wrapper">
    <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
        {!! $widgetsContent !!}
    </ul>
</div>
