<?php

namespace App\Http\Controllers\Api\Central\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Central\Auth\LoginRequest;
use App\Http\Resources\Api\Central\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login for Central Admin users
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Determine if input is email or username
        $type = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $admin = Admin::where($type, $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create Sanctum token with abilities
        $token = $admin->createToken('api-token', [
            'type' => 'central_admin',
            'role' => $admin->roles->pluck('name')->toArray(),
        ])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => new AdminResource($admin),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Register new admin (if needed)
     */
    public function register(): JsonResponse
    {
        // Implementation for admin registration if needed
        return response()->json([
            'success' => false,
            'message' => 'Admin registration is not available',
        ], 403);
    }

    /**
     * Get current authenticated admin
     */
    public function me(): JsonResponse
    {
        $admin = auth('admin')->user();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin retrieved successfully',
            'data' => new AdminResource($admin),
        ]);
    }

    /**
     * Logout current admin
     */
    public function logout(): JsonResponse
    {
        $admin = auth('admin')->user();
        
        if (!$admin) {
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
        $admin = auth('admin')->user();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        // Revoke old token
        $admin->currentAccessToken()->delete();
        
        // Create new token
        $token = $admin->createToken('api-token', [
            'type' => 'central_admin',
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

