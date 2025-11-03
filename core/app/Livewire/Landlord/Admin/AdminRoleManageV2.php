<?php

namespace App\Livewire\Landlord\Admin;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Url;
use App\Models\Admin;
use App\Helpers\SanitizeInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Lazy]
class AdminRoleManageV2 extends Component
{
    #[Url(as: 'view', keep: true)]
    public string $view = 'all-users';
    public $allUsers = [];
    public $roles = [];
    public $allRoles = [];
    public $permissions = [];
    public $rolePermissions = [];
    
    // User Management Properties
    public $userId = null;
    public $userName = '';
    public $userEmail = '';
    public $userRole = '';
    public $userImage = null;
    public $userMobile = '';
    public $userPassword = '';
    public $userPasswordConfirmation = '';
    
    // Role Management Properties
    public $roleId = null;
    public $roleName = '';
    public $selectedPermissions = [];

    protected $listeners = [];

    public function mount()
    {
        // Optimized mount for Octane - minimal initialization
    }

    public function hydrate()
    {
        // Load data on hydration - faster booting with lazy loading
        if ($this->view === 'all-users') {
            $this->loadAllUsers();
            $this->loadRoles();
        } elseif ($this->view === 'all-roles') {
            $this->loadAllRoles();
            $this->loadPermissions();
        } elseif (in_array($this->view, ['new-user', 'edit-user'])) {
            $this->loadRoles();
        } elseif (in_array($this->view, ['new-role', 'edit-role'])) {
            $this->loadPermissions();
        }
    }

    public function navigateView(string $view)
    {
        $this->view = $view;
        $this->resetUserForm();
        $this->resetRoleForm();
    }

    public function loadAllUsers()
    {
        $cacheKey = 'admin_users_list_' . auth('admin')->id();
        
        $this->allUsers = Cache::store('redis')->remember($cacheKey, 120, function () {
            return Admin::with('roles')
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username ?? '',
                        'mobile' => $user->mobile ?? '',
                        'roles' => $user->roles->pluck('name')->toArray(),
                        'created_at' => $user->created_at,
                    ];
                })
                ->toArray();
        });
    }

    public function loadRoles()
    {
        $this->roles = Role::all()->pluck('name', 'id')->toArray();
    }

    public function loadAllRoles()
    {
        $cacheKey = 'admin_roles_list_' . auth('admin')->id();
        
        $this->allRoles = Cache::store('redis')->remember($cacheKey, 120, function () {
            return Role::with('permissions')
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions_count' => $role->permissions->count(),
                        'created_at' => $role->created_at,
                    ];
                })
                ->toArray();
        });
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        })->toArray();
    }

    public function resetUserForm()
    {
        $this->userId = null;
        $this->userName = '';
        $this->userEmail = '';
        $this->userRole = '';
        $this->userImage = null;
        $this->userMobile = '';
        $this->userPassword = '';
        $this->userPasswordConfirmation = '';
    }

    public function resetRoleForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
    }

    public function showEditUserForm($userId)
    {
        $user = Admin::with('roles')->findOrFail($userId);
        $this->userId = $user->id;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userMobile = $user->mobile ?? '';
        $this->userImage = $user->image;
        $this->userRole = $user->roles->first()?->id ?? '';
        $this->view = 'edit-user';
    }

    public function showEditRoleForm($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->view = 'edit-role';
    }

    public function showNewUserForm()
    {
        $this->view = 'new-user';
        $this->resetUserForm();
    }

    public function showNewRoleForm()
    {
        $this->view = 'new-role';
        $this->resetRoleForm();
    }

    public function saveUser()
    {
        $this->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:admins,email,' . $this->userId,
            'userMobile' => 'nullable|string|max:20',
            'userRole' => 'required|exists:roles,name',
        ]);

        if ($this->userId) {
            // Update existing user
            $admin = Admin::findOrFail($this->userId);
            $admin->name = $this->userName;
            $admin->email = $this->userEmail;
            $admin->mobile = $this->userMobile;
            if ($this->userImage) {
                $admin->image = $this->userImage;
            }
            if ($this->userPassword && $this->userPassword === $this->userPasswordConfirmation) {
                $admin->password = Hash::make($this->userPassword);
            }
            $admin->save();
            
            // Sync roles
            $role = Role::where('name', $this->userRole)->first();
            if ($role) {
                $admin->syncRoles([$role]);
            }
            
            session()->flash('success', 'Admin updated successfully');
        } else {
            // Create new user
            $this->validate([
                'userPassword' => 'required|string|min:8|confirmed',
            ]);
            
            $admin = Admin::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'username' => str_replace(' ', '_', strtolower($this->userName)),
                'mobile' => $this->userMobile,
                'image' => $this->userImage,
                'password' => Hash::make($this->userPassword),
                'status' => 1,
            ]);
            
            // Assign role
            $role = Role::where('name', $this->userRole)->first();
            if ($role) {
                $admin->assignRole($role);
            }
            
            session()->flash('success', 'Admin created successfully');
        }

        Cache::store('redis')->forget('admin_users_list_' . auth('admin')->id());
        $this->loadAllUsers();
        $this->view = 'all-users';
        $this->resetUserForm();
    }

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'required|array|min:1',
        ]);

        if ($this->roleId) {
            // Update existing role
            $role = Role::findOrFail($this->roleId);
            $role->name = $this->roleName;
            $role->save();
            $role->syncPermissions($this->selectedPermissions);
            
            session()->flash('success', 'Role updated successfully');
        } else {
            // Create new role
            $role = Role::create(['name' => $this->roleName]);
            $role->syncPermissions($this->selectedPermissions);
            
            session()->flash('success', 'Role created successfully');
        }

        Cache::store('redis')->forget('admin_roles_list_' . auth('admin')->id());
        Cache::store('redis')->forget('admin_roles_pluck');
        Cache::store('redis')->forget('admin_permissions_grouped');
        $this->loadAllRoles();
        $this->view = 'all-roles';
        $this->resetRoleForm();
    }

    public function deleteUser($userId)
    {
        if ($userId == auth('admin')->id()) {
            session()->flash('error', 'Cannot delete your own account');
            return;
        }
        
        $admin = Admin::findOrFail($userId);
        $admin->delete();
        
        Cache::store('redis')->forget('admin_users_list_' . auth('admin')->id());
        $this->loadAllUsers();
        
        session()->flash('success', 'Admin deleted successfully');
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();
        
        Cache::store('redis')->forget('admin_roles_list_' . auth('admin')->id());
        Cache::store('redis')->forget('admin_roles_pluck');
        $this->loadAllRoles();
        
        session()->flash('success', 'Role deleted successfully');
    }

    public function render()
    {
        $titles = [
            'all-users' => 'All Admins',
            'all-roles' => 'All Admin Roles',
            'new-user' => 'Add New Admin',
            'edit-user' => 'Edit Admin',
            'new-role' => 'Add New Role',
            'edit-role' => 'Edit Role',
        ];
        
        $title = $titles[$this->view] ?? 'Admin Role Manage';
        
        return view('livewire.landlord.admin.admin-role-manage-v2')
            ->layout('layouts.landlord.admin.v2.master', [
                'title' => $title
            ]);
    }
}
