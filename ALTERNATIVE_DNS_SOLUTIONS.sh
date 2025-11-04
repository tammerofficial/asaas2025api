#!/bin/bash

# =============================================================================
# 🔧 حلول بديلة لمشكلة DNS - asaas.local
# =============================================================================

echo "========================================="
echo "🔧 حلول بديلة لمشكلة DNS"
echo "========================================="
echo ""

# =============================================================================
# الحل البديل #1: استخدام dscacheutil
# =============================================================================
echo "1️⃣ حل #1: تنظيف DNS Cache في macOS"
echo "─────────────────────────────────────────"
echo "⚠️  يتطلب صلاحيات sudo"
echo ""
echo "الأمر:"
echo "sudo dscacheutil -flushcache"
echo "sudo killall -HUP mDNSResponder"
echo ""
read -p "هل تريد تنفيذ هذا الحل؟ (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    sudo dscacheutil -flushcache
    sudo killall -HUP mDNSResponder
    echo "✅ تم تنظيف DNS cache"
    echo "🧪 اختبار النتيجة..."
    time curl -k -w "\n⏱️  Total: %{time_total}s\n" -o /dev/null -s https://asaas.local/admin-home
fi
echo ""

# =============================================================================
# الحل البديل #2: تعديل resolv.conf
# =============================================================================
echo "2️⃣ حل #2: استخدام Google DNS"
echo "─────────────────────────────────────────"
echo "💡 يمكنك تغيير DNS في إعدادات الشبكة:"
echo ""
echo "   1. System Settings → Network"
echo "   2. اختر الاتصال النشط (Wi-Fi/Ethernet)"
echo "   3. Details → DNS"
echo "   4. أضف:"
echo "      - 8.8.8.8"
echo "      - 8.8.4.4"
echo ""
echo "⏸️  تم تخطي هذا الحل (يدوي)"
echo ""

# =============================================================================
# الحل البديل #3: استخدام IP مباشرة
# =============================================================================
echo "3️⃣ حل #3: استخدام IP مباشرة في Laravel"
echo "─────────────────────────────────────────"
echo ""
read -p "هل تريد تطبيق هذا الحل؟ (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    cd core
    
    # Backup
    cp .env .env.backup.ip.$(date +%Y%m%d_%H%M%S)
    
    # تعديل APP_URL
    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i '' 's|APP_URL=https://asaas.local|APP_URL=https://127.0.0.1|' .env
    else
        sed -i 's|APP_URL=https://asaas.local|APP_URL=https://127.0.0.1|' .env
    fi
    
    echo "✅ تم تغيير APP_URL إلى 127.0.0.1"
    
    # Clear cache
    php artisan config:clear
    php artisan cache:clear
    
    echo "🧪 اختبار النتيجة..."
    time curl -k -w "\n⏱️  Total: %{time_total}s\n" -o /dev/null -s -H "Host: asaas.local" https://127.0.0.1/admin-home
    
    cd ..
fi
echo ""

# =============================================================================
# الحل البديل #4: تعطيل IPv6
# =============================================================================
echo "4️⃣ حل #4: إجبار استخدام IPv4 فقط"
echo "─────────────────────────────────────────"
echo "🧪 اختبار مع IPv4 فقط..."
time curl -4 -k -w "\n⏱️  Total: %{time_total}s\n" -o /dev/null -s https://asaas.local/admin-home
echo ""
echo "💡 إذا كان الأداء أفضل مع -4، يمكن تعطيل IPv6 في الشبكة"
echo ""

# =============================================================================
# الحل البديل #5: استخدام Nginx Reverse Proxy
# =============================================================================
echo "5️⃣ حل #5: إعداد Nginx Reverse Proxy"
echo "─────────────────────────────────────────"
echo "⏸️  حل متقدم - يتطلب تكوين يدوي"
echo ""
echo "📝 مثال على تكوين Nginx:"
cat << 'NGINX_CONF'

server {
    listen 80;
    server_name asaas.local;
    
    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}

NGINX_CONF
echo ""

# =============================================================================
# الحل البديل #6: ServBay DNS Settings
# =============================================================================
echo "6️⃣ حل #6: تعديل إعدادات ServBay DNS"
echo "─────────────────────────────────────────"
echo "💡 خطوات يدوية:"
echo ""
echo "   1. افتح ServBay"
echo "   2. Settings → DNS"
echo "   3. قم بأحد الإجراءات التالية:"
echo "      a. قلل DNS Timeout إلى 1s"
echo "      b. عطّل ServBay DNS واستخدم System DNS"
echo "      c. أضف asaas.local كـ static entry"
echo ""
echo "⏸️  تم تخطي (يدوي)"
echo ""

# =============================================================================
# الحل البديل #7: استخدام localhost.test
# =============================================================================
echo "7️⃣ حل #7: استخدام localhost.test بدلاً من .local"
echo "─────────────────────────────────────────"
echo "💡 .local يمكن أن يسبب مشاكل مع mDNS في macOS"
echo ""
echo "الخطوات:"
echo "   1. في ServBay، غيّر المجال من asaas.local إلى asaas.test"
echo "   2. في Laravel .env:"
echo "      APP_URL=https://asaas.test"
echo ""
echo "⏸️  تم تخطي (يتطلب إعادة تكوين)"
echo ""

# =============================================================================
# ملخص الحلول
# =============================================================================
echo "========================================="
echo "📋 ملخص الحلول"
echo "========================================="
echo ""
echo "✅ حلول تم تطبيقها:"
echo "   - تنظيف DNS cache"
echo ""
echo "📝 حلول يدوية مقترحة:"
echo "   1. تعديل /etc/hosts (الأسهل)"
echo "   2. تغيير DNS إلى 8.8.8.8 (جيد)"
echo "   3. تعديل ServBay DNS settings (موصى به)"
echo "   4. استخدام .test بدلاً من .local (مثالي)"
echo ""
echo "🧪 اختبار نهائي..."
echo "─────────────────────────────────────────"
for i in {1..3}; do
    TIME=$(curl -k -w "%{time_total}" -o /dev/null -s https://asaas.local/admin-home 2>&1)
    echo "الاختبار #$i: ${TIME}s"
done
echo ""
echo "========================================="
echo "✅ انتهى"
echo "========================================="

