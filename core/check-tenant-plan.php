<?php

/**
 * Script to check tenant's current subscription plan
 * Usage: php check-tenant-plan.php salon
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\PlanFeature;

// Get tenant ID from command line argument
$tenantId = $argv[1] ?? 'salon';

echo "========================================\n";
echo "🔍 فحص باقة المستأجر: {$tenantId}\n";
echo "========================================\n\n";

// Find tenant
$tenant = Tenant::where('id', $tenantId)->first();

if (!$tenant) {
    echo "❌ المستأجر غير موجود: {$tenantId}\n";
    exit(1);
}

echo "✅ المستأجر موجود\n";
echo "   - ID: {$tenant->id}\n";
echo "   - User ID: {$tenant->user_id}\n";
echo "   - Price Plan ID: {$tenant->price_plan_id}\n";
echo "   - Renewal Payment Log ID: {$tenant->renewal_payment_log_id}\n\n";

// Get payment log
$paymentLog = null;
if ($tenant->renewal_payment_log_id) {
    $paymentLog = PaymentLogs::with('package')->find($tenant->renewal_payment_log_id);
    
    if ($paymentLog) {
        echo "📋 Payment Log Details:\n";
        echo "   - ID: {$paymentLog->id}\n";
        echo "   - Package ID: {$paymentLog->package_id}\n";
        echo "   - Status: {$paymentLog->status}\n";
        echo "   - Payment Status: {$paymentLog->payment_status}\n";
        echo "   - Start Date: {$paymentLog->start_date}\n";
        echo "   - Expire Date: {$paymentLog->expire_date}\n";
        echo "   - Is Renew: " . ($paymentLog->is_renew ?? 0) . "\n\n";
    } else {
        echo "⚠️  Payment Log غير موجود: {$tenant->renewal_payment_log_id}\n\n";
    }
} else {
    echo "⚠️  لا يوجد Renewal Payment Log ID\n\n";
}

// Get plan from price_plan_id
$plan = null;
if ($tenant->price_plan_id) {
    $plan = PricePlan::with(['plan_features'])->find($tenant->price_plan_id);
}

// Also check plan from payment log
if (!$plan && $paymentLog && $paymentLog->package_id) {
    $plan = PricePlan::with(['plan_features'])->find($paymentLog->package_id);
}

if ($plan) {
    echo "📦 الباقة الحالية:\n";
    echo "   - ID: {$plan->id}\n";
    echo "   - العنوان: {$plan->title}\n";
    echo "   - السعر: {$plan->price} KWD\n";
    echo "   - النوع: ";
    
    $typeText = match($plan->type) {
        0 => 'شهري (Monthly)',
        1 => 'سنوي (Yearly)',
        3 => 'مخصص (Custom)',
        default => 'مدى الحياة (Lifetime)'
    };
    echo "{$typeText}\n";
    echo "   - الحالة: " . ($plan->status == 1 ? 'نشط' : 'غير نشط') . "\n\n";
    
    // Get features - only active ones
    $activeFeatures = $plan->plan_features()->where('status', 1)->get();
    $allFeatures = $plan->plan_features;
    
    if ($activeFeatures && $activeFeatures->count() > 0) {
        echo "✨ الإمكانيات النشطة ({$activeFeatures->count()}):\n";
        foreach ($activeFeatures as $feature) {
            echo "   ✓ {$feature->feature_name}\n";
        }
    } else {
        echo "⚠️  لا توجد إمكانيات نشطة في هذه الباقة\n";
    }
    
    if ($allFeatures && $allFeatures->count() > $activeFeatures->count()) {
        $inactiveCount = $allFeatures->count() - $activeFeatures->count();
        echo "\n⚠️  الإمكانيات المعطلة ({$inactiveCount}):\n";
        foreach ($allFeatures->where('status', 0) as $feature) {
            echo "   ✗ {$feature->feature_name} (معطّل)\n";
        }
    }
    
    echo "\n";
} else {
    echo "❌ الباقة غير موجودة!\n";
    if ($tenant->price_plan_id) {
        echo "   - Price Plan ID في جدول tenants: {$tenant->price_plan_id}\n";
    }
    if ($paymentLog && $paymentLog->package_id) {
        echo "   - Package ID في Payment Log: {$paymentLog->package_id}\n";
    }
    echo "\n";
}

// Check if there's a mismatch
if ($tenant->price_plan_id && $paymentLog && $paymentLog->package_id) {
    if ($tenant->price_plan_id != $paymentLog->package_id) {
        echo "⚠️  تنبيه: هناك عدم تطابق!\n";
        echo "   - Price Plan ID في tenants: {$tenant->price_plan_id}\n";
        echo "   - Package ID في Payment Log: {$paymentLog->package_id}\n";
        echo "\n";
    }
}

// Get latest payment log
echo "📊 آخر Payment Logs للمستأجر:\n";
$latestLogs = PaymentLogs::where('tenant_id', $tenant->id)
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get();

if ($latestLogs->count() > 0) {
    foreach ($latestLogs as $log) {
        $planTitle = $log->package ? $log->package->title : 'N/A';
        echo "   - ID: {$log->id} | Plan: {$planTitle} | Status: {$log->payment_status} | Date: {$log->created_at}\n";
    }
} else {
    echo "   لا توجد Payment Logs\n";
}

echo "\n";
echo "========================================\n";
echo "✅ انتهى الفحص\n";
echo "========================================\n";

