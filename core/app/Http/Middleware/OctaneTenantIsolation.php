<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Octane Tenant Isolation Middleware
 * 
 * Critical for preventing memory leaks and data leakage between tenants
 * when using Laravel Octane (RoadRunner/Swoole)
 */
class OctaneTenantIsolation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store tenant context before request
        $tenantId = null;
        if (tenancy()->initialized) {
            $tenantId = tenant('id');
        }

        // Process request
        $response = $next($request);

        // CRITICAL: Clean up after request in Octane
        if (app()->bound('octane')) {
            $this->cleanupTenantContext($tenantId);
        }

        return $response;
    }

    /**
     * Cleanup tenant context to prevent memory leaks
     * 
     * This is CRITICAL in Octane because the application stays in memory
     */
    protected function cleanupTenantContext(?int $tenantId): void
    {
        // 1. Clear resolved instances that might hold tenant data
        $this->clearResolvedInstances();

        // 2. Clear any cached tenant-specific data
        $this->clearTenantScopedData();

        // 3. Reset global state (if any)
        $this->resetGlobalState();

        // 4. Log cleanup for monitoring (optional)
        if (config('cache-tenancy.monitoring.enabled', false)) {
            $this->logCleanup($tenantId);
        }
    }

    /**
     * Clear resolved service container instances
     */
    protected function clearResolvedInstances(): void
    {
        $servicesToClear = [
            'auth',
            'auth.driver',
            'cache',
            'cache.store',
            'db',
            'db.connection',
            'view',
            'translator',
            'url',
            'router',
            'session',
            'session.store',
        ];

        foreach ($servicesToClear as $service) {
            if (app()->bound($service)) {
                app()->forgetInstance($service);
            }
        }
    }

    /**
     * Clear tenant-scoped data from memory
     */
    protected function clearTenantScopedData(): void
    {
        // Clear any static properties that might hold tenant data
        // Add your models/classes here if they use static properties
        
        // Example:
        // YourModel::clearBootedModels();
        
        // Clear config cache if it contains tenant-specific values
        if (tenancy()->initialized) {
            // Config will be reloaded on next request
        }
    }

    /**
     * Reset global state
     */
    protected function resetGlobalState(): void
    {
        // Clear any global variables or state
        // This prevents data leakage between requests
        
        // Reset tenant() helper cache
        tenancy()->end();
        
        // Clear query log to prevent memory buildup
        if (config('app.debug')) {
            foreach (array_keys(config('database.connections')) as $connection) {
                try {
                    \DB::connection($connection)->flushQueryLog();
                } catch (\Exception $e) {
                    // Connection might not exist
                }
            }
        }
    }

    /**
     * Log cleanup for monitoring
     */
    protected function logCleanup(?int $tenantId): void
    {
        logger()->channel(config('cache-tenancy.monitoring.log_channel', 'cache'))->debug(
            'Octane tenant context cleaned',
            [
                'tenant_id' => $tenantId,
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
            ]
        );
    }

    /**
     * Handle Octane request termination
     * 
     * This runs after the response is sent to the client
     */
    public function terminate(Request $request, Response $response): void
    {
        // Additional cleanup that can happen after response is sent
        
        // Garbage collection hint (Octane will handle this)
        if (app()->bound('octane') && config('octane.garbage_collection.enabled', false)) {
            // gc_collect_cycles(); // Octane handles this
        }
    }
}

