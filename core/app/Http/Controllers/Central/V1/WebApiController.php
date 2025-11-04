<?php

namespace App\Http\Controllers\Central\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Tenant;
use App\Models\User;
use App\Models\PricePlan;
use App\Models\PaymentLogs;
use App\Models\Page;
use App\Models\Coupon;
use App\Models\CustomDomain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogTag;
use Modules\Blog\Entities\BlogComment;
use Modules\SupportTicket\Entities\SupportTicket;
use Modules\SupportTicket\Entities\SupportDepartment;
use App\Models\Menu;
use App\Models\Widgets;
use App\Models\Themes;
use App\Models\Language;
use App\Models\StaticOption;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;

/**
 * Web API Controller for Vue.js Dashboard
 * Uses the same data sources as Blade controllers but returns JSON
 */
class WebApiController extends Controller
{
    /**
     * Dashboard Stats
     */
    public function dashboardStats(): JsonResponse
    {
        $total_admin = Admin::count();
        $total_user = User::count();
        $all_tenants = Tenant::whereValid()->count();
        $total_price_plan = PricePlan::count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_admins' => $total_admin,
                'total_users' => $total_user,
                'total_tenants' => $all_tenants,
                'total_packages' => $total_price_plan,
            ]
        ]);
    }

    /**
     * Recent Orders
     */
    public function recentOrders(): JsonResponse
    {
        $recent_orders = PaymentLogs::with(['user', 'tenant'])
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->track ?? 'N/A',
                    'user_name' => $order->user->name ?? 'Unknown',
                    'tenant_id' => $order->tenant_id ?? null,
                    'package_name' => $order->package_name ?? 'N/A',
                    'amount' => $order->package_price ?? 0,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status ?? 'pending',
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $recent_orders
        ]);
    }

    /**
     * Chart Data for Dashboard
     */
    public function chartData(): JsonResponse
    {
        // Last 7 days revenue
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            
            $revenue = PaymentLogs::whereDate('created_at', $date->format('Y-m-d'))
                ->where('payment_status', 'complete')
                ->sum('package_price');
            
            $data[] = (float) $revenue;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Revenue',
                        'data' => $data,
                        'borderColor' => 'rgb(75, 192, 192)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    ]
                ]
            ]
        ]);
    }

    /**
     * Tenants List
     */
    public function tenants(Request $request): JsonResponse
    {
        $query = Tenant::with(['user', 'domains', 'payment_log'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereNotNull('user_id');
            } else {
                $query->whereNull('user_id');
            }
        }

        $tenants = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $tenants->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->user->name ?? $tenant->id,
                    'email' => $tenant->user->email ?? 'N/A',
                    'domain' => $tenant->domains->first()->domain ?? 'No domain',
                    'plan_name' => $tenant->payment_log->package_name ?? 'Free',
                    'status' => $tenant->user_id ? 'active' : 'inactive',
                    'created_at' => $tenant->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $tenants->currentPage(),
                'last_page' => $tenants->lastPage(),
                'per_page' => $tenants->perPage(),
                'total' => $tenants->total(),
            ]
        ]);
    }

    /**
     * Packages List
     */
    public function packages(Request $request): JsonResponse
    {
        $packages = PricePlan::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'title' => $package->title,
                    'price' => $package->price,
                    'type' => $package->type ?? 0,
                    'status' => $package->status ?? 1,
                    'features' => json_decode($package->features ?? '[]'),
                    'created_at' => $package->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $packages->currentPage(),
                'last_page' => $packages->lastPage(),
                'per_page' => $packages->perPage(),
                'total' => $packages->total(),
            ]
        ]);
    }

    /**
     * Orders List
     */
    public function orders(Request $request): JsonResponse
    {
        $orders = PaymentLogs::with(['user', 'tenant'])
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->track ?? 'N/A',
                    'user_name' => $order->user->name ?? 'Unknown',
                    'package_name' => $order->package_name ?? 'N/A',
                    'amount' => $order->package_price ?? 0,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status ?? 'pending',
                    'payment_gateway' => $order->payment_gateway ?? 'N/A',
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Single Order Details
     */
    public function orderDetails($id): JsonResponse
    {
        $order = PaymentLogs::with(['user', 'tenant'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_id' => $order->track ?? 'N/A',
                'user' => [
                    'id' => $order->user->id ?? null,
                    'name' => $order->user->name ?? 'Unknown',
                    'email' => $order->user->email ?? 'N/A',
                ],
                'tenant_id' => $order->tenant_id,
                'package_name' => $order->package_name ?? 'N/A',
                'package_price' => $order->package_price ?? 0,
                'status' => $order->status ?? 'pending',
                'payment_status' => $order->payment_status ?? 'pending',
                'payment_gateway' => $order->payment_gateway ?? 'N/A',
                'transaction_id' => $order->transaction_id ?? 'N/A',
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Admins List
     */
    public function admins(Request $request): JsonResponse
    {
        $admins = Admin::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $admins->map(function($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'username' => $admin->username,
                    'mobile' => $admin->mobile,
                    'image' => $admin->image,
                    'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $admins->currentPage(),
                'last_page' => $admins->lastPage(),
                'per_page' => $admins->perPage(),
                'total' => $admins->total(),
            ]
        ]);
    }

    /**
     * Payment Logs (same as orders but with different formatting)
     */
    public function payments(Request $request): JsonResponse
    {
        return $this->orders($request);
    }

    // ==========================================
    // BLOG MANAGEMENT
    // ==========================================

    /**
     * Blog Posts List
     */
    public function blogs(Request $request): JsonResponse
    {
        $blogs = Blog::with(['category'])
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $blogs->map(function($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'category' => $blog->category->name ?? 'Uncategorized',
                    'status' => $blog->status ?? 'draft',
                    'views' => $blog->views ?? 0,
                    'created_at' => $blog->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ]
        ]);
    }

    /**
     * Blog Categories List
     */
    public function blogCategories(Request $request): JsonResponse
    {
        $categories = BlogCategory::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'status' => $category->status ?? 1,
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ]
        ]);
    }

    // ==========================================
    // PAGES MANAGEMENT
    // ==========================================

    /**
     * Pages List
     */
    public function pages(Request $request): JsonResponse
    {
        $pages = Page::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $pages->map(function($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'status' => $page->status ?? 0,
                    'visibility' => $page->visibility ?? 0,
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $pages->currentPage(),
                'last_page' => $pages->lastPage(),
                'per_page' => $pages->perPage(),
                'total' => $pages->total(),
            ]
        ]);
    }

    // ==========================================
    // COUPONS MANAGEMENT
    // ==========================================

    /**
     * Coupons List
     */
    public function coupons(Request $request): JsonResponse
    {
        $coupons = Coupon::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $coupons->map(function($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount' => $coupon->discount,
                    'discount_type' => $coupon->discount_type,
                    'expire_date' => $coupon->expire_date,
                    'status' => $coupon->status ?? 1,
                    'created_at' => $coupon->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $coupons->currentPage(),
                'last_page' => $coupons->lastPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total(),
            ]
        ]);
    }

    // ==========================================
    // SUBSCRIPTIONS MANAGEMENT
    // ==========================================

    /**
     * All Subscribers (Users with active subscriptions)
     */
    public function subscribers(Request $request): JsonResponse
    {
        $users = User::with(['tenant'])
            ->whereHas('tenant')
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'tenant_id' => $user->tenant->id ?? null,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * All Stores (Tenants)
     */
    public function stores(Request $request): JsonResponse
    {
        return $this->tenants($request);
    }

    /**
     * Payment Histories
     */
    public function paymentHistories(Request $request): JsonResponse
    {
        return $this->orders($request);
    }

    /**
     * Custom Domains List
     */
    public function customDomains(Request $request): JsonResponse
    {
        $domains = CustomDomain::with(['tenant'])
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $domains->map(function($domain) {
                return [
                    'id' => $domain->id,
                    'custom_domain' => $domain->custom_domain,
                    'old_domain' => $domain->old_domain,
                    'status' => $domain->custom_domain_status ?? 'pending',
                    'tenant_id' => $domain->tenant_id,
                    'created_at' => $domain->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $domains->currentPage(),
                'last_page' => $domains->lastPage(),
                'per_page' => $domains->perPage(),
                'total' => $domains->total(),
            ]
        ]);
    }

    // ==========================================
    // SUPPORT TICKETS MANAGEMENT
    // ==========================================

    /**
     * Support Tickets List
     */
    public function supportTickets(Request $request): JsonResponse
    {
        $tickets = SupportTicket::with(['user'])
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $tickets->map(function($ticket) {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'subject' => $ticket->subject ?? $ticket->title,
                    'user_name' => $ticket->user->name ?? 'Unknown',
                    'status' => $ticket->status ?? 'open',
                    'priority' => $ticket->priority ?? 'medium',
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
            ]
        ]);
    }

    /**
     * Support Ticket Details
     */
    public function supportTicketDetails($id): JsonResponse
    {
        $ticket = SupportTicket::with(['user', 'messages'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'subject' => $ticket->subject ?? $ticket->title,
                'description' => $ticket->description,
                'user' => [
                    'id' => $ticket->user->id ?? null,
                    'name' => $ticket->user->name ?? 'Unknown',
                    'email' => $ticket->user->email ?? 'N/A',
                ],
                'status' => $ticket->status ?? 'open',
                'priority' => $ticket->priority ?? 'medium',
                'messages' => $ticket->messages ?? [],
                'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Support Departments
     */
    public function supportDepartments(Request $request): JsonResponse
    {
        $departments = SupportDepartment::orderBy('id', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $departments->map(function($dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'status' => $dept->status ?? 1,
                    'created_at' => $dept->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $departments->currentPage(),
                'last_page' => $departments->lastPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
            ]
        ]);
    }

    // ==========================================
    // MEDIA MANAGEMENT
    // ==========================================

    /**
     * Media Library
     */
    public function media(Request $request): JsonResponse
    {
        // This would typically use MediaUpload model
        // For now, return placeholder structure
        return response()->json([
            'success' => true,
            'data' => [],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 20,
                'total' => 0,
            ],
            'message' => 'Media API integration pending'
        ]);
    }
}


