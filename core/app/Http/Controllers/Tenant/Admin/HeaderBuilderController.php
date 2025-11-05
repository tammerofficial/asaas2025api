<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Plugins\PageBuilder\PageBuilderSetup;

class HeaderBuilderController extends Controller
{
    /**
     * Show Header Builder page
     */
    public function index()
    {
        return view('tenant.admin.header-builder');
    }

    /**
     * Get admin panel addon markup
     */
    public function getAddonMarkup(Request $request)
    {
        $output = PageBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->addon_class,
            'namespace' => base64_decode($request->addon_namespace),
            'type' => 'new',
            'location' => 'header', // Force location to header
            'after' => false,
            'before' => false,
        ]);
        return $output;
    }

    /**
     * Store new addon content
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        $widget_id = PageBuilder::create([
            'addon_type' => $request->addon_type,
            'addon_location' => 'header', // Force location to header
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => null,
            'addon_order' => $request->addon_order ?? 0,
            'addon_page_type' => null,
            'addon_settings' => json_encode($widget_content),
        ])->id;

        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('pagebuilder_header_exists_' . $tenant_id);
        Cache::forget('pagebuilder_header_content_' . $tenant_id);

        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        return response()->json($data);
    }

    /**
     * Update addon content
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();

        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('widget_settings_cache' . $request->id);
        Cache::forget('pagebuilder_header_exists_' . $tenant_id);
        Cache::forget('pagebuilder_header_content_' . $tenant_id);

        PageBuilder::findOrFail($request->id)->update([
            'addon_type' => $request->addon_type,
            'addon_location' => 'header', // Force location to header
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_order' => $request->addon_order ?? 0,
            'addon_settings' => json_encode($addon_content),
        ]);

        $data['status'] = 'ok';
        return response()->json($data);
    }

    /**
     * Delete addon
     */
    public function delete(Request $request)
    {
        PageBuilder::findOrFail($request->id)->delete();

        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('widget_settings_cache' . $request->id);
        Cache::forget('pagebuilder_header_exists_' . $tenant_id);
        Cache::forget('pagebuilder_header_content_' . $tenant_id);

        return response()->json('ok');
    }

    /**
     * Update addon order
     */
    public function updateOrder(Request $request)
    {
        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('widget_settings_cache' . $request->id);
        Cache::forget('pagebuilder_header_exists_' . $tenant_id);
        Cache::forget('pagebuilder_header_content_' . $tenant_id);

        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);
        return response()->json('ok');
    }

    /**
     * Save header settings (sticky, transparent)
     */
    public function saveSettings(Request $request)
    {
        $this->validate($request, [
            'header_sticky' => 'nullable|in:yes,no',
            'header_transparent' => 'nullable|in:yes,no',
        ]);

        // Save settings using static option
        update_static_option('header_builder_sticky', $request->header_sticky ?? 'no');
        update_static_option('header_builder_transparent', $request->header_transparent ?? 'no');

        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('pagebuilder_header_exists_' . $tenant_id);
        Cache::forget('pagebuilder_header_content_' . $tenant_id);

        return response()->json([
            'status' => 'ok',
            'msg' => __('Settings saved successfully'),
            'type' => 'success'
        ]);
    }
}
