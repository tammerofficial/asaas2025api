<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Central\Settings\UpdateSettingsRequest;
use App\Http\Resources\Api\Central\SettingsResource;
use App\Models\StaticOptionCentral;
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
            $cacheKey = 'central_settings_all';
            $settings = $this->remember($cacheKey, 600, function () {
                return StaticOptionCentral::all()->mapWithKeys(function ($option) {
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
            $settings = StaticOptionCentral::all()->mapWithKeys(function ($option) {
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
            $cacheKey = "central_setting_{$key}";
            $setting = $this->remember($cacheKey, 600, function () use ($key) {
                $option = StaticOptionCentral::where('option_name', $key)->first();
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
            $option = StaticOptionCentral::where('option_name', $key)->first();

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
        $validated = $request->validated();
        $updated = [];

        // Update each setting
        foreach ($validated['settings'] as $key => $value) {
            StaticOptionCentral::updateOrCreate(
                ['option_name' => $key],
                [
                    'option_value' => $value,
                    'unique_key' => $key, // Set unique_key for syncing
                ]
            );
            
            $updated[$key] = $value;
            
            // Clear cache for this setting
            Cache::forget("central_setting_{$key}");
        }

        // Clear all settings cache
        $this->clearCache('central_settings*');

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
        $option = StaticOptionCentral::where('option_name', $key)->first();

        if (!$option) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found',
            ], 404);
        }

        $option->delete();

        // Clear cache
        Cache::forget("central_setting_{$key}");
        $this->clearCache('central_settings*');

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully',
        ]);
    }
}

