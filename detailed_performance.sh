#!/bin/bash

echo "========================================="
echo "📊 تقرير مفصل لأداء الداشبورد"
echo "========================================="
echo ""

# Check Query Performance
echo "1️⃣ فحص أداء الاستعلامات في Controller"
echo "─────────────────────────────────────────"
cd core && php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
DB::enableQueryLog();

echo '🔍 محاكاة عمل الداشبورد Controller...' . PHP_EOL . PHP_EOL;

\$start = microtime(true);

// Simulate dashboard() method
\$total_admin = \App\Models\Admin::count();
\$time1 = microtime(true) - \$start;

\$total_user = \App\Models\User::count();
\$time2 = microtime(true) - \$start;

\$all_tenants = \App\Models\Tenant::whereValid()->count();
\$time3 = microtime(true) - \$start;

\$total_price_plan = \App\Models\PricePlan::count();
\$time4 = microtime(true) - \$start;

\$total_brand = \App\Models\Brand::all()->count();
\$time5 = microtime(true) - \$start;

\$total_testimonial = \App\Models\Testimonial::all()->count();
\$time6 = microtime(true) - \$start;

\$recent_order_logs = \App\Models\PaymentLogs::orderBy('id','desc')->take(5)->get();
\$time7 = microtime(true) - \$start;

\$totalTime = microtime(true) - \$start;

echo '📌 النتائج:' . PHP_EOL;
echo '  1. Admin::count() = ' . \$total_admin . ' (' . round(\$time1 * 1000, 2) . 'ms)' . PHP_EOL;
echo '  2. User::count() = ' . \$total_user . ' (' . round((\$time2 - \$time1) * 1000, 2) . 'ms)' . PHP_EOL;
echo '  3. Tenant::whereValid()->count() = ' . \$all_tenants . ' (' . round((\$time3 - \$time2) * 1000, 2) . 'ms)' . PHP_EOL;
echo '  4. PricePlan::count() = ' . \$total_price_plan . ' (' . round((\$time4 - \$time3) * 1000, 2) . 'ms)' . PHP_EOL;
echo '  5. Brand::all()->count() = ' . \$total_brand . ' (' . round((\$time5 - \$time4) * 1000, 2) . 'ms)' . PHP_EOL;
echo '  6. Testimonial::all()->count() = ' . \$total_testimonial . ' (' . round((\$time6 - \$time5) * 1000, 2) . 'ms)' . PHP_EOL;
echo '  7. PaymentLogs::orderBy()->take(5) = ' . \$recent_order_logs->count() . ' (' . round((\$time7 - \$time6) * 1000, 2) . 'ms)' . PHP_EOL;
echo '' . PHP_EOL;
echo '⏱️  إجمالي وقت الاستعلامات: ' . round(\$totalTime * 1000, 2) . 'ms' . PHP_EOL;
echo '📊 عدد الاستعلامات المنفذة: ' . count(DB::getQueryLog()) . PHP_EOL;

echo '' . PHP_EOL . '🔎 تفاصيل الاستعلامات:' . PHP_EOL;
foreach(DB::getQueryLog() as \$key => \$query) {
    echo '  ' . (\$key + 1) . '. ' . \$query['query'] . ' (' . \$query['time'] . 'ms)' . PHP_EOL;
}
"
cd ..
echo ""

# Check View Rendering Performance
echo "2️⃣ فحص أداء عرض الصفحة (Blade View)"
echo "─────────────────────────────────────────"
cd core && php artisan tinker --execute="
\$start = microtime(true);
\$themes = getAllThemeSlug();
\$themeTime = microtime(true) - \$start;
echo '📁 getAllThemeSlug(): ' . count(\$themes) . ' themes (' . round(\$themeTime * 1000, 2) . 'ms)' . PHP_EOL;
"
cd ..
echo ""

# Check Middleware Performance
echo "3️⃣ فحص Middleware المستخدم"
echo "─────────────────────────────────────────"
cd core && php artisan route:list --path="admin-home" --columns=uri,name,middleware 2>/dev/null | head -20 || echo "⚠️ لا يمكن عرض معلومات الراوت (مشكلة في route:cache)"
cd ..
echo ""

# Check Asset Loading
echo "4️⃣ فحص تحميل الملفات الثابتة (Assets)"
echo "─────────────────────────────────────────"
echo "📦 CSS Files:"
ls -lh assets/landlord/admin/css/*.css 2>/dev/null | awk '{print "  " $9 " - " $5}' | head -5
echo ""
echo "📦 JS Files:"
ls -lh assets/landlord/admin/js/*.js 2>/dev/null | awk '{print "  " $9 " - " $5}' | head -5
echo ""

# Check AJAX Endpoints Performance
echo "5️⃣ فحص أداء AJAX Endpoints"
echo "─────────────────────────────────────────"
echo "📊 Chart Data - Monthly:"
curl -k -X POST https://asaas.local/admin-home/chart-data-month \
  -w "  ⏱️  الوقت: %{time_total}s | كود: %{http_code}\n" \
  -o /dev/null -s \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=test" 2>&1 | grep -E "الوقت|كود"

echo ""
echo "📊 Chart Data - Daily:"
curl -k -X POST https://asaas.local/admin-home/chart-data-by-day \
  -w "  ⏱️  الوقت: %{time_total}s | كود: %{http_code}\n" \
  -o /dev/null -s \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=test" 2>&1 | grep -E "الوقت|كود"

echo ""

# Check Config Cache
echo "6️⃣ فحص الكاش والتكوين"
echo "─────────────────────────────────────────"
echo "📁 Config Cache:"
[ -f core/bootstrap/cache/config.php ] && echo "  ✅ موجود ($(ls -lh core/bootstrap/cache/config.php | awk '{print $5}'))" || echo "  ❌ غير موجود"

echo "📁 Route Cache:"
[ -f core/bootstrap/cache/routes-v7.php ] && echo "  ✅ موجود ($(ls -lh core/bootstrap/cache/routes-v7.php | awk '{print $5}'))" || echo "  ❌ غير موجود"

echo "📁 View Cache:"
VIEW_COUNT=$(find core/storage/framework/views -name "*.php" 2>/dev/null | wc -l)
echo "  📊 عدد Views المخزنة: $VIEW_COUNT"

echo ""

# Check Server Response Time Components
echo "7️⃣ تحليل مكونات وقت الاستجابة"
echo "─────────────────────────────────────────"
curl -k -w "\n📊 تفاصيل الأداء:\n\
  🔌 DNS Lookup: %{time_namelookup}s\n\
  🔗 TCP Connection: %{time_connect}s\n\
  🔒 SSL Handshake: %{time_appconnect}s\n\
  📤 Pre-transfer: %{time_pretransfer}s\n\
  ⏳ Start Transfer: %{time_starttransfer}s\n\
  ✅ Total: %{time_total}s\n\
  📦 Size: %{size_download} bytes\n\
  🚀 Speed: %{speed_download} bytes/s\n" \
  -o /dev/null -s https://asaas.local/admin-home

echo ""
echo "========================================="
echo "✅ انتهى التحليل المفصل"
echo "========================================="
