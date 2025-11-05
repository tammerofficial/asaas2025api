<?php

namespace App\Http\Controllers\Central\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UsesApiCache;
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
use App\Models\SupportDepartment;
use App\Models\Menu;
use App\Models\Widgets;
use App\Models\Themes;
use App\Models\Language;
use App\Models\StaticOption;
use App\Models\PaymentGateway;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use App\Models\MediaUploader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Web API Controller for Vue.js Dashboard
 * Uses the same data sources as Blade controllers but returns JSON
 */
class WebApiController extends Controller
{
    use UsesApiCache;
    /**
     * Dashboard Stats
     */
    public function dashboardStats(): JsonResponse
    {
        $cacheKey = $this->getCacheKey('dashboard:stats');
        $ttl = $this->getStatsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            $total_admin = Admin::count();
            $total_user = User::count();
            $all_tenants = Tenant::whereValid()->count();
            $total_price_plan = PricePlan::count();
            
            return [
                'total_admins' => $total_admin,
                'total_users' => $total_user,
                'total_tenants' => $all_tenants,
                'total_packages' => $total_price_plan,
            ];
        }, ['tag:dashboard']);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Recent Orders
     */
    public function recentOrders(): JsonResponse
    {
        $cacheKey = $this->getCacheKey('dashboard:recent-orders');
        $ttl = $this->getRecentOrdersTtl();
        
        $recent_orders = $this->remember($cacheKey, $ttl, function () {
            return PaymentLogs::with(['user', 'tenant'])
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
        }, ['tag:dashboard', 'tag:orders']);

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
        $cacheKey = $this->getCacheKey('dashboard:chart-data');
        $ttl = $this->getChartDataTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            // Last 7 days revenue
            $labels = [];
            $revenueData = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                
                $revenue = PaymentLogs::whereDate('created_at', $date->format('Y-m-d'))
                    ->where('payment_status', 'complete')
                    ->sum('package_price');
                
                $revenueData[] = (float) $revenue;
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Revenue',
                        'data' => $revenueData,
                        'borderColor' => 'rgb(75, 192, 192)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    ]
                ]
            ];
        }, ['tag:dashboard']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Tenants List
     */
    public function tenants(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search', ''),
            'status' => $request->get('status', 'all'),
        ];
        
        $cacheKey = $this->getCacheKey('tenants', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
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

            return [
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
            ];
        }, ['tag:tenants']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Packages List
     */
    public function packages(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('packages', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $packages = PricePlan::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:packages']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Orders List
     */
    public function orders(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('orders', $params);
        $ttl = $this->getOrdersTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $orders = PaymentLogs::with(['user', 'tenant'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:orders']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Single Order Details
     */
    public function orderDetails($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("orders:{$id}");
        $ttl = $this->getOrderDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $order = PaymentLogs::with(['user', 'tenant'])->findOrFail($id);

            return [
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
            ];
        }, ['tag:orders']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Admins List
     */
    public function admins(Request $request): JsonResponse
    {
        try {
            $params = [
                'page' => $request->get('page', 1),
                'per_page' => $request->get('per_page', 20),
            ];
            
            $cacheKey = $this->getCacheKey('admins', $params);
            $ttl = $this->getListsTtl();
            
            $result = $this->remember($cacheKey, $ttl, function () use ($request) {
                $admins = Admin::orderBy('id', 'desc')
                    ->paginate($request->per_page ?? 20);

                return [
                    'data' => $admins->map(function($admin) {
                        return [
                            'id' => $admin->id,
                            'name' => $admin->name ?? '',
                            'email' => $admin->email ?? '',
                            'username' => $admin->username ?? '',
                            'mobile' => $admin->mobile ?? '',
                            'image' => $admin->image ?? null,
                            'created_at' => $admin->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
                        ];
                    }),
                    'meta' => [
                        'current_page' => $admins->currentPage(),
                        'last_page' => $admins->lastPage(),
                        'per_page' => $admins->perPage(),
                        'total' => $admins->total(),
                    ]
                ];
            }, ['tag:admins']);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'meta' => $result['meta']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admins',
                'error' => $e->getMessage(),
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 20,
                    'total' => 0,
                ]
            ], 500);
        }
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('blogs', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $blogs = Blog::with(['category'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $blogs->map(function($blog) {
                    // Normalize status: 1 = 'publish', 0 = 'draft', or use string value
                    $status = $blog->status;
                    if (is_numeric($status)) {
                        $status = $status == 1 ? 'publish' : 'draft';
                    } else {
                        $status = $status ?? 'draft';
                    }
                    
                    return [
                        'id' => $blog->id,
                        'title' => $blog->title,
                        'slug' => $blog->slug,
                        'category' => $blog->category->name ?? 'Uncategorized',
                        'status' => $status,
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
            ];
        }, ['tag:blogs']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Blog Categories List
     */
    public function blogCategories(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('blogs:categories', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $categories = BlogCategory::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:blogs']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('pages', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $pages = Page::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:pages']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('coupons', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $coupons = Coupon::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:coupons']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('subscribers', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $users = User::with(['tenant'])
                ->whereHas('tenant')
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:tenants']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('custom-domains', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $domains = CustomDomain::with(['tenant'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:tenants']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
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
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('support:tickets', $params);
        $ttl = $this->getSupportTicketsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $tickets = SupportTicket::with(['user'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:support']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Support Ticket Details
     */
    public function supportTicketDetails($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("support:tickets:{$id}");
        $ttl = $this->getSupportTicketDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $ticket = SupportTicket::with(['user', 'messages'])->findOrFail($id);

            return [
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
            ];
        }, ['tag:support']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Support Departments
     */
    public function supportDepartments(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('support:departments', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $departments = SupportDepartment::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
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
            ];
        }, ['tag:support']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // SUPPORT TICKET CATEGORIES CRUD
    // ==========================================

    /**
     * Support Ticket Categories List
     */
    public function supportTicketCategories(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];
        
        $cacheKey = $this->getCacheKey('support:ticket-categories', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $query = SupportDepartment::orderBy('id', 'desc');
            
            if ($request->has('search')) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $categories = $query->paginate($request->per_page ?? 20);

            return [
                'data' => $categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'status' => $category->status ?? 1,
                        'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                ]
            ];
        }, ['tag:support']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Create Support Ticket Category
     */
    public function storeSupportTicketCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $category = SupportDepartment::create([
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket category created successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
                'created_at' => $category->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    /**
     * Get Single Support Ticket Category
     */
    public function getSupportTicketCategory($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("support:ticket-categories:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $category = SupportDepartment::findOrFail($id);

            return [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status ?? 1,
                'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:support']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Support Ticket Category
     */
    public function updateSupportTicketCategory(Request $request, $id): JsonResponse
    {
        $category = SupportDepartment::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $category->update([
            'name' => $request->name ?? $category->name,
            'status' => $request->has('status') ? $request->status : $category->status,
        ]);

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket category updated successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
                'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Delete Support Ticket Category
     */
    public function deleteSupportTicketCategory($id): JsonResponse
    {
        $category = SupportDepartment::findOrFail($id);
        $category->delete();

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket category deleted successfully'
        ]);
    }

    // ==========================================
    // PAYMENT METHODS CRUD
    // ==========================================

    /**
     * Payment Methods List
     */
    public function paymentMethods(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];
        
        $cacheKey = $this->getCacheKey('payment:methods', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $query = PaymentGateway::orderBy('id', 'desc');
            
            if ($request->has('search')) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $methods = $query->paginate($request->per_page ?? 20);

            return [
                'data' => $methods->map(function($method) {
                    return [
                        'id' => $method->id,
                        'name' => $method->name,
                        'image' => $method->image,
                        'description' => $method->description,
                        'status' => $method->status ?? 0,
                        'test_mode' => $method->test_mode ?? 0,
                        'credentials' => json_decode($method->credentials ?? '{}', true),
                        'created_at' => $method->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $method->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $methods->currentPage(),
                    'last_page' => $methods->lastPage(),
                    'per_page' => $methods->perPage(),
                    'total' => $methods->total(),
                ]
            ];
        }, ['tag:payments']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Create Payment Method
     */
    public function storePaymentMethod(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1',
            'test_mode' => 'nullable|integer|in:0,1',
            'credentials' => 'nullable|array',
        ]);

        $method = PaymentGateway::create([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'status' => $request->status ?? 0,
            'test_mode' => $request->test_mode ?? 0,
            'credentials' => json_encode($request->credentials ?? []),
        ]);

        // Clear cache
        $this->clearCacheTags(['tag:payments']);

        return response()->json([
            'success' => true,
            'message' => 'Payment method created successfully',
            'data' => [
                'id' => $method->id,
                'name' => $method->name,
                'status' => $method->status,
                'created_at' => $method->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    /**
     * Get Single Payment Method
     */
    public function getPaymentMethod($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("payment:methods:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $method = PaymentGateway::findOrFail($id);

            return [
                'id' => $method->id,
                'name' => $method->name,
                'image' => $method->image,
                'description' => $method->description,
                'status' => $method->status ?? 0,
                'test_mode' => $method->test_mode ?? 0,
                'credentials' => json_decode($method->credentials ?? '{}', true),
                'created_at' => $method->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $method->updated_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:payments']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Payment Method
     */
    public function updatePaymentMethod(Request $request, $id): JsonResponse
    {
        $method = PaymentGateway::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1',
            'test_mode' => 'nullable|integer|in:0,1',
            'credentials' => 'nullable|array',
        ]);

        $method->update([
            'name' => $request->name ?? $method->name,
            'image' => $request->has('image') ? $request->image : $method->image,
            'description' => $request->has('description') ? $request->description : $method->description,
            'status' => $request->has('status') ? $request->status : $method->status,
            'test_mode' => $request->has('test_mode') ? $request->test_mode : $method->test_mode,
            'credentials' => $request->has('credentials') ? json_encode($request->credentials) : $method->credentials,
        ]);

        // Clear cache
        $this->clearCacheTags(['tag:payments']);

        return response()->json([
            'success' => true,
            'message' => 'Payment method updated successfully',
            'data' => [
                'id' => $method->id,
                'name' => $method->name,
                'status' => $method->status,
                'updated_at' => $method->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Delete Payment Method
     */
    public function deletePaymentMethod($id): JsonResponse
    {
        $method = PaymentGateway::findOrFail($id);
        $method->delete();

        // Clear cache
        $this->clearCacheTags(['tag:payments']);

        return response()->json([
            'success' => true,
            'message' => 'Payment method deleted successfully'
        ]);
    }

    // ==========================================
    // PAYMENT SETTINGS MANAGEMENT
    // ==========================================

    /**
     * Payment General Settings
     */
    public function paymentSettings(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('payment:settings');
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            $settings = [
                'site_global_currency' => get_static_option('site_global_currency', 'USD'),
                'site_currency_symbol_position' => get_static_option('site_currency_symbol_position', 'left'),
                'site_default_payment_gateway' => get_static_option('site_default_payment_gateway', ''),
                'site_global_payment_gateway' => get_static_option('site_global_payment_gateway', ''),
                'currency_amount_type_status' => get_static_option('currency_amount_type_status', ''),
                'site_custom_currency_symbol' => get_static_option('site_custom_currency_symbol', ''),
                'site_custom_currency_thousand_separator' => get_static_option('site_custom_currency_thousand_separator', ','),
                'site_custom_currency_decimal_separator' => get_static_option('site_custom_currency_decimal_separator', '.'),
                'cash_on_delivery' => get_static_option('cash_on_delivery', ''),
            ];
            
            return $settings;
        }, ['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Payment Settings
     */
    public function updatePaymentSettings(Request $request): JsonResponse
    {
        $request->validate([
            'site_global_currency' => 'nullable|string|max:191',
            'site_currency_symbol_position' => 'nullable|string|max:191',
            'site_default_payment_gateway' => 'nullable|string|max:191',
            'site_global_payment_gateway' => 'nullable|string|max:191',
            'currency_amount_type_status' => 'nullable|string',
            'site_custom_currency_symbol' => 'nullable|string|max:191',
            'site_custom_currency_thousand_separator' => 'nullable|string|max:191',
            'site_custom_currency_decimal_separator' => 'nullable|string|max:191',
            'cash_on_delivery' => 'nullable|string',
        ]);

        $settings = [
            'site_global_currency',
            'site_currency_symbol_position',
            'site_default_payment_gateway',
            'site_global_payment_gateway',
            'currency_amount_type_status',
            'site_custom_currency_symbol',
            'site_custom_currency_thousand_separator',
            'site_custom_currency_decimal_separator',
            'cash_on_delivery',
        ];

        foreach ($settings as $key) {
            if ($request->has($key)) {
                update_static_option($key, $request->$key);
            }
        }

        // Clear cache
        $this->clearCacheTags(['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'message' => 'Payment settings updated successfully'
        ]);
    }

    /**
     * Payment Currencies
     */
    public function currencies(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('payment:currencies');
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            // Common currencies list
            $currencies = [
                ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
                ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
                ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
                ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك'],
                ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س'],
                ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
                ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => '.د.ب'],
                ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
                ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق'],
                ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
            ];
            
            // Get current global currency
            $globalCurrency = get_static_option('site_global_currency', 'KWD');
            
            return [
                'currencies' => $currencies,
                'current_currency' => $globalCurrency,
            ];
        }, ['tag:payments']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Payment SAAS Settings
     */
    public function saasSettings(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('payment:saas-settings');
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            $settings = [
                'saas_enabled' => get_static_option('saas_enabled', 'on'),
                'saas_commission_type' => get_static_option('saas_commission_type', 'percentage'),
                'saas_commission_amount' => get_static_option('saas_commission_amount', '0'),
                'saas_trial_period' => get_static_option('saas_trial_period', '0'),
                'saas_auto_renewal' => get_static_option('saas_auto_renewal', 'on'),
            ];
            
            return $settings;
        }, ['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update SAAS Settings
     */
    public function updateSaasSettings(Request $request): JsonResponse
    {
        $request->validate([
            'saas_enabled' => 'nullable|string|in:on,off',
            'saas_commission_type' => 'nullable|string|in:percentage,fixed',
            'saas_commission_amount' => 'nullable|numeric|min:0',
            'saas_trial_period' => 'nullable|integer|min:0',
            'saas_auto_renewal' => 'nullable|string|in:on,off',
        ]);

        $settings = [
            'saas_enabled',
            'saas_commission_type',
            'saas_commission_amount',
            'saas_trial_period',
            'saas_auto_renewal',
        ];

        foreach ($settings as $key) {
            if ($request->has($key)) {
                update_static_option($key, $request->$key);
            }
        }

        // Clear cache
        $this->clearCacheTags(['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'message' => 'SAAS settings updated successfully'
        ]);
    }

    /**
     * Payment Notifications
     */
    public function paymentNotifications(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('payment:notifications');
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () {
            $settings = [
                'payment_email_notification' => get_static_option('payment_email_notification', 'on'),
                'payment_sms_notification' => get_static_option('payment_sms_notification', 'off'),
                'payment_admin_email' => get_static_option('payment_admin_email', ''),
                'payment_success_template' => get_static_option('payment_success_template', ''),
                'payment_failed_template' => get_static_option('payment_failed_template', ''),
            ];
            
            return $settings;
        }, ['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Payment Notifications
     */
    public function updatePaymentNotifications(Request $request): JsonResponse
    {
        $request->validate([
            'payment_email_notification' => 'nullable|string|in:on,off',
            'payment_sms_notification' => 'nullable|string|in:on,off',
            'payment_admin_email' => 'nullable|email',
            'payment_success_template' => 'nullable|string',
            'payment_failed_template' => 'nullable|string',
        ]);

        $settings = [
            'payment_email_notification',
            'payment_sms_notification',
            'payment_admin_email',
            'payment_success_template',
            'payment_failed_template',
        ];

        foreach ($settings as $key) {
            if ($request->has($key)) {
                update_static_option($key, $request->$key);
            }
        }

        // Clear cache
        $this->clearCacheTags(['tag:payments', 'tag:settings']);

        return response()->json([
            'success' => true,
            'message' => 'Payment notification settings updated successfully'
        ]);
    }

    // ==========================================
    // MEDIA MANAGEMENT
    // ==========================================

    /**
     * Media Library - List
     */
    public function media(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search', ''),
        ];
        
        $cacheKey = $this->getCacheKey('media', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $query = MediaUploader::where('load_from', 'central')
                ->orderBy('id', 'desc');
            
            // Search filter
            if ($request->has('search') && !empty($request->get('search'))) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('alt', 'LIKE', "%{$search}%");
                });
            }
            
            $media = $query->paginate($request->get('per_page', 20));
            
            return [
                'data' => $media->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'alt' => $item->alt,
                        'size' => $item->size,
                        'path' => $item->path,
                        'url' => Storage::disk('public')->url($item->path),
                        'dimensions' => $item->dimensions,
                        'user_type' => $item->user_type,
                        'user_id' => $item->user_id,
                        'load_from' => $item->load_from,
                        'is_synced' => $item->is_synced,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $media->currentPage(),
                    'last_page' => $media->lastPage(),
                    'per_page' => $media->perPage(),
                    'total' => $media->total(),
                ]
            ];
        }, ['tag:media']);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }
    
    /**
     * Get Single Media
     */
    public function getMedia($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('media', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $media = MediaUploader::where('id', $id)
                ->where('load_from', 'central')
                ->firstOrFail();
            
            return [
                'id' => $media->id,
                'title' => $media->title,
                'alt' => $media->alt,
                'size' => $media->size,
                'path' => $media->path,
                'url' => Storage::disk('public')->url($media->path),
                'dimensions' => $media->dimensions,
                'user_type' => $media->user_type,
                'user_id' => $media->user_id,
                'load_from' => $media->load_from,
                'is_synced' => $media->is_synced,
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $media->updated_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:media']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Upload Media File
     */
    public function storeMedia(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'title' => 'nullable|string|max:255',
            'alt' => 'nullable|string|max:255',
        ]);
        
        $file = $request->file('file');
        $user = auth('admin')->user();
        
        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file upload',
            ], 422);
        }
        
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('central/uploads', $filename, 'public');
        
        // Get file dimensions for images
        $dimensions = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imagePath = Storage::disk('public')->path($path);
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo) {
                $dimensions = $imageInfo[0] . 'x' . $imageInfo[1];
            }
        }
        
        // Create media record
        $media = MediaUploader::create([
            'title' => $request->input('title', $file->getClientOriginalName()),
            'alt' => $request->input('alt', $file->getClientOriginalName()),
            'size' => $file->getSize(),
            'path' => $path,
            'dimensions' => $dimensions,
            'user_type' => 'admin',
            'user_id' => $user?->id,
            'load_from' => 'central',
            'is_synced' => 0,
        ]);
        
        // Clear cache
        $this->clearCacheTags(['tag:media']);
        
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'alt' => $media->alt,
                'size' => $media->size,
                'path' => $media->path,
                'url' => Storage::disk('public')->url($media->path),
                'dimensions' => $media->dimensions,
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Media Metadata
     */
    public function updateMedia(Request $request, $id): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'alt' => 'nullable|string|max:255',
        ]);
        
        $media = MediaUploader::where('id', $id)
            ->where('load_from', 'central')
            ->firstOrFail();
        
        $media->update($request->only(['title', 'alt']));
        
        // Clear cache
        $this->clearCacheTags(['tag:media']);
        
        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'alt' => $media->alt,
                'size' => $media->size,
                'path' => $media->path,
                'url' => Storage::disk('public')->url($media->path),
                'dimensions' => $media->dimensions,
                'updated_at' => $media->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Delete Media
     */
    public function deleteMedia($id): JsonResponse
    {
        $media = MediaUploader::where('id', $id)
            ->where('load_from', 'central')
            ->firstOrFail();
        
        // Delete physical file
        if ($media->path && Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        
        $media->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:media']);
        
        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }
    
    /**
     * Bulk Delete Media
     */
    public function bulkDeleteMedia(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:media_uploaders,id',
        ]);
        
        $mediaFiles = MediaUploader::whereIn('id', $request->ids)
            ->where('load_from', 'central')
            ->get();
        
        $deletedCount = 0;
        foreach ($mediaFiles as $media) {
            // Delete physical file
            if ($media->path && Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
            $deletedCount++;
        }
        
        // Clear cache
        $this->clearCacheTags(['tag:media']);
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} media file(s)",
            'deleted_count' => $deletedCount,
        ]);
    }

    // ==========================================
    // FULL CRUD METHODS
    // ==========================================

    // ==========================================
    // BLOG CRUD
    // ==========================================

    /**
     * Store Blog
     */
    public function storeBlog(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'blog_content' => 'nullable|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'nullable|in:publish,draft',
        ]);

        $blog = Blog::create($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:blogs']);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'data' => $blog
        ], 201);
    }

    /**
     * Get Single Blog
     */
    public function getBlog($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("blogs:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $blog = Blog::with(['category'])->findOrFail($id);

            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'blog_content' => $blog->blog_content,
                'category' => $blog->category->name ?? 'Uncategorized',
                'status' => $blog->status ?? 'draft',
                'views' => $blog->views ?? 0,
                'created_at' => $blog->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:blogs']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Blog
     */
    public function updateBlog(Request $request, $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:blogs,slug,' . $id,
            'blog_content' => 'nullable|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'nullable|in:publish,draft',
        ]);

        $blog->update($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:blogs']);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog
        ]);
    }

    /**
     * Delete Blog
     */
    public function deleteBlog($id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        // Clear cache
        $this->clearCacheTags(['tag:blogs']);

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully'
        ]);
    }

    /**
     * Blog Tags List
     */
    public function blogTags(Request $request): JsonResponse
    {
        try {
            $params = [
                'page' => $request->get('page', 1),
                'per_page' => $request->get('per_page', 20),
            ];
            
            $cacheKey = $this->getCacheKey('blogs:tags', $params);
            $ttl = $this->getListsTtl();
            
            $result = $this->remember($cacheKey, $ttl, function () use ($request) {
                $tags = BlogTag::orderBy('id', 'desc')
                    ->paginate($request->per_page ?? 20);

                return [
                    'data' => $tags->map(function($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name ?? $tag->tag_text ?? 'Untitled',
                            'slug' => $tag->slug ?? '',
                            'created_at' => $tag->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
                        ];
                    }),
                    'meta' => [
                        'current_page' => $tags->currentPage(),
                        'last_page' => $tags->lastPage(),
                        'per_page' => $tags->perPage(),
                        'total' => $tags->total(),
                    ]
                ];
            }, ['tag:blogs']);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'meta' => $result['meta']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog tags',
                'error' => $e->getMessage(),
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 20,
                    'total' => 0,
                ]
            ], 500);
        }
    }

    /**
     * Blog Comments List
     */
    public function blogComments(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('blogs:comments', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $comments = BlogComment::with(['blog', 'user'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'blog_title' => $comment->blog->title ?? 'N/A',
                        'user_name' => $comment->user->name ?? 'Anonymous',
                        'comment' => $comment->comment,
                        'status' => $comment->status ?? 1,
                        'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ]
            ];
        }, ['tag:blogs']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // PAGES CRUD
    // ==========================================

    /**
     * Store Page
     */
    public function storePage(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'page_content' => 'nullable|string',
            'status' => 'nullable|integer',
            'visibility' => 'nullable|integer',
        ]);

        $page = Page::create($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:pages']);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'data' => $page
        ], 201);
    }

    /**
     * Get Single Page
     */
    public function getPage($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("pages:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $page = Page::findOrFail($id);

            return [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'page_content' => $page->page_content,
                'status' => $page->status ?? 0,
                'visibility' => $page->visibility ?? 0,
                'created_at' => $page->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:pages']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Page
     */
    public function updatePage(Request $request, $id): JsonResponse
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:pages,slug,' . $id,
            'page_content' => 'nullable|string',
            'status' => 'nullable|integer',
            'visibility' => 'nullable|integer',
        ]);

        $page->update($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:pages']);

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully',
            'data' => $page
        ]);
    }

    /**
     * Delete Page
     */
    public function deletePage($id): JsonResponse
    {
        $page = Page::findOrFail($id);
        $page->delete();

        // Clear cache
        $this->clearCacheTags(['tag:pages']);

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully'
        ]);
    }

    // ==========================================
    // PACKAGES CRUD
    // ==========================================

    /**
     * Store Package
     */
    public function storePackage(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'nullable|integer',
            'status' => 'nullable|integer',
            'features' => 'nullable|array',
        ]);

        $package = PricePlan::create($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:packages']);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    /**
     * Get Single Package
     */
    public function getPackage($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("packages:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $package = PricePlan::findOrFail($id);

            return [
                'id' => $package->id,
                'title' => $package->title,
                'price' => $package->price,
                'type' => $package->type ?? 0,
                'status' => $package->status ?? 1,
                'features' => json_decode($package->features ?? '[]'),
                'created_at' => $package->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:packages']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Package
     */
    public function updatePackage(Request $request, $id): JsonResponse
    {
        $package = PricePlan::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'type' => 'nullable|integer',
            'status' => 'nullable|integer',
            'features' => 'nullable|array',
        ]);

        $package->update($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:packages']);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
            'data' => $package
        ]);
    }

    /**
     * Delete Package
     */
    public function deletePackage($id): JsonResponse
    {
        $package = PricePlan::findOrFail($id);
        $package->delete();

        // Clear cache
        $this->clearCacheTags(['tag:packages']);

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }

    // ==========================================
    // COUPONS CRUD
    // ==========================================

    /**
     * Store Coupon
     */
    public function storeCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'expire_date' => 'nullable|date',
            'status' => 'nullable|integer',
        ]);

        $coupon = Coupon::create($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:coupons']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ], 201);
    }

    /**
     * Get Single Coupon
     */
    public function getCoupon($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("coupons:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $coupon = Coupon::findOrFail($id);

            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
                'expire_date' => $coupon->expire_date,
                'status' => $coupon->status ?? 1,
                'created_at' => $coupon->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:coupons']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Coupon
     */
    public function updateCoupon(Request $request, $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'sometimes|required|string|max:255|unique:coupons,code,' . $id,
            'discount' => 'sometimes|required|numeric|min:0',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'expire_date' => 'nullable|date',
            'status' => 'nullable|integer',
        ]);

        $coupon->update($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:coupons']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully',
            'data' => $coupon
        ]);
    }

    /**
     * Delete Coupon
     */
    public function deleteCoupon($id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        // Clear cache
        $this->clearCacheTags(['tag:coupons']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully'
        ]);
    }

    // ==========================================
    // TENANTS CRUD
    // ==========================================

    /**
     * Store Tenant
     */
    public function storeTenant(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|string|max:255|unique:tenants,id',
        ]);

        // Tenant creation logic would go here
        // This is a simplified version
        
        // Clear cache if tenant is created
        // $this->clearCacheTags(['tag:tenants']);
        
        return response()->json([
            'success' => false,
            'message' => 'Tenant creation via API not yet implemented'
        ], 400);
    }

    /**
     * Get Single Tenant
     */
    public function getTenant($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("tenants:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $tenant = Tenant::with(['user', 'domains', 'payment_log'])->findOrFail($id);

            return [
                'id' => $tenant->id,
                'name' => $tenant->user->name ?? $tenant->id,
                'email' => $tenant->user->email ?? 'N/A',
                'domain' => $tenant->domains->first()->domain ?? 'No domain',
                'plan_name' => $tenant->payment_log->package_name ?? 'Free',
                'status' => $tenant->user_id ? 'active' : 'inactive',
                'created_at' => $tenant->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:tenants']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Tenant
     */
    public function updateTenant(Request $request, $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        
        // Update logic would go here
        // This is a simplified version
        
        // Clear cache if tenant is updated
        // $this->clearCacheTags(['tag:tenants']);
        
        return response()->json([
            'success' => false,
            'message' => 'Tenant update via API not yet implemented'
        ], 400);
    }

    /**
     * Delete Tenant
     */
    public function deleteTenant($id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        // Delete logic would go here
        // This is a simplified version
        
        // Clear cache if tenant is deleted
        // $this->clearCacheTags(['tag:tenants']);
        
        return response()->json([
            'success' => false,
            'message' => 'Tenant deletion via API not yet implemented'
        ], 400);
    }

    // ==========================================
    // ADMINS CRUD
    // ==========================================

    /**
     * Store Admin
     */
    public function storeAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:8',
            'mobile' => 'nullable|string',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
        ]);

        // Clear cache
        $this->clearCacheTags(['tag:admins']);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => $admin
        ], 201);
    }

    /**
     * Get Single Admin
     */
    public function getAdmin($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey("admins:{$id}");
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($id) {
            $admin = Admin::findOrFail($id);

            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'username' => $admin->username,
                'mobile' => $admin->mobile,
                'image' => $admin->image,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:admins']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Admin
     */
    public function updateAdmin(Request $request, $id): JsonResponse
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:admins,email,' . $id,
            'username' => 'sometimes|required|string|max:255|unique:admins,username,' . $id,
            'password' => 'nullable|string|min:8',
            'mobile' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'username', 'mobile']);
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        // Clear cache
        $this->clearCacheTags(['tag:admins']);

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully',
            'data' => $admin
        ]);
    }

    /**
     * Delete Admin
     */
    public function deleteAdmin($id): JsonResponse
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        // Clear cache
        $this->clearCacheTags(['tag:admins']);

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully'
        ]);
    }

    // ==========================================
    // SUPPORT TICKETS CRUD
    // ==========================================

    /**
     * Store Support Ticket
     */
    public function storeSupportTicket(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'description' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:open,pending,closed',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $ticket = SupportTicket::create($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    /**
     * Update Support Ticket
     */
    public function updateSupportTicket(Request $request, $id): JsonResponse
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,pending,closed',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $ticket->update($request->all());

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket updated successfully',
            'data' => $ticket
        ]);
    }

    /**
     * Delete Support Ticket
     */
    public function deleteSupportTicket($id): JsonResponse
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        // Clear cache
        $this->clearCacheTags(['tag:support']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket deleted successfully'
        ]);
    }

    // ==========================================
    // USERS MANAGEMENT
    // ==========================================

    /**
     * Users List
     */
    public function users(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('users', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $users = User::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ];
        }, ['tag:users']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Roles List
     */
    public function roles(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('roles', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $roles = Role::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $roles->map(function($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                        'created_at' => $role->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total(),
                ]
            ];
        }, ['tag:roles']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Permissions List
     */
    public function permissions(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('permissions', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $permissions = Permission::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $permissions->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                        'created_at' => $permission->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $permissions->currentPage(),
                    'last_page' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                ]
            ];
        }, ['tag:roles']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Activity Logs
     */
    public function activityLogs(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        // Activity logs change frequently, use shorter TTL
        $cacheKey = $this->getCacheKey('activity:logs', $params);
        $ttl = 60; // 1 minute - shorter because logs change frequently
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $logs = Activity::with(['causer'])
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $logs->map(function($log) {
                    return [
                        'id' => $log->id,
                        'description' => $log->description,
                        'subject_type' => $log->subject_type,
                        'causer' => $log->causer->name ?? 'System',
                        'properties' => $log->properties,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                ]
            ];
        }, ['tag:activity']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // APPEARANCES MANAGEMENT
    // ==========================================

    /**
     * Themes List
     */
    public function themes(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('themes', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $themes = Themes::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $themes->map(function($theme) {
                    return [
                        'id' => $theme->id,
                        'name' => $theme->name,
                        'status' => $theme->status ?? 0,
                        'created_at' => $theme->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $themes->currentPage(),
                    'last_page' => $themes->lastPage(),
                    'per_page' => $themes->perPage(),
                    'total' => $themes->total(),
                ]
            ];
        }, ['tag:appearance']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Menus List
     */
    public function menus(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('menus', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $menus = Menu::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $menus->map(function($menu) {
                    return [
                        'id' => $menu->id,
                        'title' => $menu->title,
                        'status' => $menu->status ?? 1,
                        'created_at' => $menu->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $menus->currentPage(),
                    'last_page' => $menus->lastPage(),
                    'per_page' => $menus->perPage(),
                    'total' => $menus->total(),
                ]
            ];
        }, ['tag:appearance']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    /**
     * Widgets List
     */
    public function widgets(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('widgets', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $widgets = Widgets::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $widgets->map(function($widget) {
                    return [
                        'id' => $widget->id,
                        'widget_name' => $widget->widget_name,
                        'widget_location' => $widget->widget_location,
                        'status' => $widget->status ?? 1,
                        'created_at' => $widget->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $widgets->currentPage(),
                    'last_page' => $widgets->lastPage(),
                    'per_page' => $widgets->perPage(),
                    'total' => $widgets->total(),
                ]
            ];
        }, ['tag:appearance']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // SETTINGS MANAGEMENT
    // ==========================================

    /**
     * Get Settings
     */
    public function getSettings(Request $request): JsonResponse
    {
        $params = [
            'key' => $request->get('key'),
        ];
        
        $cacheKey = $this->getCacheKey('settings', $params);
        $ttl = $this->getDetailsTtl();
        
        $data = $this->remember($cacheKey, $ttl, function () use ($request) {
            return StaticOption::when($request->has('key'), function($query) use ($request) {
                return $query->where('option_name', $request->key);
            })
            ->get()
            ->mapWithKeys(function($setting) {
                return [$setting->option_name => $setting->option_value];
            });
        }, ['tag:settings']);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update Settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            StaticOption::updateOrCreate(
                ['option_name' => $key],
                ['option_value' => $value]
            );
        }

        // Clear cache
        $this->clearCacheTags(['tag:settings']);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    // ==========================================
    // SYSTEM MANAGEMENT
    // ==========================================

    /**
     * Languages List
     */
    public function languages(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('languages', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $languages = Language::orderBy('id', 'desc')
                ->paginate($request->per_page ?? 20);

            return [
                'data' => $languages->map(function($language) {
                    return [
                        'id' => $language->id,
                        'name' => $language->name,
                        'slug' => $language->slug,
                        'default' => $language->default ?? 0,
                        'status' => $language->status ?? 1,
                        'created_at' => $language->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $languages->currentPage(),
                    'last_page' => $languages->lastPage(),
                    'per_page' => $languages->perPage(),
                    'total' => $languages->total(),
                ]
            ];
        }, ['tag:settings']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // PLUGINS MANAGEMENT
    // ==========================================

    /**
     * Plugins List
     */
    public function plugins(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search', ''),
        ];
        
        $cacheKey = $this->getCacheKey('plugins', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            // Get plugins from StaticOption
            $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
            $pluginsData = $pluginsOption ? json_decode($pluginsOption->option_value, true) : [];
            
            // Filter by search
            if ($request->has('search') && !empty($request->get('search'))) {
                $search = $request->get('search');
                $pluginsData = array_filter($pluginsData, function($plugin) use ($search) {
                    return stripos($plugin['name'] ?? '', $search) !== false || 
                           stripos($plugin['description'] ?? '', $search) !== false;
                });
            }
            
            // Paginate manually
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);
            $total = count($pluginsData);
            $offset = ($page - 1) * $perPage;
            $items = array_slice($pluginsData, $offset, $perPage);
            
            return [
                'data' => array_values($items),
                'meta' => [
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                ]
            ];
        }, ['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }
    
    /**
     * Get Single Plugin
     */
    public function getPlugin($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('plugin', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
            $pluginsData = $pluginsOption ? json_decode($pluginsOption->option_value, true) : [];
            
            $plugin = collect($pluginsData)->firstWhere('id', $id);
            
            if (!$plugin) {
                abort(404, 'Plugin not found');
            }
            
            return $plugin;
        }, ['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Plugin (Install)
     */
    public function storePlugin(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
        ]);
        
        $pluginsOption = StaticOption::firstOrCreate(
            ['option_name' => 'plugins_list'],
            ['option_value' => json_encode([])]
        );
        
        $pluginsData = json_decode($pluginsOption->option_value, true) ?: [];
        
        $newPlugin = [
            'id' => count($pluginsData) + 1,
            'name' => $request->name,
            'description' => $request->description ?? '',
            'version' => $request->version ?? '1.0.0',
            'author' => $request->author ?? 'System',
            'is_active' => false,
            'installed_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        $pluginsData[] = $newPlugin;
        $pluginsOption->update(['option_value' => json_encode($pluginsData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'message' => 'Plugin installed successfully',
            'data' => $newPlugin
        ], 201);
    }
    
    /**
     * Update Plugin
     */
    public function updatePlugin(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
        ]);
        
        $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
        if (!$pluginsOption) {
            abort(404, 'Plugins list not found');
        }
        
        $pluginsData = json_decode($pluginsOption->option_value, true) ?: [];
        
        $index = array_search($id, array_column($pluginsData, 'id'));
        if ($index === false) {
            abort(404, 'Plugin not found');
        }
        
        $pluginsData[$index] = array_merge($pluginsData[$index], $request->only(['name', 'description', 'version', 'author']));
        $pluginsOption->update(['option_value' => json_encode($pluginsData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'message' => 'Plugin updated successfully',
            'data' => $pluginsData[$index]
        ]);
    }
    
    /**
     * Activate Plugin
     */
    public function activatePlugin($id): JsonResponse
    {
        $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
        if (!$pluginsOption) {
            abort(404, 'Plugins list not found');
        }
        
        $pluginsData = json_decode($pluginsOption->option_value, true) ?: [];
        
        $index = array_search($id, array_column($pluginsData, 'id'));
        if ($index === false) {
            abort(404, 'Plugin not found');
        }
        
        $pluginsData[$index]['is_active'] = true;
        $pluginsOption->update(['option_value' => json_encode($pluginsData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'message' => 'Plugin activated successfully',
            'data' => $pluginsData[$index]
        ]);
    }
    
    /**
     * Deactivate Plugin
     */
    public function deactivatePlugin($id): JsonResponse
    {
        $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
        if (!$pluginsOption) {
            abort(404, 'Plugins list not found');
        }
        
        $pluginsData = json_decode($pluginsOption->option_value, true) ?: [];
        
        $index = array_search($id, array_column($pluginsData, 'id'));
        if ($index === false) {
            abort(404, 'Plugin not found');
        }
        
        $pluginsData[$index]['is_active'] = false;
        $pluginsOption->update(['option_value' => json_encode($pluginsData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'message' => 'Plugin deactivated successfully',
            'data' => $pluginsData[$index]
        ]);
    }
    
    /**
     * Delete Plugin (Uninstall)
     */
    public function deletePlugin($id): JsonResponse
    {
        $pluginsOption = StaticOption::where('option_name', 'plugins_list')->first();
        if (!$pluginsOption) {
            abort(404, 'Plugins list not found');
        }
        
        $pluginsData = json_decode($pluginsOption->option_value, true) ?: [];
        
        $pluginsData = array_filter($pluginsData, function($plugin) use ($id) {
            return ($plugin['id'] ?? null) != $id;
        });
        
        $pluginsOption->update(['option_value' => json_encode(array_values($pluginsData))]);
        
        // Clear cache
        $this->clearCacheTags(['tag:plugins']);
        
        return response()->json([
            'success' => true,
            'message' => 'Plugin uninstalled successfully',
        ]);
    }

    // ==========================================
    // APPEARANCES - THEME OPTIONS
    // ==========================================

    /**
     * Get Theme Options
     */
    public function themeOptions(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('theme-options');
        $ttl = $this->getSettingsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () {
            $option = StaticOption::where('option_name', 'theme_options')->first();
            return $option ? json_decode($option->option_value, true) : [];
        }, ['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Update Theme Options
     */
    public function updateThemeOptions(Request $request): JsonResponse
    {
        $request->validate([
            'options' => 'required|array',
        ]);
        
        StaticOption::updateOrCreate(
            ['option_name' => 'theme_options'],
            ['option_value' => json_encode($request->options)]
        );
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Theme options updated successfully',
            'data' => $request->options
        ]);
    }

    // ==========================================
    // APPEARANCES - GENERAL SETTINGS
    // ==========================================

    /**
     * Get Appearance General Settings
     */
    public function appearanceGeneralSettings(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('appearance-general');
        $ttl = $this->getSettingsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () {
            $option = StaticOption::where('option_name', 'appearance_general_settings')->first();
            return $option ? json_decode($option->option_value, true) : [];
        }, ['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Update Appearance General Settings
     */
    public function updateAppearanceGeneralSettings(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);
        
        StaticOption::updateOrCreate(
            ['option_name' => 'appearance_general_settings'],
            ['option_value' => json_encode($request->settings)]
        );
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Appearance general settings updated successfully',
            'data' => $request->settings
        ]);
    }

    // ==========================================
    // EMAIL TEMPLATES
    // ==========================================

    /**
     * Email Templates List
     */
    public function emailTemplates(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search', ''),
        ];
        
        $cacheKey = $this->getCacheKey('email-templates', $params);
        $ttl = $this->getListsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $option = StaticOption::where('option_name', 'email_templates')->first();
            $templatesData = $option ? json_decode($option->option_value, true) : [];
            
            // Filter by search
            if ($request->has('search') && !empty($request->get('search'))) {
                $search = $request->get('search');
                $templatesData = array_filter($templatesData, function($template) use ($search) {
                    return stripos($template['name'] ?? '', $search) !== false || 
                           stripos($template['subject'] ?? '', $search) !== false;
                });
            }
            
            // Ensure all templates have an id field
            $templatesData = array_map(function($template, $index) {
                if (!isset($template['id'])) {
                    $template['id'] = $index + 1;
                }
                return $template;
            }, array_values($templatesData), array_keys(array_values($templatesData)));
            
            // Paginate manually
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);
            $total = count($templatesData);
            $offset = ($page - 1) * $perPage;
            $items = array_slice($templatesData, $offset, $perPage);
            
            return [
                'data' => array_values($items),
                'meta' => [
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                ]
            ];
        }, ['tag:settings']);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }
    
    /**
     * Get Single Email Template
     */
    public function getEmailTemplate($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('email-template', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $option = StaticOption::where('option_name', 'email_templates')->first();
            $templatesData = $option ? json_decode($option->option_value, true) : [];
            
            $template = collect($templatesData)->firstWhere('id', $id);
            
            if (!$template) {
                abort(404, 'Email template not found');
            }
            
            return $template;
        }, ['tag:settings']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Email Template
     */
    public function storeEmailTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string|max:50',
        ]);
        
        $option = StaticOption::firstOrCreate(
            ['option_name' => 'email_templates'],
            ['option_value' => json_encode([])]
        );
        
        $templatesData = json_decode($option->option_value, true) ?: [];
        
        $newTemplate = [
            'id' => count($templatesData) + 1,
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'type' => $request->type ?? 'general',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        $templatesData[] = $newTemplate;
        $option->update(['option_value' => json_encode($templatesData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Email template created successfully',
            'data' => $newTemplate
        ], 201);
    }
    
    /**
     * Update Email Template
     */
    public function updateEmailTemplate(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subject' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'type' => 'nullable|string|max:50',
        ]);
        
        $option = StaticOption::where('option_name', 'email_templates')->first();
        if (!$option) {
            abort(404, 'Email templates not found');
        }
        
        $templatesData = json_decode($option->option_value, true) ?: [];
        
        $index = array_search($id, array_column($templatesData, 'id'));
        if ($index === false) {
            abort(404, 'Email template not found');
        }
        
        $templatesData[$index] = array_merge($templatesData[$index], $request->only(['name', 'subject', 'body', 'type']));
        $templatesData[$index]['updated_at'] = now()->format('Y-m-d H:i:s');
        $option->update(['option_value' => json_encode($templatesData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Email template updated successfully',
            'data' => $templatesData[$index]
        ]);
    }
    
    /**
     * Delete Email Template
     */
    public function deleteEmailTemplate($id): JsonResponse
    {
        $option = StaticOption::where('option_name', 'email_templates')->first();
        if (!$option) {
            abort(404, 'Email templates not found');
        }
        
        $templatesData = json_decode($option->option_value, true) ?: [];
        
        $templatesData = array_filter($templatesData, function($template) use ($id) {
            return ($template['id'] ?? null) != $id;
        });
        
        $option->update(['option_value' => json_encode(array_values($templatesData))]);
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Email template deleted successfully',
        ]);
    }

    // ==========================================
    // LOGIN ACTIVITY
    // ==========================================

    /**
     * Login Activity List
     */
    public function loginActivity(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search' => $request->get('search', ''),
            'status' => $request->get('status', ''),
            'type' => $request->get('type', ''),
        ];
        
        $cacheKey = $this->getCacheKey('login-activity', $params);
        $ttl = 60; // 1 minute - shorter because logs change frequently
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            $query = Activity::where('log_name', 'login')
                ->orWhere('description', 'LIKE', '%login%')
                ->with(['causer'])
                ->orderBy('id', 'desc');
            
            // Search filter
            if ($request->has('search') && !empty($request->get('search'))) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%")
                      ->orWhereHas('causer', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            // Status filter (if implemented in activity log)
            if ($request->has('status') && !empty($request->get('status'))) {
                // This would depend on how status is stored in activity log
            }
            
            $logs = $query->paginate($request->get('per_page', 20));
            
            return [
                'data' => $logs->map(function($log) {
                    return [
                        'id' => $log->id,
                        'description' => $log->description,
                        'user_name' => $log->causer->name ?? 'System',
                        'user_email' => $log->causer->email ?? null,
                        'ip_address' => $log->properties['ip_address'] ?? null,
                        'user_agent' => $log->properties['user_agent'] ?? null,
                        'status' => $log->properties['status'] ?? 'success',
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                ]
            ];
        }, ['tag:activity']);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }

    // ==========================================
    // ROLES CRUD
    // ==========================================

    /**
     * Get Single Role
     */
    public function getRole($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('role', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $role = Role::with('permissions')->findOrFail($id);
            
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ];
                }),
                'created_at' => $role->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:roles']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Role
     */
    public function storeRole(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
        ]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Role
     */
    public function updateRole(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role = Role::findOrFail($id);
        $role->update($request->only(['name', 'guard_name']));
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'updated_at' => $role->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Delete Role
     */
    public function deleteRole($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully',
        ]);
    }

    // ==========================================
    // PERMISSIONS CRUD
    // ==========================================

    /**
     * Get Single Permission
     */
    public function getPermission($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('permission', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $permission = Permission::findOrFail($id);
            
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:roles']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Permission
     */
    public function storePermission(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'nullable|string|max:255',
        ]);
        
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
        ]);
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Permission
     */
    public function updatePermission(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:permissions,name,' . $id,
            'guard_name' => 'nullable|string|max:255',
        ]);
        
        $permission = Permission::findOrFail($id);
        $permission->update($request->only(['name', 'guard_name']));
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'updated_at' => $permission->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Delete Permission
     */
    public function deletePermission($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:roles']);
        
        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully',
        ]);
    }

    // ==========================================
    // LANGUAGES CRUD
    // ==========================================

    /**
     * Get Single Language
     */
    public function getLanguage($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('language', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $language = Language::findOrFail($id);
            
            return [
                'id' => $language->id,
                'name' => $language->name,
                'slug' => $language->slug,
                'default' => $language->default ?? 0,
                'status' => $language->status ?? 1,
                'direction' => $language->direction ?? 'ltr',
                'created_at' => $language->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:settings']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Language
     */
    public function storeLanguage(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:10|unique:languages,slug',
            'direction' => 'nullable|in:ltr,rtl',
            'status' => 'nullable|boolean',
        ]);
        
        $language = Language::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'direction' => $request->direction ?? 'ltr',
            'status' => $request->status ?? 1,
            'default' => 0,
        ]);
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Language created successfully',
            'data' => [
                'id' => $language->id,
                'name' => $language->name,
                'slug' => $language->slug,
                'direction' => $language->direction,
                'status' => $language->status,
                'created_at' => $language->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Language
     */
    public function updateLanguage(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:10|unique:languages,slug,' . $id,
            'direction' => 'nullable|in:ltr,rtl',
            'status' => 'nullable|boolean',
        ]);
        
        $language = Language::findOrFail($id);
        $language->update($request->only(['name', 'slug', 'direction', 'status']));
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'data' => [
                'id' => $language->id,
                'name' => $language->name,
                'slug' => $language->slug,
                'direction' => $language->direction,
                'status' => $language->status,
                'updated_at' => $language->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Delete Language
     */
    public function deleteLanguage($id): JsonResponse
    {
        $language = Language::findOrFail($id);
        
        // Prevent deleting default language
        if ($language->default == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete default language',
            ], 422);
        }
        
        $language->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Language deleted successfully',
        ]);
    }
    
    /**
     * Set Default Language
     */
    public function setDefaultLanguage($id): JsonResponse
    {
        $language = Language::findOrFail($id);
        
        // Unset all other default languages
        Language::where('default', 1)->update(['default' => 0]);
        
        // Set this as default
        $language->update(['default' => 1]);
        
        // Clear cache
        $this->clearCacheTags(['tag:settings']);
        
        return response()->json([
            'success' => true,
            'message' => 'Default language updated successfully',
            'data' => [
                'id' => $language->id,
                'name' => $language->name,
                'slug' => $language->slug,
                'default' => 1,
            ]
        ]);
    }

    // ==========================================
    // USERS DELETE
    // ==========================================

    /**
     * Delete User
     */
    public function deleteUser($id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id == auth('admin')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account',
            ], 422);
        }
        
        $user->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:users']);
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    // ==========================================
    // MENUS CRUD
    // ==========================================

    /**
     * Get Single Menu
     */
    public function getMenu($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('menu', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $menu = Menu::with('items')->findOrFail($id);
            
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description ?? '',
                'items' => $menu->items ?? [],
                'created_at' => $menu->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Menu
     */
    public function storeMenu(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
        ]);
        
        $menu = Menu::create([
            'name' => $request->name,
            'description' => $request->description ?? '',
            'items' => $request->items ?? [],
        ]);
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Menu created successfully',
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'created_at' => $menu->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Menu
     */
    public function updateMenu(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
        ]);
        
        $menu = Menu::findOrFail($id);
        $menu->update($request->only(['name', 'description', 'items']));
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Menu updated successfully',
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'updated_at' => $menu->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Delete Menu
     */
    public function deleteMenu($id): JsonResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully',
        ]);
    }

    // ==========================================
    // WIDGETS CRUD
    // ==========================================

    /**
     * Get Single Widget
     */
    public function getWidget($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('widget', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $widget = Widgets::findOrFail($id);
            
            return [
                'id' => $widget->id,
                'name' => $widget->name,
                'description' => $widget->description ?? '',
                'is_active' => $widget->is_active ?? false,
                'created_at' => $widget->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Store Widget
     */
    public function storeWidget(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $widget = Widgets::create([
            'name' => $request->name,
            'description' => $request->description ?? '',
            'is_active' => $request->is_active ?? false,
        ]);
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Widget created successfully',
            'data' => [
                'id' => $widget->id,
                'name' => $widget->name,
                'description' => $widget->description,
                'is_active' => $widget->is_active,
                'created_at' => $widget->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
    
    /**
     * Update Widget
     */
    public function updateWidget(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $widget = Widgets::findOrFail($id);
        $widget->update($request->only(['name', 'description', 'is_active']));
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Widget updated successfully',
            'data' => [
                'id' => $widget->id,
                'name' => $widget->name,
                'is_active' => $widget->is_active,
                'updated_at' => $widget->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
    
    /**
     * Activate Widget
     */
    public function activateWidget($id): JsonResponse
    {
        $widget = Widgets::findOrFail($id);
        $widget->update(['is_active' => true]);
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Widget activated successfully',
            'data' => [
                'id' => $widget->id,
                'is_active' => true,
            ]
        ]);
    }
    
    /**
     * Deactivate Widget
     */
    public function deactivateWidget($id): JsonResponse
    {
        $widget = Widgets::findOrFail($id);
        $widget->update(['is_active' => false]);
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Widget deactivated successfully',
            'data' => [
                'id' => $widget->id,
                'is_active' => false,
            ]
        ]);
    }
    
    /**
     * Delete Widget
     */
    public function deleteWidget($id): JsonResponse
    {
        $widget = Widgets::findOrFail($id);
        $widget->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Widget deleted successfully',
        ]);
    }

    // ==========================================
    // THEMES ACTIONS
    // ==========================================

    /**
     * Get Single Theme
     */
    public function getTheme($id): JsonResponse
    {
        $cacheKey = $this->getCacheKey('theme', ['id' => $id]);
        $ttl = $this->getSingleItemTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () use ($id) {
            $theme = Themes::findOrFail($id);
            
            return [
                'id' => $theme->id,
                'name' => $theme->name,
                'description' => $theme->description ?? '',
                'is_active' => $theme->is_active ?? false,
                'created_at' => $theme->created_at->format('Y-m-d H:i:s'),
            ];
        }, ['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Activate Theme
     */
    public function activateTheme($id): JsonResponse
    {
        $theme = Themes::findOrFail($id);
        
        // Deactivate all other themes
        Themes::where('id', '!=', $id)->update(['is_active' => false]);
        
        // Activate this theme
        $theme->update(['is_active' => true]);
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Theme activated successfully',
            'data' => [
                'id' => $theme->id,
                'name' => $theme->name,
                'is_active' => true,
            ]
        ]);
    }
    
    /**
     * Delete Theme
     */
    public function deleteTheme($id): JsonResponse
    {
        $theme = Themes::findOrFail($id);
        
        // Prevent deleting active theme
        if ($theme->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete active theme',
            ], 422);
        }
        
        $theme->delete();
        
        // Clear cache
        $this->clearCacheTags(['tag:appearances']);
        
        return response()->json([
            'success' => true,
            'message' => 'Theme deleted successfully',
        ]);
    }

    // ==========================================
    // SYSTEM BACKUPS
    // ==========================================

    /**
     * Backups List
     */
    public function backups(Request $request): JsonResponse
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
        ];
        
        $cacheKey = $this->getCacheKey('backups', $params);
        $ttl = 60; // 1 minute - shorter because backups change frequently
        
        $result = $this->remember($cacheKey, $ttl, function () use ($request) {
            // Get backups from StaticOption
            $backupsOption = StaticOption::where('option_name', 'backups_list')->first();
            $backupsData = $backupsOption ? json_decode($backupsOption->option_value, true) : [];
            
            // Paginate manually
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);
            $total = count($backupsData);
            $offset = ($page - 1) * $perPage;
            $items = array_slice($backupsData, $offset, $perPage);
            
            return [
                'data' => array_values($items),
                'meta' => [
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                ]
            ];
        }, ['tag:system']);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'meta' => $result['meta']
        ]);
    }
    
    /**
     * Create Backup
     */
    public function createBackup(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'include_database' => 'nullable|boolean',
            'include_files' => 'nullable|boolean',
        ]);
        
        // In a real implementation, this would trigger a backup job
        // For now, we'll create a backup record
        $backupsOption = StaticOption::firstOrCreate(
            ['option_name' => 'backups_list'],
            ['option_value' => json_encode([])]
        );
        
        $backupsData = json_decode($backupsOption->option_value, true) ?: [];
        
        $newBackup = [
            'id' => count($backupsData) + 1,
            'name' => $request->name ?? 'Backup ' . now()->format('Y-m-d H:i:s'),
            'type' => ($request->include_database && $request->include_files) ? 'Full' : 
                      ($request->include_database ? 'Database' : 'Files'),
            'size' => 0, // Would be calculated in real implementation
            'status' => 'completed',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        $backupsData[] = $newBackup;
        $backupsOption->update(['option_value' => json_encode($backupsData)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:system']);
        
        return response()->json([
            'success' => true,
            'message' => 'Backup created successfully',
            'data' => $newBackup
        ], 201);
    }
    
    /**
     * Restore Backup
     */
    public function restoreBackup($id): JsonResponse
    {
        $backupsOption = StaticOption::where('option_name', 'backups_list')->first();
        if (!$backupsOption) {
            abort(404, 'Backups list not found');
        }
        
        $backupsData = json_decode($backupsOption->option_value, true) ?: [];
        $backup = collect($backupsData)->firstWhere('id', $id);
        
        if (!$backup) {
            abort(404, 'Backup not found');
        }
        
        // In a real implementation, this would trigger a restore job
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Backup restore initiated successfully',
            'data' => $backup
        ]);
    }
    
    /**
     * Delete Backup
     */
    public function deleteBackup($id): JsonResponse
    {
        $backupsOption = StaticOption::where('option_name', 'backups_list')->first();
        if (!$backupsOption) {
            abort(404, 'Backups list not found');
        }
        
        $backupsData = json_decode($backupsOption->option_value, true) ?: [];
        
        $backupsData = array_filter($backupsData, function($backup) use ($id) {
            return ($backup['id'] ?? null) != $id;
        });
        
        $backupsOption->update(['option_value' => json_encode(array_values($backupsData))]);
        
        // Clear cache
        $this->clearCacheTags(['tag:system']);
        
        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully',
        ]);
    }

    // ==========================================
    // SYSTEM SITEMAP
    // ==========================================

    /**
     * Get Sitemap Info
     */
    public function sitemap(Request $request): JsonResponse
    {
        $cacheKey = $this->getCacheKey('sitemap');
        $ttl = $this->getSettingsTtl();
        
        $result = $this->remember($cacheKey, $ttl, function () {
            $option = StaticOption::where('option_name', 'sitemap_info')->first();
            
            if ($option) {
                return json_decode($option->option_value, true);
            }
            
            // Default sitemap info
            return [
                'last_generated' => null,
                'total_urls' => 0,
                'url' => '/sitemap.xml',
                'settings' => [
                    'include_blogs' => true,
                    'include_pages' => true,
                    'include_categories' => true,
                    'auto_generate' => false,
                ]
            ];
        }, ['tag:system']);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Generate Sitemap
     */
    public function generateSitemap(Request $request): JsonResponse
    {
        // In a real implementation, this would generate the sitemap.xml file
        // For now, we'll update the sitemap info
        
        $option = StaticOption::firstOrCreate(
            ['option_name' => 'sitemap_info'],
            ['option_value' => json_encode([])]
        );
        
        $sitemapInfo = json_decode($option->option_value, true) ?: [];
        
        // Count total URLs (simplified)
        $totalUrls = Blog::count() + Page::count() + BlogCategory::count();
        
        $sitemapInfo = array_merge($sitemapInfo, [
            'last_generated' => now()->format('Y-m-d H:i:s'),
            'total_urls' => $totalUrls,
            'url' => '/sitemap.xml',
        ]);
        
        $option->update(['option_value' => json_encode($sitemapInfo)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:system']);
        
        return response()->json([
            'success' => true,
            'message' => 'Sitemap generated successfully',
            'data' => $sitemapInfo
        ]);
    }
    
    /**
     * Update Sitemap Settings
     */
    public function updateSitemap(Request $request): JsonResponse
    {
        $request->validate([
            'include_blogs' => 'nullable|boolean',
            'include_pages' => 'nullable|boolean',
            'include_categories' => 'nullable|boolean',
            'auto_generate' => 'nullable|boolean',
        ]);
        
        $option = StaticOption::firstOrCreate(
            ['option_name' => 'sitemap_info'],
            ['option_value' => json_encode([])]
        );
        
        $sitemapInfo = json_decode($option->option_value, true) ?: [];
        
        $sitemapInfo['settings'] = array_merge($sitemapInfo['settings'] ?? [], [
            'include_blogs' => $request->input('include_blogs', true),
            'include_pages' => $request->input('include_pages', true),
            'include_categories' => $request->input('include_categories', true),
            'auto_generate' => $request->input('auto_generate', false),
        ]);
        
        $option->update(['option_value' => json_encode($sitemapInfo)]);
        
        // Clear cache
        $this->clearCacheTags(['tag:system']);
        
        return response()->json([
            'success' => true,
            'message' => 'Sitemap settings updated successfully',
            'data' => $sitemapInfo
        ]);
    }

    // ==========================================
    // REPORTS
    // ==========================================

    /**
     * Tenants Report
     */
    public function reportTenants(): JsonResponse
    {
        try {
            $cacheKey = $this->getCacheKey('report:tenants', ['month' => now()->format('Y-m')]);
            $ttl = 600; // 10 minutes
            
            $result = $this->remember($cacheKey, $ttl, function () {
                $tenants = Tenant::selectRaw('COUNT(*) as total_tenants, SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as active_tenants, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_tenants', [now()->subMonth()])
                    ->first();
                
                return $tenants;
            }, ['tag:reports']);
            
            return response()->json([
                'success' => true,
                'message' => 'Tenants report retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate tenants report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revenue Report
     */
    public function reportRevenue(): JsonResponse
    {
        try {
            $cacheKey = $this->getCacheKey('report:revenue', ['month' => now()->format('Y-m')]);
            $ttl = 600;
            
            $result = $this->remember($cacheKey, $ttl, function () {
                $revenue = PaymentLogs::selectRaw('SUM(CASE WHEN payment_status = ? THEN package_price ELSE 0 END) as total_revenue, COUNT(*) as total_payments', ['success'])
                    ->first();
                
                return $revenue;
            }, ['tag:reports']);
            
            return response()->json([
                'success' => true,
                'message' => 'Revenue report retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate revenue report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscriptions Report
     */
    public function reportSubscriptions(): JsonResponse
    {
        try {
            $cacheKey = $this->getCacheKey('report:subscriptions', ['month' => now()->format('Y-m')]);
            $ttl = 600;
            
            $result = $this->remember($cacheKey, $ttl, function () {
                $subscriptions = PaymentLogs::selectRaw('COUNT(*) as total_subscriptions, SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as active_subscriptions', ['success'])
                    ->first();
                
                return $subscriptions;
            }, ['tag:reports']);
            
            return response()->json([
                'success' => true,
                'message' => 'Subscriptions report retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate subscriptions report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Plans Report
     */
    public function reportPlans(): JsonResponse
    {
        try {
            $cacheKey = $this->getCacheKey('report:plans', ['month' => now()->format('Y-m')]);
            $ttl = 600;
            
            $result = $this->remember($cacheKey, $ttl, function () {
                $plans = PricePlan::selectRaw('COUNT(*) as total_plans, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_plans', ['publish'])
                    ->first();
                
                return $plans;
            }, ['tag:reports']);
            
            return response()->json([
                'success' => true,
                'message' => 'Plans report retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate plans report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Order Payment Logs
     */
    public function orderPaymentLogs($id): JsonResponse
    {
        try {
            $order = \App\Models\Order::findOrFail($id);
            $cacheKey = $this->getCacheKey("order:{$id}:payment-logs");
            $ttl = $this->getDetailsTtl();
            
            $paymentLog = $this->remember($cacheKey, $ttl, function () use ($order) {
                return PaymentLogs::where('order_id', $order->id)
                    ->first();
            }, ['tag:orders', "tag:order:{$id}"]);
            
            if (!$paymentLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment log not found for this order',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Payment logs retrieved successfully',
                'data' => [
                    'id' => $paymentLog->id,
                    'order_id' => $paymentLog->order_id,
                    'package_name' => $paymentLog->package_name,
                    'package_price' => $paymentLog->package_price,
                    'payment_status' => $paymentLog->payment_status,
                    'payment_gateway' => $paymentLog->payment_gateway,
                    'transaction_id' => $paymentLog->transaction_id,
                    'created_at' => $paymentLog->created_at?->format('Y-m-d H:i:s'),
                    'updated_at' => $paymentLog->updated_at?->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


