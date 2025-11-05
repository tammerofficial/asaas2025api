@php
    $location = $location ?? \Illuminate\Support\Str::random(16);
    $widgetsContent = \Plugins\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page($location,$page->id);
    // Remove any @section/@endsection directives that might break parent view
    $widgetsContent = preg_replace('/@section\s*\([^)]+\)/i', '', $widgetsContent);
    $widgetsContent = preg_replace('/@endsection/i', '', $widgetsContent);
@endphp
<div class="page-builder-area-wrapper extra-title">
    <h4 class="main-title">{{__(ucfirst(str_replace('_',' ',$title ?? 'Drag Widgets Into Draggable Area')))}}</h4>
    <ul id="{{$location}}"
        class="sortable available-form-field main-fields sortable_widget_location">
        {!! $widgetsContent !!}
    </ul>
</div>
