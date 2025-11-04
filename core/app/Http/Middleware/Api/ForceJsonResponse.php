<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Force JSON response for all API requests
 * Ensures Content-Type is always application/json
 */
class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force Accept header to application/json for API requests
        $request->headers->set('Accept', 'application/json');
        
        $response = $next($request);
        
        // Ensure Content-Type is application/json
        if (!$response->headers->has('Content-Type') || 
            !str_contains($response->headers->get('Content-Type'), 'application/json')) {
            $response->headers->set('Content-Type', 'application/json');
        }
        
        return $response;
    }
}




