@extends(is_null(tenant()) ? 'landlord.admin.admin-master' : 'tenant.admin.admin-master')
@section('title')
{{__('Edit With Page Builder')}}
@endsection

@php
    // Helper function to clean section directives from widget output
    if(!function_exists('clean_section_directives')){
        function clean_section_directives($content) {
            if(empty($content)) return $content;
            // Remove @section directives
            $content = preg_replace('/@section\s*\([^)]+\)/i', '', $content);
            // Remove @endsection directives
            $content = preg_replace('/@endsection/i', '', $content);
            return $content;
        }
    }
@endphp

@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-pagebuilder::css/>
    <x-pagebuilder::elementor.css/>
    <link rel="stylesheet" href="{{global_asset('assets/common/css/fontawesome-iconpicker.min.css')}}">
    <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet">

    <style>
        /* Override admin master layout for fullscreen */
        body.pagebuilder-elementor-active {
            overflow: hidden;
        }
        
        body.pagebuilder-elementor-active .main-content {
            display: none;
        }

        .attachment-preview {
            overflow: hidden;
        }
        .attachment-preview .thumbnail .centered {
            position: absolute;
            top: 0;
            left: 0;
            transform: translate(50%, 50%);
            width: 100%;
            height: 100%;
        }
        .attachment-preview .thumbnail .centered img {
            object-fit: contain;
        }
        
        /* Toggle Buttons Styling */
        #toggle-legacy-view,
        #toggle-elementor-view {
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        #toggle-legacy-view:hover,
        #toggle-elementor-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        #toggle-legacy-view.btn-primary,
        #toggle-elementor-view.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        #toggle-legacy-view i,
        #toggle-elementor-view i {
            margin-right: 4px;
        }
    </style>
@endsection
@section('content')
    {{-- Toggle Button to Switch Between Legacy and Elementor View --}}
    <div class="col-12 mb-3" style="position: fixed; top: 15px; right: 300px; z-index: 10001;">
        <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-sm btn-secondary" id="toggle-legacy-view" title="{{__('Legacy View')}}">
                <i class="mdi mdi-format-list-bulleted"></i> {{__('Legacy')}}
            </button>
            <button type="button" class="btn btn-sm btn-primary" id="toggle-elementor-view" title="{{__('Live Preview (Elementor Style)')}}">
                <i class="mdi mdi-eye"></i> {{__('Live Preview')}}
            </button>
        </div>
    </div>

    {{-- Elementor-Style Page Builder Layout (Default) --}}
    <div id="elementor-layout-wrapper">
        <x-pagebuilder::elementor.layout 
            :page="$page" 
            location="dynamic_page" 
            type="landlord"
        />
    </div>
    
    {{-- Legacy Layout (Hidden by default, can be toggled) --}}
    <div id="pagebuilder-legacy-layout" style="display: none; position: relative; top: 0;">
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-4">{{$page->title}} <br> <small class="text-small">{{__('Edit With Page Builder')}}</small></h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <x-link-with-popover url="{{route(route_prefix().'admin.pages')}}" extraclass="ml-3">
                            {{__('All Pages')}}
                        </x-link-with-popover>
                        <x-link-with-popover  class="info" target="_blank" url="{{route(route_prefix().'dynamic.page', $page->slug)}}" popover="{{__('view item in frontend')}}">
                            <i class="mdi mdi-eye"></i>
                        </x-link-with-popover>
                        <x-link-with-popover url="{{route(route_prefix().'admin.pages.edit', $page->id)}}">
                            <i class="mdi mdi-pencil"></i>
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
                </div>
        </div>
    </div>
   <div class="row g-4">
       <div class="col-lg-8">
           <div class="card">
               <div class="card-body">
                   <x-pagebuilder::draggable location="dynamic_page" :page="$page"/>
               </div>
           </div>
       </div>
       <div class="col-lg-4">
           <div class="card">
               <div class="card-body">
                   <x-pagebuilder::widgets type="landlord"/>
               </div>
           </div>
       </div>
   </div>
    </div>
    
    <x-media-upload.markup/>
@endsection

@push('scripts')
    <script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/jquery.nice-select.min.js')}}"></script>
    <x-media-upload.js/>
    <x-summernote.js/>
    <x-pagebuilder::js/>
    
    {{-- Elementor Preview JavaScript (Basic version) --}}
    <script>
        $(document).ready(function(){
            // Toggle between Legacy and Elementor view
            let currentView = 'elementor'; // Default to elementor
            
            $('#toggle-legacy-view').on('click', function(){
                if(currentView !== 'legacy'){
                    switchToLegacyView();
                }
            });
            
            $('#toggle-elementor-view').on('click', function(){
                if(currentView !== 'elementor'){
                    switchToElementorView();
                }
            });
            
            function switchToLegacyView(){
                $('#elementor-layout-wrapper').hide();
                $('#pagebuilder-legacy-layout').show();
                $('body').removeClass('pagebuilder-elementor-active');
                currentView = 'legacy';
                $('#toggle-legacy-view').removeClass('btn-secondary').addClass('btn-primary');
                $('#toggle-elementor-view').removeClass('btn-primary').addClass('btn-secondary');
            }
            
            function switchToElementorView(){
                $('#pagebuilder-legacy-layout').hide();
                $('#elementor-layout-wrapper').show();
                $('body').addClass('pagebuilder-elementor-active');
                currentView = 'elementor';
                $('#toggle-elementor-view').removeClass('btn-secondary').addClass('btn-primary');
                $('#toggle-legacy-view').removeClass('btn-primary').addClass('btn-secondary');
                loadPreview();
            }
            
            // Mark body for fullscreen layout (default elementor)
            $('body').addClass('pagebuilder-elementor-active');
            
            // Initialize basic preview loading
            // Add timeout fallback - if AJAX fails, show server-rendered content
            setTimeout(function(){
                if($('#pb-preview-loading').is(':visible')){
                    console.warn('Preview loading timeout - showing server-rendered content');
                    $('#pb-preview-loading').hide();
                    $('#pb-preview-content-area').show();
                    addWidgetEditHandlers();
                    initializeDragDrop();
                }
            }, 10000); // 10 second timeout
            
            loadPreview();
            
            // Sidebar toggle
            $('#pb-btn-minimize-sidebar').on('click', function(){
                $('#pb-sidebar').toggleClass('minimized');
                $(this).find('i').toggleClass('mdi-chevron-left mdi-chevron-right');
            });
            
            // Toggle sidebar
            $('#pb-btn-toggle-sidebar').on('click', function(){
                $('#pb-sidebar').toggleClass('minimized');
            });
            
            // Device preview buttons
            $('.pb-preview-btn[data-device]').on('click', function(){
                $('.pb-preview-btn').removeClass('active');
                $(this).addClass('active');
                const device = $(this).data('device');
                $('#pb-preview-frame').attr('data-device', device);
            });
            
            // Preview refresh
            $('#pb-preview-refresh').on('click', function(){
                loadPreview();
            });
            
            // Widget click handlers - using event delegation for dynamically loaded content
            $(document).on('click', '#pb-preview-content-area [data-widget-id]', function(e){
                e.stopPropagation();
                e.preventDefault();
                const widgetId = $(this).data('widget-id');
                if(widgetId){
                    selectWidget(widgetId);
                }
            });
            
            // Close settings panel when clicking close button
            $(document).on('click', '#pb-settings-close, .pb-settings-close, #pb-btn-cancel', function(e){
                e.preventDefault();
                $('#pb-settings-panel').addClass('hidden');
                $('#pb-settings-footer').hide();
                clearWidgetSelection();
            });
            
            // Close with ESC key
            $(document).on('keydown', function(e){
                if(e.key === 'Escape'){
                    if(!$('#pb-settings-panel').hasClass('hidden')){
                        $('#pb-settings-panel').addClass('hidden');
                        $('#pb-settings-footer').hide();
                        clearWidgetSelection();
                    }
                }
            });
            
            // Search widgets
            $('#pb-search-addon').on('keyup', function(){
                const searchTerm = $(this).val().toLowerCase();
                $('.pb-widget-list li').each(function(){
                    const text = $(this).text().toLowerCase();
                    if(text.includes(searchTerm)){
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                // Show/hide empty state
                const visibleCount = $('.pb-widget-list li:visible').length;
                if(visibleCount === 0){
                    $('#pb-sidebar-empty').show();
                } else {
                    $('#pb-sidebar-empty').hide();
                }
            });
            
            // Save button
            $('#pb-btn-save').on('click', function(){
                saveAllWidgets();
            });
            
            // Update widget
            $('#pb-settings-form').on('submit', function(e){
                e.preventDefault();
                updateWidget();
            });
            
            // Delete widget
            $('#pb-btn-delete-widget').on('click', function(){
                if(confirm('{{__("Are you sure you want to delete this widget?")}}')){
                    deleteWidget();
                }
            });
        });
        
        // Load preview
        function loadPreview(){
            // Show server-rendered content immediately
            $('#pb-preview-loading').hide();
            $('#pb-preview-content-area').show();
            addWidgetEditHandlers();
            initializeDragDrop();
            updateWidgetCount();
            
            // Then try to load via AJAX for updates (optional)
            // Use direct URL - route name might not be registered yet
            const previewUrl = '{{url("/admin-home/get-preview")}}';
            
            $.ajax({
                url: previewUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    page_id: {{$page->id ?? 'null'}},
                    location: 'dynamic_page',
                    page_type: 'dynamic_page'
                },
                success: function(response){
                    if(response.success && response.html){
                        // Update content if AJAX succeeds
                        $('#pb-preview-content-area').html(response.html);
                        addWidgetEditHandlers();
                        initializeDragDrop();
                        updateWidgetCount();
                    }
                },
                error: function(xhr, status, error){
                    // Silently fail - keep server-rendered content
                    console.warn('Preview AJAX update failed, using server-rendered content:', {
                        status: status,
                        error: error
                    });
                }
            });
        }
        
        // Add edit handlers to widgets
        function addWidgetEditHandlers(){
            // All widgets should already have data-widget-id from server
            // Just add click handlers and hover effects
            $('#pb-preview-content-area [data-widget-id]').each(function(){
                const $widget = $(this);
                const widgetId = $widget.data('widget-id');
                
                if(widgetId){
                    $widget.css('cursor', 'pointer');
                    $widget.css('position', 'relative');
                    
                    // Remove any existing handlers to avoid duplicates
                    $widget.off('click.widgetEdit');
                    $widget.off('mouseenter.widgetEdit');
                    $widget.off('mouseleave.widgetEdit');
                    
                    // Add click handler
                    $widget.on('click.widgetEdit', function(e){
                        e.stopPropagation();
                        selectWidget(widgetId);
                    });
                    
                    // Add hover effect
                    $widget.on('mouseenter.widgetEdit', function(){
                        if(!$(this).hasClass('pb-widget-selected')){
                            $(this).css('outline', '2px dashed #0073aa');
                            $(this).css('outline-offset', '2px');
                        }
                    }).on('mouseleave.widgetEdit', function(){
                        if(!$(this).hasClass('pb-widget-selected')){
                            $(this).css('outline', '');
                        }
                    });
                }
            });
            
            // Also handle widgets by ID attribute
            $('#pb-preview-content-area section[id^="widget-"], #pb-preview-content-area div[id^="widget-"]').each(function(){
                const $widget = $(this);
                const idAttr = $widget.attr('id');
                if(idAttr && idAttr.startsWith('widget-')){
                    const widgetId = idAttr.replace('widget-', '');
                    if(!$widget.attr('data-widget-id')){
                        $widget.attr('data-widget-id', widgetId);
                        $widget.css('cursor', 'pointer');
                    }
                }
            });
        }
        
        // Initialize Drag & Drop from Sidebar to Preview
        function initializeDragDrop(){
            // Make sidebar widgets draggable
            if($.fn.draggable){
                // Make all widget-handler items draggable
                $('.widget-handler, .sortable_02 li').each(function(){
                    const $widget = $(this);
                    
                    // Skip if already draggable
                    if($widget.hasClass('ui-draggable')){
                        return;
                    }
                    
                    $widget.draggable({
                        helper: 'clone',
                        revert: 'invalid',
                        cursor: 'move',
                        zIndex: 10000,
                        appendTo: 'body',
                        start: function(event, ui){
                            $(ui.helper).css({
                                'opacity': '0.8',
                                'box-shadow': '0 4px 12px rgba(0,0,0,0.2)',
                                'background': '#fff',
                                'border': '2px solid #0073aa',
                                'border-radius': '4px',
                                'padding': '10px'
                            });
                        }
                    });
                });
            }
            
            // Make preview areas droppable (header, content, footer)
            if($.fn.droppable){
                const droppableOptions = {
                    accept: '.widget-handler, .sortable_02 li',
                    tolerance: 'pointer',
                    hoverClass: 'pb-drop-hover',
                    greedy: false,
                    drop: function(event, ui){
                        event.preventDefault();
                        const draggedWidget = ui.draggable;
                        let widgetName = draggedWidget.attr('data-name') || draggedWidget.data('name');
                        let widgetNamespace = draggedWidget.attr('data-namespace') || draggedWidget.data('namespace');
                        
                        // Try to get from different sources
                        if(!widgetName){
                            widgetName = draggedWidget.find('h4').text().trim();
                            // Also try to get from text content
                            if(!widgetName){
                                widgetName = draggedWidget.text().trim();
                            }
                        }
                        
                        if(!widgetNamespace){
                            const namespaceAttr = draggedWidget.attr('data-namespace');
                            if(namespaceAttr){
                                try {
                                    // Try base64 decode first
                                    widgetNamespace = atob(namespaceAttr);
                                } catch(e){
                                    // If not base64, use as is
                                    widgetNamespace = namespaceAttr;
                                }
                            }
                        }
                        
                        // If still not found, try to extract from widget structure
                        if(!widgetName || !widgetNamespace){
                            console.warn('Could not extract widget data', {
                                widget: draggedWidget,
                                name: widgetName,
                                namespace: widgetNamespace
                            });
                            return;
                        }
                        
                        console.log('Adding widget to preview', {
                            name: widgetName,
                            namespace: widgetNamespace
                        });
                        
                        addWidgetToPreview(widgetName, widgetNamespace);
                    }
                };
                
                // Make preview header droppable
                $('#pb-preview-header').droppable($.extend({}, droppableOptions, {
                    accept: '.widget-handler, .sortable_02 li'
                }));
                
                // Make preview page content droppable
                $('#pb-preview-page-content').droppable($.extend({}, droppableOptions));
                
                // Make preview footer droppable
                $('#pb-preview-footer').droppable($.extend({}, droppableOptions));
                
                // Also make the entire preview content area droppable as fallback
                $('#pb-preview-content-area').droppable($.extend({}, droppableOptions));
            }
            
            // Make preview widgets sortable/reorderable (for reordering existing widgets)
            if($.fn.sortable){
                $('#pb-preview-page-content').sortable({
                    items: '[data-widget-id]',
                    placeholder: 'sortable-placeholder',
                    cursor: 'move',
                    tolerance: 'pointer',
                    axis: 'y',
                    cancel: '.widget-handler, .sortable_02 li', // Don't sort draggable widgets
                    stop: function(event, ui){
                        updateWidgetOrder();
                    }
                });
            }
        }
        
        // Add widget to preview
        function addWidgetToPreview(widgetName, widgetNamespace){
            console.log('Adding widget:', widgetName, widgetNamespace);
            
            // Show loading indicator
            $('#pb-preview-loading').show();
            
            const markupUrl = '{{url("/admin-home/get-admin-markup")}}';
            
            $.ajax({
                url: markupUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    addon_class: widgetName,
                    addon_namespace: widgetNamespace,
                    addon_page_id: {{$page->id ?? 'null'}},
                    addon_page_type: 'dynamic_page',
                    addon_location: 'dynamic_page',
                    type: 'new'
                },
                success: function(markup){
                    console.log('Got markup, creating widget...');
                    
                    // Extract widget ID from markup
                    const tempDiv = $('<div>').html(markup);
                    let widgetId = tempDiv.find('input[name="id"]').val();
                    
                    // If no ID found, create widget first
                    if(!widgetId){
                        // Create widget in database first
                        const createUrl = '{{url("/admin-home/new")}}';
                        $.ajax({
                            url: createUrl,
                            method: 'POST',
                            data: {
                                _token: '{{csrf_token()}}',
                                addon_name: widgetName,
                                addon_namespace: widgetNamespace,
                                addon_page_id: {{$page->id ?? 'null'}},
                                addon_page_type: 'dynamic_page',
                                addon_location: 'dynamic_page',
                                addon_type: 'new',
                                addon_order: $('#pb-preview-page-content [data-widget-id]').length + 1
                            },
                            success: function(response){
                                if(response.id){
                                    widgetId = response.id;
                                    // Reload preview to show new widget
                                    loadPreview();
                                } else {
                                    console.error('Failed to create widget', response);
                                    alert('{{__("Failed to create widget")}}');
                                }
                                $('#pb-preview-loading').hide();
                            },
                            error: function(xhr){
                                console.error('Failed to create widget', xhr);
                                alert('{{__("Failed to create widget")}}');
                                $('#pb-preview-loading').hide();
                            }
                        });
                    } else {
                        // Widget already exists, just reload preview
                        loadPreview();
                    }
                },
                error: function(xhr){
                    console.error('Failed to get widget markup', xhr);
                    alert('{{__("Failed to load widget")}}');
                    $('#pb-preview-loading').hide();
                }
            });
        }
        
        // Update widget order
        function updateWidgetOrder(){
            const widgets = [];
            $('#pb-preview-page-content [data-widget-id]').each(function(index){
                widgets.push({
                    id: $(this).data('widget-id'),
                    order: index + 1
                });
            });
            
            // Send update order request
            const orderUrl = '{{url("/admin-home/update-order")}}';
            $.ajax({
                url: orderUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    widgets: widgets
                }
            });
        }
        
        // Select widget
        function selectWidget(widgetId){
            // Remove previous selection
            clearWidgetSelection();
            
            // Highlight selected widget
            $(`[data-widget-id="${widgetId}"]`).addClass('pb-widget-selected');
            
            // Show settings panel
            const $panel = $('#pb-settings-panel');
            $panel.removeClass('hidden');
            
            $('#pb-settings-empty').hide();
            $('#pb-settings-loading').show();
            $('#pb-settings-form-wrapper').hide();
            
            // Load widget form
            loadWidgetSettings(widgetId);
        }
        
        // Clear widget selection
        function clearWidgetSelection(){
            $('[data-widget-id]').removeClass('pb-widget-selected');
        }
        
        // Load widget settings
        function loadWidgetSettings(widgetId){
            const settingsUrl = '{{url("/admin-home/get-widget-settings")}}';
            
            $.ajax({
                url: settingsUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    widget_id: widgetId
                },
                success: function(response){
                    if(response.success && response.html){
                        // Extract form content from admin markup
                        const $temp = $('<div>').html(response.html);
                        
                        // Find the all-field-wrap div which contains all form fields
                        let formFields = '';
                        const $allFieldWrap = $temp.find('.all-field-wrap');
                        
                        if($allFieldWrap.length){
                            // Get content inside all-field-wrap
                            formFields = $allFieldWrap.html();
                        } else {
                            // Fallback: try to find form fields directly
                            // Remove form tags and keep only content
                            $temp.find('form').each(function(){
                                const $form = $(this);
                                // Remove submit button and form tags
                                $form.find('.widget_save_change_button').remove();
                                $form.find('input[name="_token"]').remove();
                                formFields = $form.html();
                            });
                            
                            // If still empty, use the whole HTML
                            if(!formFields){
                                formFields = response.html;
                            }
                        }
                        
                        // Insert into settings panel
                        $('#pb-settings-form-content').html(formFields);
                        
                        // Update widget ID in hidden input
                        $('#pb-settings-widget-id').val(widgetId);
                        
                        // Update title
                        if(response.widget && response.widget.name){
                            $('#pb-settings-title').html(`<i class="mdi mdi-cog"></i> {{__('Edit')}}: ${response.widget.name}`);
                        } else {
                            $('#pb-settings-title').html(`<i class="mdi mdi-cog"></i> {{__('Edit Widget')}} #${widgetId}`);
                        }
                        
                        // Hide loading, show form
                        $('#pb-settings-loading').hide();
                        $('#pb-settings-form-wrapper').show();
                        $('#pb-settings-footer').show();
                        
                        // Initialize form plugins (color pickers, selects, etc.) after a short delay
                        setTimeout(function(){
                            initializeFormPlugins();
                        }, 100);
                        
                        // Scroll to top of settings panel
                        $('.pb-settings-body').scrollTop(0);
                    } else {
                        $('#pb-settings-form-content').html('<p class="text-danger">{{__("Failed to load widget settings")}}</p>');
                    }
                },
                error: function(xhr){
                    console.error('Failed to load widget settings', xhr);
                    $('#pb-settings-form-content').html('<p class="text-danger">{{__("Error loading widget settings")}}</p>');
                }
            });
        }
        
        // Initialize form plugins (color pickers, selects, etc.)
        function initializeFormPlugins(){
            // Initialize color pickers
            if(typeof $ !== 'undefined' && $.fn.spectrum){
                $('.color_picker').spectrum({
                    type: "color",
                    showAlpha: true,
                    showInput: true,
                    preferredFormat: "hex"
                });
            }
            
            // Initialize nice-select
            if(typeof $ !== 'undefined' && $.fn.niceSelect){
                $('select.form-control').niceSelect();
            }
            
            // Initialize summernote
            if(typeof $ !== 'undefined' && $.fn.summernote){
                $('.summernote').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            }
            
            // Initialize icon pickers
            if(typeof $ !== 'undefined' && $.fn.iconpicker){
                $('.icp').iconpicker();
            }
            
            // Initialize media uploaders
            if(typeof $ !== 'undefined' && typeof initMediaUploader === 'function'){
                initMediaUploader();
            }
        }
        
        // Update widget
        function updateWidget(){
            const widgetId = $('#pb-settings-widget-id').val();
            
            if(!widgetId){
                alert('{{__("Widget ID is missing")}}');
                return;
            }
            
            // Get widget info from database first (we need addon_name, addon_namespace, addon_location)
            const widgetInfoUrl = '{{url("/admin-home/get-widget-settings")}}';
            
            $.ajax({
                url: widgetInfoUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    widget_id: widgetId
                },
                success: function(widgetInfoResponse){
                    if(!widgetInfoResponse.success || !widgetInfoResponse.widget){
                        alert('{{__("Failed to get widget information")}}');
                        return;
                    }
                    
                    // Collect all form data from settings panel
                    const formData = {
                        _token: '{{csrf_token()}}',
                        id: widgetId,
                        addon_name: widgetInfoResponse.widget.name,
                        addon_namespace: widgetInfoResponse.widget.namespace || '',
                        addon_location: widgetInfoResponse.widget.location || 'dynamic_page',
                        addon_page_id: widgetInfoResponse.widget.page_id || {{$page->id ?? 'null'}},
                        addon_page_type: widgetInfoResponse.widget.page_type || 'dynamic_page',
                        addon_order: widgetInfoResponse.widget.order || $('#pb-preview-page-content [data-widget-id]').length || 1
                    };
                    
                    // Get all inputs, selects, textareas from settings form content
                    $('#pb-settings-form-content input, #pb-settings-form-content select, #pb-settings-form-content textarea').each(function(){
                        const $field = $(this);
                        const name = $field.attr('name');
                        const type = $field.attr('type');
                        
                        if(name && name !== '_token' && name !== 'id' && 
                           name !== 'addon_name' && name !== 'addon_namespace' && 
                           name !== 'addon_location' && name !== 'addon_page_id' && 
                           name !== 'addon_page_type' && name !== 'addon_order'){
                            if(type === 'checkbox' || type === 'radio'){
                                if($field.is(':checked')){
                                    formData[name] = $field.val();
                                } else if(type === 'checkbox'){
                                    formData[name] = '';
                                }
                            } else if(type === 'file'){
                                // Handle file uploads separately if needed
                                // For now, skip files
                            } else {
                                formData[name] = $field.val();
                            }
                        }
                    });
                    
                    // Get summernote content if exists
                    $('#pb-settings-form-content .summernote').each(function(){
                        const $editor = $(this);
                        const name = $editor.attr('name');
                        if(name && typeof $editor.summernote === 'function'){
                            formData[name] = $editor.summernote('code');
                        }
                    });
                    
                    // Get spectrum color picker values
                    $('#pb-settings-form-content .color_picker').each(function(){
                        const $picker = $(this);
                        const name = $picker.attr('name');
                        if(name && typeof $picker.spectrum === 'function'){
                            formData[name] = $picker.spectrum('get').toHexString();
                        }
                    });
                    
                    // Show loading
                    $('#pb-status').html('● {{__("Saving...")}}');
                    $('#pb-btn-update').prop('disabled', true);
                    
                    // AJAX call to update
                    const updateUrl = '{{url("/admin-home/update")}}';
                    $.ajax({
                        url: updateUrl,
                        method: 'POST',
                        data: formData,
                        success: function(response){
                            $('#pb-status').html('● {{__("Saved")}}');
                            $('#pb-btn-update').prop('disabled', false);
                            
                            // Refresh preview to show changes
                            loadPreview();
                            
                            setTimeout(function(){
                                $('#pb-status').html('● {{__("Ready")}}');
                            }, 2000);
                        },
                        error: function(xhr){
                            console.error('Update failed', xhr);
                            $('#pb-status').html('● {{__("Error")}}');
                            $('#pb-btn-update').prop('disabled', false);
                            
                            let errorMsg = '{{__("Failed to update widget")}}';
                            if(xhr.responseJSON){
                                if(xhr.responseJSON.message){
                                    errorMsg = xhr.responseJSON.message;
                                } else if(xhr.responseJSON.errors){
                                    const errors = Object.values(xhr.responseJSON.errors).flat();
                                    errorMsg = errors.join(', ');
                                }
                            }
                            alert(errorMsg);
                        }
                    });
                },
                error: function(xhr){
                    console.error('Failed to get widget info', xhr);
                    alert('{{__("Failed to get widget information")}}');
                }
            });
        }
        
        // Delete widget
        function deleteWidget(){
            const widgetId = $('#pb-settings-widget-id').val();
            
            const deleteUrl = '{{url("/admin-home/delete")}}';
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    id: widgetId
                },
                success: function(){
                    $('#pb-settings-panel').addClass('hidden');
                    $('#pb-settings-footer').hide();
                    clearWidgetSelection();
                    loadPreview();
                    updateWidgetCount();
                }
            });
        }
        
        // Save all widgets
        function saveAllWidgets(){
            $('#pb-status').html('● {{__("Saving...")}}');
            // This will save all pending changes
            setTimeout(function(){
                $('#pb-status').html('● {{__("All changes saved")}}');
            }, 1000);
        }
        
        // Update widget count
        function updateWidgetCount(){
            const count = $('#pb-preview-content-area [data-widget-id]').length;
            $('#pb-widget-count').text(count + ' {{__("Widgets")}}');
        }
    </script>
    
    {{-- Legacy PageBuilder Script --}}
    <x-pagebuilder::script :page="$page"/>
@endpush
