<?php

namespace App\Livewire\Landlord\Admin;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\Admin;
use App\Models\User;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\Brand;
use App\Models\Testimonial;
use App\Models\UpdateInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Lazy]
class DashboardV2 extends Component
{
    public $totalAdmin = 0;
    public $totalUser = 0;
    public $allTenants = 0;
    public $totalPricePlan = 0;
    public $totalBrand = 0;
    public $totalTestimonial = 0;
    public $recentOrderLogs = [];
    public $updateInfo = [];

    public function mount()
    {
        // Only set title - data loading moved to hydrate() for faster booting
        $this->dispatch('setPageTitle', ['title' => 'Dashboard']);
    }

    public function hydrate()
    {
        // Load data on hydration - faster booting with lazy loading
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        try {
            // Use Redis cache for better performance with Octane
            $cacheKey = 'dashboard_stats_v2_' . auth('admin')->id();
            
            // Optimize cache - use Redis for shared memory across workers
            $data = Cache::store('redis')->remember($cacheKey, 180, function () {
                // Use select() to reduce memory and faster queries
                return [
                    'totalAdmin' => Admin::count(),
                    'totalUser' => User::count(),
                    'allTenants' => Tenant::whereValid()->count(),
                    'totalPricePlan' => PricePlan::count(),
                    'totalBrand' => Brand::count(),
                    'totalTestimonial' => Testimonial::count(),
                    'recentOrderLogs' => PaymentLogs::with(['user:id,name', 'package:id,title'])
                        ->select('id', 'user_id', 'package_id', 'total', 'payment_status', 'created_at')
                        ->orderBy('id', 'desc')
                        ->take(5)
                        ->get()
                        ->toArray(),
                    'updateInfo' => UpdateInfo::whereNull('read_at')
                        ->select('id', 'title', 'read_at', 'created_at')
                        ->limit(5)
                        ->get()
                        ->toArray(),
                ];
            });
            
            $this->totalAdmin = $data['totalAdmin'];
            $this->totalUser = $data['totalUser'];
            $this->allTenants = $data['allTenants'];
            $this->totalPricePlan = $data['totalPricePlan'];
            $this->totalBrand = $data['totalBrand'];
            $this->totalTestimonial = $data['totalTestimonial'];
            $this->recentOrderLogs = $data['recentOrderLogs'];
            $this->updateInfo = $data['updateInfo'];
        } catch (\Exception $e) {
            // Handle exceptions silently
        }
    }
    
    public function refreshDashboard()
    {
        // Clear Redis cache
        Cache::store('redis')->forget('dashboard_stats_v2_' . auth('admin')->id());
        $this->loadDashboardData();
        session()->flash('success', 'Dashboard Refreshed');
    }

    public function render()
    {
        return view('livewire.landlord.admin.dashboard-v2')
            ->layout('layouts.landlord.admin.v2.master', [
                'title' => 'Dashboard'
            ]);
    }
}
