<?php

namespace App\Http\Controllers\Api\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tenant\Auth\LoginRequest;
use App\Http\Resources\Api\Tenant\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login for Tenant Admin users
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Get current tenant context
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Create Sanctum token with abilities including tenant_id
        $token = $admin->createToken('api-token', [
            'type' => 'tenant_admin',
            'tenant_id' => $tenant->id,
            'tenant_domain' => $tenant->domains->first()->domain ?? null,
            'role' => $admin->roles->pluck('name')->toArray(),
        ])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => new AdminResource($admin),
                'tenant' => [
                    'id' => $tenant->id,
                    'domain' => $tenant->domains->first()->domain ?? null,
                    'name' => $tenant->name ?? null,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Get current authenticated admin
     */
    public function me(): JsonResponse
    {
        // Use Sanctum default guard (web) but check admin model
        $admin = auth()->user();
        
        // Ensure it's an Admin model instance
        if (!$admin || !($admin instanceof \App\Models\Admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $tenant = tenant();

        return response()->json([
            'success' => true,
            'message' => 'Admin retrieved successfully',
            'data' => [
                'admin' => new AdminResource($admin),
                'tenant' => $tenant ? [
                    'id' => $tenant->id,
                    'domain' => $tenant->domains->first()->domain ?? null,
                    'name' => $tenant->name ?? null,
                ] : null,
            ],
        ]);
    }

    /**
     * Logout current admin
     */
    public function logout(): JsonResponse
    {
        $admin = auth()->user();
        
        if (!$admin || !($admin instanceof \App\Models\Admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        // Revoke current token
        $admin->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(): JsonResponse
    {
        $admin = auth()->user();
        
        if (!$admin || !($admin instanceof \App\Models\Admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }
        
        // Revoke old token
        $admin->currentAccessToken()->delete();
        
        // Create new token
        $token = $admin->createToken('api-token', [
            'type' => 'tenant_admin',
            'tenant_id' => $tenant->id,
            'tenant_domain' => $tenant->domains->first()->domain ?? null,
            'role' => $admin->roles->pluck('name')->toArray(),
        ])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }
}
