<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';
    protected $fillable = ['plan_id','feature_name','status'];

    public function plan()
    {
        return $this->belongsTo(PricePlan::class,'plan_id','id');
    }

    /**
     * Boot the model and register event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache AFTER any change to plan features
        static::created(function ($planFeature) {
            static::clearPlanCache($planFeature->plan_id);
        });
        
        static::updated(function ($planFeature) {
            static::clearPlanCache($planFeature->plan_id);
        });

        static::deleted(function ($planFeature) {
            static::clearPlanCache($planFeature->plan_id);
        });
    }

    /**
     * Clear cache for all tenants using this plan
     */
    protected static function clearPlanCache($planId)
    {
        // Clear Eloquent relationship cache
        Cache::forget("price_plan_{$planId}_features");
        
        // Get all tenants using this plan
        $tenants = \App\Models\Tenant::where('price_plan_id', $planId)->get();
        
        foreach ($tenants as $tenant) {
            // Clear dashboard statistics cache
            Cache::forget("tenant_dashboard_stats_{$tenant->id}");
            Cache::forget("tenant_dashboard_stats_detailed_{$tenant->id}");
            
            // Clear view cache
            Cache::forget("tenant_sidebar_menu_{$tenant->id}");
        }
    }
}
