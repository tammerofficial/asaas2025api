<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Central\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index(): AnonymousResourceCollection
    {
        $admins = Admin::with('roles')
            ->latest()
            ->paginate(20);

        return AdminResource::collection($admins);
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email|max:255',
            'password' => 'required|string|min:8',
            'username' => 'nullable|string|max:255|unique:admins,username',
            'mobile' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $admin = Admin::create($validated);

        // Assign roles if provided
        if ($request->has('roles') && !empty($request->roles)) {
            $admin->assignRole($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => new AdminResource($admin->load('roles')),
        ], 201);
    }

    /**
     * Display the specified admin
     */
    public function show(Admin $admin): JsonResponse
    {
        $admin->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'Admin retrieved successfully',
            'data' => new AdminResource($admin),
        ]);
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, Admin $admin): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('admins', 'email')->ignore($admin->id), 'max:255'],
            'password' => 'sometimes|string|min:8',
            'username' => ['sometimes', 'nullable', 'string', Rule::unique('admins', 'username')->ignore($admin->id), 'max:255'],
            'mobile' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $admin->update($validated);

        // Sync roles if provided
        if ($request->has('roles')) {
            $admin->syncRoles($request->roles ?? []);
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully',
            'data' => new AdminResource($admin->load('roles')),
        ]);
    }

    /**
     * Remove the specified admin
     */
    public function destroy(Admin $admin): JsonResponse
    {
        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account',
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully',
        ]);
    }

    /**
     * Activate admin
     */
    public function activate(Admin $admin): JsonResponse
    {
        $admin->update(['email_verified' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Admin activated successfully',
            'data' => new AdminResource($admin->load('roles')),
        ]);
    }

    /**
     * Deactivate admin
     */
    public function deactivate(Admin $admin): JsonResponse
    {
        // Prevent deactivating yourself
        if ($admin->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account',
            ], 403);
        }

        $admin->update(['email_verified' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Admin deactivated successfully',
            'data' => new AdminResource($admin->load('roles')),
        ]);
    }
}

