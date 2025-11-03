<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Settings\UpdateSettingsRequest;
use App\Http\Resources\Api\Tenant\SettingsResource;
use App\Models\StaticOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SettingsController extends BaseApiController
{
    /**
     * Get all settings
     */
    public function index(): JsonResponse
    {
        try {
            // Cache settings for 10 minutes
            $cacheKey = 'tenant_settings_all';
            $settings = $this->remember($cacheKey, 600, function () {
                return StaticOption::all()->mapWithKeys(function ($option) {
                    return [$option->option_name => $option->option_value];
                })->toArray();
            });

            return response()->json([
                'success' => true,
                'message' => 'Settings retrieved successfully',
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $settings = StaticOption::all()->mapWithKeys(function ($option) {
                return [$option->option_name => $option->option_value];
            })->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Settings retrieved successfully',
                'data' => $settings,
            ]);
        }
    }

    /**
     * Get a specific setting by key
     */
    public function show(string $key): JsonResponse
    {
        try {
            // Cache individual setting for 10 minutes
            $cacheKey = "tenant_setting_{$key}";
            $setting = $this->remember($cacheKey, 600, function () use ($key) {
                $option = StaticOption::where('option_name', $key)->first();
                return $option ? [
                    'key' => $option->option_name,
                    'value' => $option->option_value,
                ] : null;
            });

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Setting retrieved successfully',
                'data' => $setting,
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $option = StaticOption::where('option_name', $key)->first();

            if (!$option) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Setting retrieved successfully',
                'data' => [
                    'key' => $option->option_name,
                    'value' => $option->option_value,
                ],
            ]);
        }
    }

    /**
     * Update settings (bulk or single)
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $validated = $request->validated();
        $updated = [];

        // Update each setting
        foreach ($validated['settings'] as $key => $value) {
            StaticOption::updateOrCreate(
                ['option_name' => $key],
                ['option_value' => $value]
            );
            
            $updated[$key] = $value;
            
            // Clear cache for this setting
            Cache::forget("tenant_setting_{$key}");
        }

        // Clear all settings cache
        $this->clearCache('tenant_settings*');

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => $updated,
        ]);
    }

    /**
     * Delete a setting by key
     */
    public function destroy(string $key): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $option = StaticOption::where('option_name', $key)->first();

        if (!$option) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found',
            ], 404);
        }

        $option->delete();

        // Clear cache
        Cache::forget("tenant_setting_{$key}");
        $this->clearCache('tenant_settings*');

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully',
        ]);
    }
}

