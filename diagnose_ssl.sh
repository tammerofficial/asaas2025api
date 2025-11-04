#!/bin/bash

echo "========================================="
echo "🔍 تشخيص مشكلة البطء في الاتصال"
echo "========================================="
echo ""

# Check SSL Certificate
echo "1️⃣ فحص شهادة SSL"
echo "─────────────────────────────────────────"
echo | openssl s_client -connect asaas.local:443 -servername asaas.local 2>&1 | grep -E "Verify return code|subject=|issuer=" | head -10
echo ""

# Check DNS Performance
echo "2️⃣ فحص أداء DNS"
echo "─────────────────────────────────────────"
time host asaas.local 2>&1
echo ""

# Check /etc/hosts
echo "3️⃣ فحص /etc/hosts"
echo "─────────────────────────────────────────"
grep asaas.local /etc/hosts || echo "⚠️ لا يوجد إدخال في /etc/hosts"
echo ""

# Test with IP directly
echo "4️⃣ اختبار الاتصال المباشر بـ 127.0.0.1"
echo "─────────────────────────────────────────"
time curl -k -w "\n⏱️  Total: %{time_total}s | Connect: %{time_connect}s | SSL: %{time_appconnect}s\n" \
  -o /dev/null -s \
  -H "Host: asaas.local" \
  https://127.0.0.1/admin-home 2>&1 | grep -E "time|Total|real"
echo ""

# Test without SSL
echo "5️⃣ اختبار بدون SSL (HTTP)"
echo "─────────────────────────────────────────"
time curl -w "\n⏱️  Total: %{time_total}s | Connect: %{time_connect}s\n" \
  -o /dev/null -s \
  http://asaas.local/admin-home 2>&1 | grep -E "time|Total|real"
echo ""

# Check if it's IPv6 issue
echo "6️⃣ فحص مشكلة IPv6"
echo "─────────────────────────────────────────"
echo "اختبار IPv4 فقط:"
time curl -4 -k -w "⏱️  Total: %{time_total}s\n" -o /dev/null -s https://asaas.local/admin-home 2>&1 | grep -E "time|Total|real"
echo ""

# Check TCP connection time
echo "7️⃣ فحص وقت اتصال TCP مباشرة"
echo "─────────────────────────────────────────"
time nc -zv -G 5 asaas.local 443 2>&1
echo ""

# Check if there's a proxy
echo "8️⃣ فحص إعدادات Proxy"
echo "─────────────────────────────────────────"
env | grep -i proxy || echo "✅ لا يوجد proxy مفعل"
echo ""

# Check Laravel .env
echo "9️⃣ فحص إعدادات Laravel"
echo "─────────────────────────────────────────"
cd core
echo "APP_URL: $(grep ^APP_URL .env 2>/dev/null || echo 'غير موجود')"
echo "APP_ENV: $(grep ^APP_ENV .env 2>/dev/null || echo 'غير موجود')"
echo "APP_DEBUG: $(grep ^APP_DEBUG .env 2>/dev/null || echo 'غير موجود')"
echo "SESSION_DRIVER: $(grep ^SESSION_DRIVER .env 2>/dev/null || echo 'غير موجود')"
cd ..
echo ""

# Test localhost comparison
echo "🔟 مقارنة: asaas.local vs localhost"
echo "─────────────────────────────────────────"
echo "🌐 asaas.local:"
curl -k -w "  ⏱️  %{time_total}s\n" -o /dev/null -s https://asaas.local/admin-home 2>&1
echo ""
echo "🏠 127.0.0.1:"
curl -k -w "  ⏱️  %{time_total}s\n" -o /dev/null -s -H "Host: asaas.local" https://127.0.0.1/admin-home 2>&1
echo ""

echo "========================================="
echo "✅ انتهى التشخيص"
echo "========================================="
