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

    public function store_new_addon_content(Request $request){
        $this->validate($request,[
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        // Create widget in database (needed for ID)
        $widget_id = PageBuilder::create([
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
            'addon_settings' => json_encode($widget_content),
        ])->id;

        // Get tenant ID if available
        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Cache widget settings in Redis immediately (fast response)
        $this->cacheWidgetSettings($widget_id, $widget_content, $tenant_id);

        // Invalidate preview cache
        $this->invalidatePreviewCache($request->addon_location, $request->addon_page_id, $tenant_id);

        // Dispatch job to update database in background (if needed for consistency)
        if (config('queue.default') !== 'sync') {
            $job_data = array_merge($widget_content, [
                'addon_type' => $request->addon_type,
                'addon_location' => $request->addon_location,
                'addon_name' => $request->addon_name,
                'addon_namespace' => base64_decode($request->addon_namespace),
                'addon_page_id' => $request->addon_page_id,
                'addon_order' => $request->addon_order,
                'addon_page_type' => $request->addon_page_type,
            ]);

            SavePageBuilderJob::dispatch($widget_id, $job_data, $tenant_id);
        }

        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        $data['cached'] = true;
        return response()->json($data);
    }

    public function delete(Request $request){
        $widget = PageBuilder::findOrFail($request->id);
        
        $tenant_id = !is_null(tenant()) ? tenant()->id : null;
        $location = $widget->addon_location;
        $page_id = $widget->addon_page_id;

        // Delete from cache immediately
        $this->clearWidgetCache($request->id, $tenant_id);
        $this->invalidatePreviewCache($location, $page_id, $tenant_id);

        // Delete from database via queue (if not sync)
        if (config('queue.default') !== 'sync') {
            DeletePageBuilderJob::dispatch($request->id, $tenant_id, $page_id, $location);
        } else {
            // If sync queue, delete immediately
            $widget->delete();
        }

        return response()->json('ok');
    }

    public function update_addon_order(Request $request){
        Cache::forget('widget_settings_cache'.$request->id);
        Cache::forget('page_id-'.$request->addon_page_id);
        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);
        return response()->json('ok');
    }

    public function update_addon_content(Request $request){
        $this->validate($request,[
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();

        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Cache widget settings in Redis immediately (fast response)
        $this->cacheWidgetSettings($request->id, $addon_content, $tenant_id);

        // Invalidate preview cache
        $this->invalidatePreviewCache($request->addon_location, $request->addon_page_id, $tenant_id);

        // Prepare data for database update
        $job_data = array_merge($addon_content, [
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
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
                'addon_type' => $request->addon_type,
                'addon_location' => $request->addon_location,
                'addon_name' => $request->addon_name,
                'addon_namespace' => base64_decode($request->addon_namespace),
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
