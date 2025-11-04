<?php

/**
 * Script to update tenant's subscription plan
 * Usage: php update-tenant-plan.php salon
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use Illuminate\Support\Facades\DB;

// Get tenant ID from command line argument
$tenantId = $argv[1] ?? 'salon';

echo "========================================\n";
echo "🔄 تحديث باقة المستأجر: {$tenantId}\n";
echo "========================================\n\n";

// Find tenant
$tenant = Tenant::where('id', $tenantId)->first();

if (!$tenant) {
    echo "❌ المستأجر غير موجود: {$tenantId}\n";
    exit(1);
}

echo "✅ المستأجر موجود\n";
echo "   - الباقة الحالية (Price Plan ID): {$tenant->price_plan_id}\n";
echo "   - Payment Log الحالي (Renewal Payment Log ID): {$tenant->renewal_payment_log_id}\n\n";

// Get latest payment log
$latestPaymentLog = PaymentLogs::with('package')
    ->where('tenant_id', $tenant->id)
    ->where('payment_status', 'complete')
    ->orderBy('id', 'desc')
    ->first();

if (!$latestPaymentLog) {
    echo "❌ لا يوجد Payment Log صالح للمستأجر\n";
    exit(1);
}

echo "📋 آخر Payment Log:\n";
echo "   - ID: {$latestPaymentLog->id}\n";
echo "   - Package ID: {$latestPaymentLog->package_id}\n";
echo "   - Plan: " . ($latestPaymentLog->package ? $latestPaymentLog->package->title : 'N/A') . "\n";
echo "   - Status: {$latestPaymentLog->payment_status}\n";
echo "   - Created: {$latestPaymentLog->created_at}\n\n";

// Check if update is needed
if ($tenant->renewal_payment_log_id == $latestPaymentLog->id && 
    $tenant->price_plan_id == $latestPaymentLog->package_id) {
    echo "✅ الباقة محدثة بالفعل - لا حاجة للتحديث\n";
    exit(0);
}

echo "⚠️  هناك اختلاف - سيتم التحديث...\n\n";

// Get plan details
$newPlan = PricePlan::with(['plan_features'])->find($latestPaymentLog->package_id);

if (!$newPlan) {
    echo "❌ الباقة غير موجودة: {$latestPaymentLog->package_id}\n";
    exit(1);
}

echo "📦 الباقة الجديدة:\n";
echo "   - ID: {$newPlan->id}\n";
echo "   - العنوان: {$newPlan->title}\n";
echo "   - السعر: {$newPlan->price} KWD\n";

$features = $newPlan->plan_features()->where('status', 1)->get();
echo "   - الإمكانيات النشطة: {$features->count()}\n";
foreach ($features as $feature) {
    echo "     ✓ {$feature->feature_name}\n";
}

echo "\n";

// Confirm update
echo "🔄 سيتم تحديث:\n";
echo "   - Price Plan ID: {$tenant->price_plan_id} → {$latestPaymentLog->package_id}\n";
echo "   - Renewal Payment Log ID: {$tenant->renewal_payment_log_id} → {$latestPaymentLog->id}\n\n";

// Update tenant
DB::beginTransaction();
try {
    $tenant->price_plan_id = $latestPaymentLog->package_id;
    $tenant->renewal_payment_log_id = $latestPaymentLog->id;
    $tenant->save();
    
    DB::commit();
    
    echo "✅ تم التحديث بنجاح!\n\n";
    
    // Verify
    $tenant->refresh();
    echo "📊 التحقق من التحديث:\n";
    echo "   - Price Plan ID: {$tenant->price_plan_id}\n";
    echo "   - Renewal Payment Log ID: {$tenant->renewal_payment_log_id}\n";
    
    echo "\n";
    echo "========================================\n";
    echo "✅ اكتمل التحديث بنجاح\n";
    echo "========================================\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ خطأ في التحديث: " . $e->getMessage() . "\n";
    exit(1);
}

