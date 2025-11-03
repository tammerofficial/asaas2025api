<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\Api\EnsureTenantContext;
use App\Http\Controllers\Api\Tenant\Auth\AuthController;
use App\Http\Controllers\Api\Tenant\DashboardController;
use App\Http\Controllers\Api\Tenant\ProductController;
use App\Http\Controllers\Api\Tenant\OrderController;
use App\Http\Controllers\Api\Tenant\CustomerController;
use App\Http\Controllers\Api\Tenant\CategoryController;
use App\Http\Controllers\Api\Tenant\BlogController;
use App\Http\Controllers\Api\Tenant\BlogCategoryController;
use App\Http\Controllers\Api\Tenant\PageController;
use App\Http\Controllers\Api\Tenant\MediaController;
use App\Http\Controllers\Api\Tenant\SettingsController;
use App\Http\Controllers\Api\Tenant\CouponController;
use App\Http\Controllers\Api\Tenant\ShippingZoneController;
use App\Http\Controllers\Api\Tenant\ShippingMethodController;
use App\Http\Controllers\Api\Tenant\InventoryController;
use App\Http\Controllers\Api\Tenant\WalletController;
use App\Http\Controllers\Api\Tenant\SupportTicketController;
use App\Http\Controllers\Api\Tenant\ReportController;
use App\Http\Controllers\Api\Tenant\ProductReviewController;
use App\Http\Controllers\Api\Tenant\RefundController;
use App\Http\Controllers\Api\Tenant\TaxController;
use App\Http\Controllers\Api\Tenant\NewsletterController;
use App\Http\Controllers\Api\Tenant\BadgeController;
use App\Http\Controllers\Api\Tenant\CampaignController;
use App\Http\Controllers\Api\Tenant\DigitalProductController;
use App\Http\Controllers\Api\Tenant\CountryController;
use App\Http\Controllers\Api\Tenant\StateController;
use App\Http\Controllers\Api\Tenant\ServiceController;
use App\Http\Controllers\Api\Tenant\ServiceCategoryController;
use App\Http\Controllers\Api\Tenant\SalesReportController;
use App\Http\Controllers\Api\Tenant\SiteAnalyticsController;
use App\Http\Controllers\Api\Tenant\ProductAttributeController;
use App\Http\Controllers\Api\Tenant\BrandController;
use App\Http\Controllers\Api\Tenant\ColorController;
use App\Http\Controllers\Api\Tenant\SizeController;
use App\Http\Controllers\Api\Tenant\TagController;
use App\Http\Controllers\Api\Tenant\UnitController;
use App\Http\Controllers\Api\Tenant\SubCategoryController;
use App\Http\Controllers\Api\Tenant\ChildCategoryController;
use App\Http\Controllers\Api\Tenant\DeliveryOptionController;
use App\Http\Controllers\Api\Tenant\CityController;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| These routes handle the Tenant dashboard API endpoints.
| They require tenant context initialization via domain.
| Access from tenant domains only (e.g., tenant1.asaas.local)
|
*/

Route::middleware([
    'api',
    \App\Http\Middleware\Api\ForceJsonResponse::class,
    InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
])->prefix('tenant/v1')->group(function () {
    
    // Public routes (Authentication)
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });
    
    // Protected routes (require authentication)
    Route::middleware([EnsureTenantContext::class, 'auth:sanctum'])->group(function () {
        
        // Authentication routes
        Route::prefix('auth')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
        });
        
        // Dashboard routes
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('stats', [DashboardController::class, 'stats']);
            Route::get('recent-orders', [DashboardController::class, 'recentOrders']);
            Route::get('chart-data', [DashboardController::class, 'chartData']);
        });
        
        // Products management
        Route::apiResource('products', ProductController::class);
        Route::prefix('products')->group(function () {
            Route::post('{product}/activate', [ProductController::class, 'activate']);
            Route::post('{product}/deactivate', [ProductController::class, 'deactivate']);
        });
        
        // Orders management
        Route::apiResource('orders', OrderController::class);
        Route::prefix('orders')->group(function () {
            Route::post('{order}/update-status', [OrderController::class, 'updateStatus']);
            Route::post('{order}/cancel', [OrderController::class, 'cancel']);
        });
        
        // Customers management
        Route::apiResource('customers', CustomerController::class);
        Route::prefix('customers')->group(function () {
            Route::get('{customer}/orders', [CustomerController::class, 'orders']);
            Route::get('{customer}/stats', [CustomerController::class, 'stats']);
        });
        
        // Categories management
        Route::apiResource('categories', CategoryController::class);
        Route::prefix('categories')->group(function () {
            Route::get('{category}/products', [CategoryController::class, 'products']);
        });
        
        // Blog management
        Route::apiResource('blogs', BlogController::class);
        Route::prefix('blogs')->group(function () {
            Route::post('{blog}/publish', [BlogController::class, 'publish']);
            Route::post('{blog}/unpublish', [BlogController::class, 'unpublish']);
        });
        
        // Blog Categories management
        Route::apiResource('blog-categories', BlogCategoryController::class);
        Route::prefix('blog-categories')->group(function () {
            Route::get('{blogCategory}/blogs', [BlogCategoryController::class, 'blogs']);
        });
        
        // Pages management
        Route::apiResource('pages', PageController::class);
        Route::prefix('pages')->group(function () {
            Route::post('{page}/publish', [PageController::class, 'publish']);
            Route::post('{page}/unpublish', [PageController::class, 'unpublish']);
        });
        
        // Media management
        Route::apiResource('media', MediaController::class)->except(['store']);
        Route::prefix('media')->group(function () {
            Route::post('upload', [MediaController::class, 'upload']);
            Route::post('bulk-delete', [MediaController::class, 'bulkDelete']);
        });
        
        // Settings management
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index']);
            Route::get('{key}', [SettingsController::class, 'show']);
            Route::put('/', [SettingsController::class, 'update']);
            Route::delete('{key}', [SettingsController::class, 'destroy']);
        });
        
        // Coupons management
        Route::apiResource('coupons', CouponController::class);
        Route::prefix('coupons')->group(function () {
            Route::post('{coupon}/activate', [CouponController::class, 'activate']);
            Route::post('{coupon}/deactivate', [CouponController::class, 'deactivate']);
            Route::get('validate/{code}', [CouponController::class, 'validate']);
        });
        
        // Shipping Zones management
        Route::apiResource('shipping-zones', ShippingZoneController::class);
        
        // Shipping Methods management
        Route::apiResource('shipping-methods', ShippingMethodController::class);
        
        // Inventory management
        Route::apiResource('inventory', InventoryController::class);
        Route::post('inventory/{inventory}/adjust-stock', [InventoryController::class, 'adjustStock']);
        
        // Wallet management
        Route::apiResource('wallets', WalletController::class)->only(['index', 'show', 'update']);
        Route::post('wallets/{wallet}/add-balance', [WalletController::class, 'addBalance']);
        Route::post('wallets/{wallet}/deduct-balance', [WalletController::class, 'deductBalance']);
        
        // Support Tickets management
        Route::apiResource('support-tickets', SupportTicketController::class);
        Route::post('support-tickets/{supportTicket}/add-message', [SupportTicketController::class, 'addMessage']);
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('sales', [ReportController::class, 'sales']);
            Route::get('products', [ReportController::class, 'products']);
            Route::get('customers', [ReportController::class, 'customers']);
            Route::get('orders', [ReportController::class, 'orders']);
        });
        
        // Product Reviews management
        Route::apiResource('product-reviews', ProductReviewController::class);
        Route::post('product-reviews/{productReview}/approve', [ProductReviewController::class, 'approve']);
        Route::post('product-reviews/{productReview}/reject', [ProductReviewController::class, 'reject']);
        
        // Refunds management
        Route::apiResource('refunds', RefundController::class);
        Route::post('refunds/{refund}/approve', [RefundController::class, 'approve']);
        Route::post('refunds/{refund}/reject', [RefundController::class, 'reject']);
        
        // Taxes management
        Route::apiResource('taxes', TaxController::class);
        
        // Newsletter management
        Route::apiResource('newsletters', NewsletterController::class);
        Route::post('newsletters/{newsletter}/unsubscribe', [NewsletterController::class, 'unsubscribe']);
        
        // Badges management
        Route::apiResource('badges', BadgeController::class);
        
        // Campaigns management
        Route::apiResource('campaigns', CampaignController::class);
        Route::post('campaigns/{campaign}/activate', [CampaignController::class, 'activate']);
        Route::post('campaigns/{campaign}/deactivate', [CampaignController::class, 'deactivate']);
        
        // Digital Products management
        Route::apiResource('digital-products', DigitalProductController::class);
        Route::post('digital-products/{digitalProduct}/activate', [DigitalProductController::class, 'activate']);
        Route::post('digital-products/{digitalProduct}/deactivate', [DigitalProductController::class, 'deactivate']);
        
        // Countries & States
        Route::get('countries', [CountryController::class, 'index']);
        Route::get('countries/{country}', [CountryController::class, 'show']);
        Route::get('states', [StateController::class, 'index']);
        Route::get('states/{state}', [StateController::class, 'show']);
        
        // Services management
        Route::apiResource('services', ServiceController::class);
        
        // Service Categories management
        Route::apiResource('service-categories', ServiceCategoryController::class);
        Route::prefix('service-categories')->group(function () {
            Route::get('{serviceCategory}/services', [ServiceCategoryController::class, 'services']);
        });
        
        // Sales Reports
        Route::prefix('sales-reports')->group(function () {
            Route::get('/', [SalesReportController::class, 'index']);
            Route::get('today', [SalesReportController::class, 'today']);
            Route::get('weekly', [SalesReportController::class, 'weekly']);
            Route::get('monthly', [SalesReportController::class, 'monthly']);
            Route::get('yearly', [SalesReportController::class, 'yearly']);
        });
        
        // Site Analytics
        Route::prefix('site-analytics')->group(function () {
            Route::get('/', [SiteAnalyticsController::class, 'index']);
            Route::get('visitors', [SiteAnalyticsController::class, 'visitors']);
            Route::get('orders', [SiteAnalyticsController::class, 'orders']);
        });
        
        // Attributes Module
        Route::apiResource('product-attributes', ProductAttributeController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('colors', ColorController::class);
        Route::apiResource('sizes', SizeController::class);
        Route::apiResource('tags', TagController::class);
        Route::apiResource('units', UnitController::class);
        Route::apiResource('sub-categories', SubCategoryController::class);
        Route::apiResource('child-categories', ChildCategoryController::class);
        Route::apiResource('delivery-options', DeliveryOptionController::class);
        
        // Cities
        Route::apiResource('cities', CityController::class);
    });
});

