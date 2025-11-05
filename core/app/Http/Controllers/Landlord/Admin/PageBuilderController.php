<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Jobs\DeletePageBuilderJob;
use App\Jobs\SavePageBuilderJob;
use App\Models\Page;
use App\Models\PageBuilder;
use App\Providers\TenantCacheServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Plugins\PageBuilder\PageBuilderSetup;

class PageBuilderController extends Controller
{
    const BASE_PATH = 'admin.page-builder.';

    public function dynamicpage_builder($type,$id){
        if (empty($type) || empty($id)){
            abort(404);
        }
        $page = Page::findOrFail($id);
        return view(self::BASE_PATH.'dynamicpage',compact('id','type','page'));
    }


    public function get_admin_panel_addon_markup(Request $request){
        $output = PageBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->addon_class,
            'namespace' => base64_decode( $request->addon_namespace),
            'type' => 'new',
            'page_id' => $request->addon_page_id ?? '',
            'page_type' => $request->addon_page_type ?? '',
            'location' => $request->addon_location ?? '',
            'after' => false,
            'before' => false,
        ]);
        return $output;
    }

    public function get_widget_settings(Request $request){
        try {
            $widget_id = $request->widget_id;
            
            if(empty($widget_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Widget ID is required'
                ], 400);
            }
            
            $widget = PageBuilder::findOrFail($widget_id);
            
            // Render admin form for this widget
            $admin_markup = PageBuilderSetup::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'page_id' => $widget->addon_page_id ?? '',
                'page_type' => $widget->addon_page_type ?? '',
                'location' => $widget->addon_location ?? '',
                'after' => false,
                'before' => false,
            ]);
            
            return response()->json([
                'success' => true,
                'html' => $admin_markup,
                'widget' => [
                    'id' => $widget->id,
                    'name' => $widget->addon_name,
                    'namespace' => $widget->addon_namespace,
                    'location' => $widget->addon_location,
                    'page_id' => $widget->addon_page_id,
                    'page_type' => $widget->addon_page_type,
                    'order' => $widget->addon_order,
                    'title' => $widget->addon_name, // You might want to get actual title
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Get Widget Settings Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_preview(Request $request){
        try {
            $page_id = $request->page_id;
            $location = $request->location ?? 'dynamic_page';
            $page_type = $request->page_type ?? 'dynamic_page';
            
            $html = '';
            
            // Header with widget IDs
            $headerContent = \App\Facades\ThemeDataFacade::renderPageBuilderHeader();
            if(empty($headerContent)){
                $headerWidgets = PageBuilder::where(['addon_location' => 'header'])
                    ->orderBy('addon_order', 'ASC')
                    ->get();
                
                $headerContent = '';
                foreach($headerWidgets as $widget){
                    $widgetHtml = PageBuilderSetup::render_widgets_by_name_for_frontend([
                        'name' => $widget->addon_name,
                        'namespace' => $widget->addon_namespace,
                        'id' => $widget->id,
                    ]);
                    // Wrap with data-widget-id
                    $headerContent .= '<div data-widget-id="' . $widget->id . '" class="pb-preview-widget">' . $widgetHtml . '</div>';
                }
            }
            $html .= '<div class="pb-preview-header">' . $headerContent . '</div>';
            
            // Page Content with widget IDs
            if($page_id){
                $pageWidgets = PageBuilder::where([
                    'addon_page_type' => $page_type,
                    'addon_page_id' => $page_id
                ])->orderBy('addon_order', 'ASC')->get();
                
                $pageContent = '';
                foreach($pageWidgets as $widget){
                    $widgetHtml = PageBuilderSetup::render_widgets_by_name_for_frontend([
                        'name' => $widget->addon_name,
                        'namespace' => $widget->addon_namespace,
                        'id' => $widget->id,
                    ]);
                    // Wrap with data-widget-id and section ID
                    $pageContent .= '<section id="widget-' . $widget->id . '" data-widget-id="' . $widget->id . '" class="pb-preview-widget">' . $widgetHtml . '</section>';
                }
            } else {
                $pageContent = PageBuilderSetup::render_frontend_pagebuilder_content_by_location($location);
                // Add data-widget-id to existing content
                $pageWidgets = PageBuilder::where(['addon_location' => $location])
                    ->orderBy('addon_order', 'ASC')
                    ->get();
                foreach($pageWidgets as $widget){
                    $pageContent = str_replace(
                        '<section id="' . $widget->id . '"',
                        '<section id="widget-' . $widget->id . '" data-widget-id="' . $widget->id . '"',
                        $pageContent
                    );
                }
            }
            $html .= '<div class="pb-preview-page-content">' . $pageContent . '</div>';
            
            // Footer with widget IDs
            $footerContent = \App\Facades\ThemeDataFacade::renderPageBuilderFooter();
            if(empty($footerContent)){
                $footerWidgets = PageBuilder::where(['addon_location' => 'footer'])
                    ->orderBy('addon_order', 'ASC')
                    ->get();
                
                $footerContent = '';
                foreach($footerWidgets as $widget){
                    $widgetHtml = PageBuilderSetup::render_widgets_by_name_for_frontend([
                        'name' => $widget->addon_name,
                        'namespace' => $widget->addon_namespace,
                        'id' => $widget->id,
                    ]);
                    // Wrap with data-widget-id
                    $footerContent .= '<div data-widget-id="' . $widget->id . '" class="pb-preview-widget">' . $widgetHtml . '</div>';
                }
            }
            $html .= '<div class="pb-preview-footer">' . $footerContent . '</div>';
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('PageBuilder Preview Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store_new_addon_content(Request $request){
        \Log::info('PageBuilder store_new_addon_content called', [
            'url' => $request->fullUrl(),
            'tenant_id' => tenant()?->id,
            'data' => $request->except(['_token'])
        ]);
        
        try {
            // Ensure tenant is initialized
            if (!tenant()) {
                \Log::error('PageBuilder: No tenant context available');
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant context is required'
                ], 400);
            }

            $this->validate($request,[
                'addon_name' => 'required|string',
                'addon_namespace' => 'required|string',
                'addon_order' => 'nullable|integer',
                'addon_location' => 'required|string',
            ]);

            unset($request['_token']);
            $widget_content = (array) $request->all();

            // Decode namespace if it's base64 encoded
            $namespace = $request->addon_namespace;
            try {
                // Try to decode if it's base64
                $decoded = base64_decode($namespace, true);
                if ($decoded !== false && base64_encode($decoded) === $namespace) {
                    $namespace = $decoded;
                }
            } catch (\Exception $e) {
                // If decoding fails, use as is
            }

            // Use tenant connection for transaction
            // Stancl Tenancy sets the default connection to tenant DB when initialized
            $connection = DB::connection();
            
            // Verify connection is working
            try {
                $connection->getPdo();
            } catch (\Exception $e) {
                \Log::error('PageBuilder: Database connection failed', [
                    'error' => $e->getMessage(),
                    'tenant_id' => tenant()?->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Database connection failed: ' . $e->getMessage()
                ], 500);
            }
            
            $connection->beginTransaction();
            try {
                // Create widget in database (needed for ID)
                $widget = PageBuilder::create([
                    'addon_type' => $request->addon_type ?? 'new',
                    'addon_location' => $request->addon_location,
                    'addon_name' => $request->addon_name,
                    'addon_namespace' => $namespace,
                    'addon_page_id' => $request->addon_page_id,
                    'addon_order' => $request->addon_order ?? 0,
                    'addon_page_type' => $request->addon_page_type,
                    'addon_settings' => json_encode($widget_content),
                ]);
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                throw $e;
            }

            $widget_id = $widget->id;

            // Get tenant ID if available
            $tenant_id = !is_null(tenant()) ? tenant()->id : null;

            // Cache widget settings in Redis immediately (fast response)
            $this->cacheWidgetSettings($widget_id, $widget_content, $tenant_id);

            // Invalidate preview cache
            $this->invalidatePreviewCache($request->addon_location, $request->addon_page_id, $tenant_id);

            // Dispatch job to update database in background (if needed for consistency)
            if (config('queue.default') !== 'sync') {
                $job_data = array_merge($widget_content, [
                    'addon_type' => $request->addon_type ?? 'new',
                    'addon_location' => $request->addon_location,
                    'addon_name' => $request->addon_name,
                    'addon_namespace' => $namespace,
                    'addon_page_id' => $request->addon_page_id,
                    'addon_order' => $request->addon_order,
                    'addon_page_type' => $request->addon_page_type,
                ]);

                SavePageBuilderJob::dispatch($widget_id, $job_data, $tenant_id);
            }

            return response()->json([
                'id' => $widget_id,
                'status' => 'ok',
                'cached' => true,
            ]);
        } catch (\Throwable $e) {
            // Rollback any active transaction
            try {
                $connection = DB::connection();
                if ($connection->transactionLevel() > 0) {
                    $connection->rollBack();
                }
            } catch (\Exception $rollbackException) {
                // Ignore rollback errors
            }
            
            \Log::error('PageBuilder Store New Addon Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'addon_name' => $request->addon_name ?? null,
                    'addon_location' => $request->addon_location ?? null,
                    'addon_page_id' => $request->addon_page_id ?? null,
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request){
        \Log::info('PageBuilder delete called', [
            'widget_id' => $request->id,
            'tenant_id' => tenant()?->id
        ]);
        
        try {
            // Ensure tenant context
            if (!tenant()) {
                \Log::error('PageBuilder delete: No tenant context');
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant context is required'
                ], 400);
            }
            
            // Validate request
            $this->validate($request, [
                'id' => 'required|integer'
            ]);
            
            // Find widget
            $widget = PageBuilder::find($request->id);
            
            if (!$widget) {
                \Log::warning('PageBuilder delete: Widget not found', [
                    'widget_id' => $request->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Widget not found'
                ], 404);
            }
            
            $tenant_id = tenant()->id;
            $location = $widget->addon_location;
            $page_id = $widget->addon_page_id;

            // Delete from cache immediately
            $this->clearWidgetCache($request->id, $tenant_id);
            $this->invalidatePreviewCache($location, $page_id, $tenant_id);

            // Delete from database
            try {
                if (config('queue.default') !== 'sync') {
                    // Delete via queue
                    DeletePageBuilderJob::dispatch($request->id, $tenant_id, $page_id, $location);
                    // But also delete immediately to avoid UI issues
                    $widget->delete();
                } else {
                    // If sync queue, delete immediately
                    $widget->delete();
                }
                
                \Log::info('PageBuilder widget deleted successfully', [
                    'widget_id' => $request->id
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Widget deleted successfully'
                ]);
                
            } catch (\Exception $e) {
                \Log::error('PageBuilder delete: Database error', [
                    'widget_id' => $request->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete widget from database: ' . $e->getMessage()
                ], 500);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('PageBuilder delete: Unexpected error', [
                'widget_id' => $request->id ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_addon_order(Request $request){
        Cache::forget('widget_settings_cache'.$request->id);
        Cache::forget('page_id-'.$request->addon_page_id);
        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);
        return response()->json('ok');
    }

    public function update_addon_content(Request $request){
        $this->validate($request,[
            'id' => 'required|exists:page_builders,id',
            'addon_name' => 'required|string',
            'addon_namespace' => 'required|string',
            'addon_order' => 'nullable|integer',
            'addon_location' => 'required|string',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();

        // Decode namespace if it's base64 encoded
        $namespace = $request->addon_namespace;
        try {
            // Try to decode if it's base64
            $decoded = base64_decode($namespace, true);
            if ($decoded !== false && base64_encode($decoded) === $namespace) {
                $namespace = $decoded;
            }
        } catch (\Exception $e) {
            // If decoding fails, use as is
        }

        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Cache widget settings in Redis immediately (fast response)
        $this->cacheWidgetSettings($request->id, $addon_content, $tenant_id);

        // Invalidate preview cache
        $this->invalidatePreviewCache($request->addon_location, $request->addon_page_id ?? null, $tenant_id);

        // Prepare data for database update
        $job_data = array_merge($addon_content, [
            'addon_type' => $request->addon_type ?? 'update',
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => $namespace,
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
        ]);

        // Update database via queue (if not sync)
        if (config('queue.default') !== 'sync') {
            SavePageBuilderJob::dispatch($request->id, $job_data, $tenant_id);
        } else {
            // If sync queue, update immediately
            PageBuilder::findOrFail($request->id)->update([
                'addon_type' => $request->addon_type ?? 'update',
                'addon_location' => $request->addon_location,
                'addon_name' => $request->addon_name,
                'addon_namespace' => $namespace,
                'addon_page_id' => $request->addon_page_id,
                'addon_order' => $request->addon_order,
                'addon_page_type' => $request->addon_page_type,
                'addon_settings' => json_encode($addon_content),
            ]);
        }

        return response()->json(['status' => 'ok', 'cached' => true]);
    }

    /**
     * Cache widget settings in Redis
     */
    protected function cacheWidgetSettings(int $widget_id, array $settings, ?int $tenant_id): void
    {
        try {
            $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                "pagebuilder_widget_{$widget_id}"
            );

            // Cache for 1 hour
            Cache::store('redis')->put(
                $cacheKey,
                $settings,
                config('cache-tenancy.ttl.default', 3600)
            );

            // Also cache in legacy format for backward compatibility
            Cache::forget('widget_settings_cache' . $widget_id);
            Cache::put('widget_settings_cache' . $widget_id, $settings, 3600);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to cache PageBuilder widget settings', [
                'widget_id' => $widget_id,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Clear widget cache
     */
    protected function clearWidgetCache(int $widget_id, ?int $tenant_id): void
    {
        try {
            $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                "pagebuilder_widget_{$widget_id}"
            );

            Cache::store('redis')->forget($cacheKey);
            Cache::forget('widget_settings_cache' . $widget_id);
        } catch (\Exception $e) {
            \Log::warning('Failed to clear PageBuilder widget cache', [
                'widget_id' => $widget_id,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Invalidate preview cache
     */
    protected function invalidatePreviewCache(?string $location, ?int $page_id, ?int $tenant_id): void
    {
        try {
            // Clear location-specific cache
            if ($location && $tenant_id) {
                if ($location === 'header') {
                    Cache::forget('pagebuilder_header_exists_' . $tenant_id);
                    Cache::forget('pagebuilder_header_content_' . $tenant_id);
                } elseif ($location === 'footer') {
                    Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
                    Cache::forget('pagebuilder_footer_content_' . $tenant_id);
                }
            }

            // Clear page-specific cache
            if ($page_id) {
                Cache::forget('page_id-' . $page_id);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to invalidate PageBuilder preview cache', [
                'location' => $location,
                'page_id' => $page_id,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

}
