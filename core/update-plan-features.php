<?php

/**
 * Script to update plan features and activate them
 * Usage: php update-plan-features.php 13
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PricePlan;
use App\Models\PlanFeature;
use App\Models\PlanTheme;
use Illuminate\Support\Facades\DB;

// Get plan ID from command line argument
$planId = $argv[1] ?? '13';

echo "========================================\n";
echo "🔄 تحديث إمكانيات الباقة: {$planId}\n";
echo "========================================\n\n";

// Find plan
$plan = PricePlan::with(['plan_features', 'plan_themes'])->find($planId);

if (!$plan) {
    echo "❌ الباقة غير موجودة: {$planId}\n";
    exit(1);
}

echo "📦 الباقة: {$plan->title}\n\n";

// Features to activate
$featuresToActivate = [
    'pages',
    'blog',
    'products',
    'storage',
];

// Themes to activate
$themesToActivate = [
    'hexfashion',
];

echo "✨ الإمكانيات المطلوبة:\n";
foreach ($featuresToActivate as $feature) {
    echo "   - {$feature}\n";
}

echo "\n🎨 الثيمات المطلوبة:\n";
foreach ($themesToActivate as $theme) {
    echo "   - {$theme}\n";
}

echo "\n";

DB::beginTransaction();
try {
    // Update features
    foreach ($featuresToActivate as $featureName) {
        $feature = PlanFeature::where('plan_id', $planId)
            ->where('feature_name', $featureName)
            ->first();
        
        if ($feature) {
            $feature->status = 1;
            $feature->save();
            echo "✅ تم تفعيل الإمكانية: {$featureName}\n";
        } else {
            PlanFeature::create([
                'plan_id' => $planId,
                'feature_name' => $featureName,
                'status' => 1,
            ]);
            echo "➕ تم إضافة وتفعيل الإمكانية: {$featureName}\n";
        }
    }
    
    // Deactivate other features
    $otherFeatures = PlanFeature::where('plan_id', $planId)
        ->whereNotIn('feature_name', $featuresToActivate)
        ->get();
    
    foreach ($otherFeatures as $feature) {
        $feature->status = 0;
        $feature->save();
        echo "❌ تم تعطيل الإمكانية: {$feature->feature_name}\n";
    }
    
    // Update themes
    // Delete existing themes
    PlanTheme::where('plan_id', $planId)->delete();
    
    // Add new themes
    foreach ($themesToActivate as $themeSlug) {
        PlanTheme::create([
            'plan_id' => $planId,
            'theme_slug' => $themeSlug,
            'status' => 1,
        ]);
        echo "✅ تم إضافة الثيم: {$themeSlug}\n";
    }
    
    DB::commit();
    
    echo "\n";
    echo "✅ تم التحديث بنجاح!\n\n";
    
    // Verify
    $plan->refresh();
    $activeFeatures = PlanFeature::where('plan_id', $planId)->where('status', 1)->get();
    echo "📊 الإمكانيات النشطة ({$activeFeatures->count()}):\n";
    foreach ($activeFeatures as $feature) {
        echo "   ✓ {$feature->feature_name}\n";
    }
    
    $planThemes = PlanTheme::where('plan_id', $planId)->get();
    echo "\n🎨 الثيمات ({$planThemes->count()}):\n";
    foreach ($planThemes as $theme) {
        echo "   ✓ {$theme->theme_slug}\n";
    }
    
    echo "\n";
    echo "========================================\n";
    echo "✅ اكتمل التحديث بنجاح\n";
    echo "========================================\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ خطأ في التحديث: " . $e->getMessage() . "\n";
    exit(1);
}

