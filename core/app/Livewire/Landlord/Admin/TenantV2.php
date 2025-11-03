<?php

namespace App\Livewire\Landlord\Admin;

use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;

#[Lazy]
class TenantV2 extends Component
{
    public $allTenants = [];
    public string $filter = 'all';

    public function mount()
    {
        // Only set title - data loading moved to hydrate() for faster booting
        $this->dispatch('setPageTitle', ['title' => 'Tenant Manage']);
    }

    public function hydrate()
    {
        // Load data on hydration - faster booting with lazy loading
        $this->loadTenants();
    }

    #[On('refreshTenants')]
    public function loadTenants()
    {
        $cacheKey = 'tenants_list_v2_' . $this->filter . '_' . auth('admin')->id();
        
        $query = Tenant::whereValid()
            ->orderBy('id', 'desc');
            
        if ($this->filter === 'active') {
            $query->where('id', '>', 0);
        }
        
        // Use Redis cache for Octane - shared memory across workers
        $this->allTenants = Cache::store('redis')->remember($cacheKey, 120, function () use ($query) {
            $tenants = $query->get();
            $userIds = $tenants->pluck('id')->toArray();
            
            $users = User::whereIn('id', $userIds)
                ->select(['id', 'name', 'email'])
                ->get()
                ->keyBy('id');
            
            return $tenants->map(function ($tenant) use ($users) {
                try {
                    $user = $users->get($tenant->id);
                    
                    try {
                        $tenantInfo = \App\Helpers\TenantHelper\TenantHelpers::getTenantInfoForDisplay($tenant->id);
                    } catch (\Exception $e) {
                        $tenantInfo = [];
                    }
                    
                    return [
                        'id' => $tenant->id,
                        'tenant_id' => $tenant->id,
                        'user_name' => $user->name ?? 'N/A',
                        'user_email' => $user->email ?? 'N/A',
                        'tenant_info' => $tenantInfo ?? [],
                        'created_at' => $tenant->created_at,
                    ];
                } catch (\Exception $e) {
                    return [
                        'id' => $tenant->id,
                        'tenant_id' => $tenant->id,
                        'user_name' => 'N/A',
                        'user_email' => 'N/A',
                        'tenant_info' => [],
                        'created_at' => $tenant->created_at,
                    ];
                }
            })
            ->toArray();
        });
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        cache()->forget('tenants_list_v2_' . $this->filter . '_' . auth('admin')->id());
        $this->loadTenants();
    }

    public function render()
    {
        return view('livewire.landlord.admin.tenant-v2')
            ->layout('layouts.landlord.admin.v2.master', [
                'title' => 'Tenant Manage'
            ]);
    }
}
