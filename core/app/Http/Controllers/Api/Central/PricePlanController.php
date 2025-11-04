<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Central\PricePlan\StorePricePlanRequest;
use App\Http\Requests\Api\Central\PricePlan\UpdatePricePlanRequest;
use App\Http\Resources\Api\Central\PricePlanResource;
use App\Models\PricePlan;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class PricePlanController extends BaseApiController
{
    /**
     * Display a listing of price plans
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache plans for 10 minutes (plans don't change frequently)
            $cacheKey = 'central_price_plans_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 600, function () {
                return PricePlan::select([
                    'id', 'title', 'features', 'type', 'status', 'price', 'free_trial',
                    'faq', 'page_permission_feature', 'blog_permission_feature',
                    'product_permission_feature', 'storage_permission_feature',
                    'package_badge', 'package_description', 'created_at', 'updated_at'
                ])
                ->with([
                    'plan_features:id,price_plan_id,name,value',
                    'plan_themes:id,price_plan_id,theme_slug',
                    'plan_payment_gateways:id,price_plan_id,gateway'
                ])
                ->latest()
                ->paginate(20);
            });

            return PricePlanResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $plans = PricePlan::with([
                'plan_features:id,price_plan_id,name,value',
                'plan_themes:id,price_plan_id,theme_slug',
                'plan_payment_gateways:id,price_plan_id,gateway'
            ])
            ->latest()
            ->paginate(20);

            return PricePlanResource::collection($plans);
        }
    }

    /**
     * Store a newly created price plan
     */
    public function store(StorePricePlanRequest $request): JsonResponse
    {
        $plan = PricePlan::create($request->validated());
        
        // Clear cache when creating new plan
        $this->clearCache('central_price_plans*');

        // Load relationships efficiently
        $plan->load([
            'plan_features:id,price_plan_id,name,value',
            'plan_themes:id,price_plan_id,theme_slug',
            'plan_payment_gateways:id,price_plan_id,gateway'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Price plan created successfully',
            'data' => new PricePlanResource($plan),
        ], 201);
    }

    /**
     * Display the specified price plan
     */
    public function show(PricePlan $plan): JsonResponse
    {
        try {
            // Cache individual plan for 10 minutes
            $cachedPlan = $this->remember("central_price_plan_{$plan->id}", 600, function () use ($plan) {
                $plan->load([
                    'plan_features:id,price_plan_id,name,value',
                    'plan_themes:id,price_plan_id,theme_slug',
                    'plan_payment_gateways:id,price_plan_id,gateway'
                ]);
                return $plan;
            });

            return response()->json([
                'success' => true,
                'message' => 'Price plan retrieved successfully',
                'data' => new PricePlanResource($cachedPlan),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $plan->load([
                'plan_features:id,price_plan_id,name,value',
                'plan_themes:id,price_plan_id,theme_slug',
                'plan_payment_gateways:id,price_plan_id,gateway'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Price plan retrieved successfully',
                'data' => new PricePlanResource($plan),
            ]);
        }
    }

    /**
     * Update the specified price plan
     */
    public function update(UpdatePricePlanRequest $request, PricePlan $plan): JsonResponse
    {
        $plan->update($request->validated());
        
        // Clear related cache
        $this->clearCache('central_price_plans*');
        $this->clearCache("central_price_plan_{$plan->id}*");
        
        // Clear tenant caches for this plan
        $this->clearPlanCache($plan->id);

        // Load relationships efficiently
        $plan->load([
            'plan_features:id,price_plan_id,name,value',
            'plan_themes:id,price_plan_id,theme_slug',
            'plan_payment_gateways:id,price_plan_id,gateway'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Price plan updated successfully',
            'data' => new PricePlanResource($plan),
        ]);
    }

    /**
     * Remove the specified price plan
     */
    public function destroy(PricePlan $plan): JsonResponse
    {
        $planId = $plan->id;
        $plan->delete();
        
        // Clear related cache
        $this->clearCache('central_price_plans*');
        $this->clearCache("central_price_plan_{$planId}*");

        return response()->json([
            'success' => true,
            'message' => 'Price plan deleted successfully',
        ]);
    }

    /**
     * Clear cache for all tenants using this plan
     */
    protected function clearPlanCache($planId)
    {
        // Clear Eloquent relationship cache
        Cache::forget("price_plan_{$planId}_features");
        
        // Get all tenants using this plan
        $tenants = Tenant::where('price_plan_id', $planId)->get();
        
        foreach ($tenants as $tenant) {
            // Clear dashboard statistics cache
            Cache::forget("tenant_dashboard_stats_{$tenant->id}");
            Cache::forget("tenant_dashboard_stats_detailed_{$tenant->id}");
            
            // Clear view cache
            Cache::forget("tenant_sidebar_menu_{$tenant->id}");
        }
        
        // Clear all application cache
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
    }
}

