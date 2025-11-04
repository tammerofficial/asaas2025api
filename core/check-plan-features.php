<?php

/**
 * Script to check plan features in detail
 * Usage: php check-plan-features.php 13
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PricePlan;
use App\Models\PlanFeature;

// Get plan ID from command line argument
$planId = $argv[1] ?? '13';

echo "========================================\n";
echo "🔍 فحص إمكانيات الباقة: {$planId}\n";
echo "========================================\n\n";

// Find plan
$plan = PricePlan::with(['plan_features'])->find($planId);

if (!$plan) {
    echo "❌ الباقة غير موجودة: {$planId}\n";
    exit(1);
}

echo "📦 الباقة:\n";
echo "   - ID: {$plan->id}\n";
echo "   - العنوان: {$plan->title}\n";
echo "   - السعر: {$plan->price} KWD\n";
echo "   - الحالة: " . ($plan->status == 1 ? 'نشط' : 'غير نشط') . "\n\n";

// Get all features
$allFeatures = PlanFeature::where('plan_id', $planId)->get();

echo "📊 جميع الإمكانيات ({$allFeatures->count()}):\n";
$activeCount = 0;
$inactiveCount = 0;

foreach ($allFeatures as $feature) {
    $status = $feature->status == 1 ? '✅ نشط' : '❌ معطّل';
    echo "   {$status} - {$feature->feature_name}\n";
    
    if ($feature->status == 1) {
        $activeCount++;
    } else {
        $inactiveCount++;
    }
}

echo "\n";
echo "📈 الإحصائيات:\n";
echo "   - الإمكانيات النشطة: {$activeCount}\n";
echo "   - الإمكانيات المعطلة: {$inactiveCount}\n";
echo "   - الإجمالي: {$allFeatures->count()}\n";

if ($activeCount == 0) {
    echo "\n⚠️  تحذير: لا توجد إمكانيات نشطة في هذه الباقة!\n";
    echo "   هذا قد يسبب عدم ظهور الإمكانيات في الداشبورد.\n";
}

echo "\n";
echo "========================================\n";
echo "✅ انتهى الفحص\n";
echo "========================================\n";

