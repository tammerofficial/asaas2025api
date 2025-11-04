#!/bin/bash

echo "========================================="
echo "🔍 تقرير فحص أداء الداشبورد - asaas.local"
echo "========================================="
echo ""
echo "📅 التاريخ: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Test 1: Connection Performance
echo "1️⃣ اختبار سرعة الاتصال بالسيرفر"
echo "─────────────────────────────────────────"
time curl -k -w "\n📊 النتائج:\n  - زمن الاتصال: %{time_connect}s\n  - زمن بدء الاستجابة: %{time_starttransfer}s\n  - إجمالي الوقت: %{time_total}s\n  - كود الاستجابة: %{http_code}\n  - حجم البيانات: %{size_download} bytes\n  - السرعة: %{speed_download} bytes/s\n" -o /dev/null -s https://asaas.local/admin-home
echo ""

# Test 2: DNS Resolution
echo "2️⃣ اختبار DNS Resolution"
echo "─────────────────────────────────────────"
time nslookup asaas.local 2>&1 | head -10
echo ""

# Test 3: Multiple Requests Average
echo "3️⃣ اختبار متوسط الأداء (5 طلبات)"
echo "─────────────────────────────────────────"
for i in {1..5}; do
  echo "📌 الطلب #$i:"
  curl -k -w "  ⏱️  الوقت: %{time_total}s | كود: %{http_code} | الحجم: %{size_download} bytes\n" -o /dev/null -s https://asaas.local/admin-home
done
echo ""

# Test 4: Database Performance
echo "4️⃣ فحص أداء قاعدة البيانات"
echo "─────────────────────────────────────────"
cd core && php artisan tinker --execute="
echo '📊 إحصائيات قاعدة البيانات:' . PHP_EOL;
\$start = microtime(true);
\$admins = \App\Models\Admin::count();
\$adminTime = (microtime(true) - \$start) * 1000;
echo '  ✓ عدد المدراء: ' . \$admins . ' (استغرق: ' . round(\$adminTime, 2) . 'ms)' . PHP_EOL;

\$start = microtime(true);
\$users = \App\Models\User::count();
\$userTime = (microtime(true) - \$start) * 1000;
echo '  ✓ عدد المستخدمين: ' . \$users . ' (استغرق: ' . round(\$userTime, 2) . 'ms)' . PHP_EOL;

\$start = microtime(true);
\$tenants = \App\Models\Tenant::whereValid()->count();
\$tenantTime = (microtime(true) - \$start) * 1000;
echo '  ✓ عدد المتاجر: ' . \$tenants . ' (استغرق: ' . round(\$tenantTime, 2) . 'ms)' . PHP_EOL;

\$start = microtime(true);
\$orders = \App\Models\PaymentLogs::orderBy('id','desc')->take(5)->get();
\$orderTime = (microtime(true) - \$start) * 1000;
echo '  ✓ آخر 5 طلبات: ' . \$orders->count() . ' (استغرق: ' . round(\$orderTime, 2) . 'ms)' . PHP_EOL;

\$totalTime = \$adminTime + \$userTime + \$tenantTime + \$orderTime;
echo '  📌 إجمالي وقت استعلامات قاعدة البيانات: ' . round(\$totalTime, 2) . 'ms' . PHP_EOL;
"
cd ..
echo ""

# Test 5: File System Performance
echo "5️⃣ فحص أداء نظام الملفات"
echo "─────────────────────────────────────────"
time ls -lah core/bootstrap/cache/ > /dev/null 2>&1
echo "📁 عدد ملفات الكاش: $(ls -1 core/bootstrap/cache/*.php 2>/dev/null | wc -l)"
echo "📁 حجم مجلد الكاش: $(du -sh core/bootstrap/cache/ 2>/dev/null | cut -f1)"
echo ""

# Test 6: Memory & CPU
echo "6️⃣ فحص الموارد (Memory & CPU)"
echo "─────────────────────────────────────────"
echo "💾 الذاكرة المستخدمة:"
vm_stat | grep "Pages active"
echo ""

# Test 7: Check Laravel Performance
echo "7️⃣ فحص أداء Laravel"
echo "─────────────────────────────────────────"
cd core && php artisan about --only=environment
echo ""

echo "========================================="
echo "✅ انتهى الفحص"
echo "========================================="
