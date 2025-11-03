<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    /**
     * Handle an incoming request.
     *
     * Ensure that the request is in Tenant context (tenant context must be initialized).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if tenant context is initialized
        if (!tenancy()->initialized) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context is required for this endpoint',
            ], 403);
        }

        $tenant = tenant();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found',
            ], 404);
        }

        // Verify token tenant_id matches request tenant (if token has tenant_id)
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $tokenAbilities = $user->currentAccessToken()->abilities ?? [];
            $tokenTenantId = $tokenAbilities['tenant_id'] ?? null;

            if ($tokenTenantId && $tokenTenantId != $tenant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tenant mismatch',
                ], 403);
            }
        }

        return $next($request);
    }
}

