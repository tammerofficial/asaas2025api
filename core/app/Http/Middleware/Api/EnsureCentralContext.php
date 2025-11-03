<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralContext
{
    /**
     * Handle an incoming request.
     *
     * Ensure that the request is in Central context (not tenant context).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if tenant context is initialized (should not be for Central API)
        if (tenancy()->initialized) {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint is only accessible from central domain',
            ], 403);
        }

        return $next($request);
    }
}

