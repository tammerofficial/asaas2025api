<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Central\Auth\AuthController;
use App\Http\Controllers\Api\Central\DashboardController;
use App\Http\Controllers\Api\Central\TenantController;
use App\Http\Controllers\Api\Central\PricePlanController;
use App\Http\Controllers\Api\Central\OrderController;
use App\Http\Controllers\Api\Central\PaymentController;
use App\Http\Controllers\Api\Central\AdminController;
use App\Http\Controllers\Api\Central\MediaController;
use App\Http\Controllers\Api\Central\SettingsController;
use App\Http\Controllers\Api\Central\SupportTicketController;
use App\Http\Controllers\Api\Central\ReportController;
use App\Http\Middleware\Api\EnsureCentralContext;

/*
|--------------------------------------------------------------------------
| Central API Routes
|--------------------------------------------------------------------------
|
| These routes handle the Central/Landlord dashboard API endpoints.
| They are accessed from the central domain (asaas.local)
| No tenant context is needed here.
|
*/

Route::middleware([\App\Http\Middleware\Api\ForceJsonResponse::class])
    ->prefix('central/v1')
    ->group(function () {
    
    // Public routes (Authentication)
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
    
    // Protected routes (require authentication)
    Route::middleware([EnsureCentralContext::class, 'auth:sanctum'])->group(function () {
        
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
        
        // Tenants management
        Route::apiResource('tenants', TenantController::class);
        Route::prefix('tenants')->group(function () {
            Route::post('{tenant}/activate', [TenantController::class, 'activate']);
            Route::post('{tenant}/deactivate', [TenantController::class, 'deactivate']);
        });
        
        // Price Plans management
        Route::apiResource('plans', PricePlanController::class);
        
        // Orders management
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::prefix('orders')->group(function () {
            Route::get('{order}/payment-logs', [OrderController::class, 'paymentLogs']);
        });
        
        // Payments management
        Route::apiResource('payments', PaymentController::class)->only(['index', 'show', 'update']);
        
        // Admin users management
        Route::apiResource('admins', AdminController::class);
        Route::prefix('admins')->group(function () {
            Route::post('{admin}/activate', [AdminController::class, 'activate']);
            Route::post('{admin}/deactivate', [AdminController::class, 'deactivate']);
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
        
        // Support Tickets management
        Route::apiResource('support-tickets', SupportTicketController::class)->only(['index', 'show', 'update']);
        Route::post('support-tickets/{supportTicket}/add-message', [SupportTicketController::class, 'addMessage']);
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('tenants', [ReportController::class, 'tenants']);
            Route::get('revenue', [ReportController::class, 'revenue']);
            Route::get('subscriptions', [ReportController::class, 'subscriptions']);
            Route::get('plans', [ReportController::class, 'plans']);
        });
    });
});

