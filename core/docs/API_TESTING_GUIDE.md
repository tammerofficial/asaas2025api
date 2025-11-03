# 🧪 دليل اختبار API - API Testing Guide

## 📋 محتويات

1. [الاختبار باستخدام cURL](#curl-testing)
2. [الاختبار باستخدام Postman](#postman-testing)
3. [سكريبت الاختبار التلقائي](#automated-testing)
4. [أمثلة cURL](#curl-examples)

---

## 🚀 الاختبار السريع

### استخدام السكريبت التلقائي

```bash
cd core
./test-endpoints.sh
```

### إعداد المتغيرات

```bash
export BASE_URL="http://asaas.local"
export TENANT_DOMAIN="tenant1"
export ADMIN_EMAIL="admin@example.com"
export ADMIN_PASSWORD="password"
export TENANT_EMAIL="admin@tenant.com"
export TENANT_PASSWORD="password"
```

---

## <a name="curl-testing"></a>اختبار API باستخدام cURL

### 1. إعداد المتغيرات

```bash
# Central API
export CENTRAL_API="http://asaas.local/api/central/v1"
export CENTRAL_TOKEN="your-token-here"

# Tenant API
export TENANT_API="http://tenant1.asaas.local/api/tenant/v1"
export TENANT_TOKEN="your-token-here"
```

### 2. الحصول على Token

#### Central API Login
```bash
curl -X POST "$CENTRAL_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

#### Tenant API Login
```bash
curl -X POST "$TENANT_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@tenant.com",
    "password": "password"
  }'
```

### 3. استخراج Token تلقائياً

```bash
# Central Token
CENTRAL_TOKEN=$(curl -s -X POST "$CENTRAL_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | grep -o '"token":"[^"]*' | cut -d'"' -f4)

# Tenant Token
TENANT_TOKEN=$(curl -s -X POST "$TENANT_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@tenant.com","password":"password"}' \
  | grep -o '"token":"[^"]*' | cut -d'"' -f4)
```

---

## <a name="curl-examples"></a>أمثلة cURL

### Central API Examples

#### Get Dashboard
```bash
curl -X GET "$CENTRAL_API/dashboard" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Accept: application/json"
```

#### List Tenants
```bash
curl -X GET "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Accept: application/json"
```

#### Create Tenant
```bash
curl -X POST "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "New Tenant",
    "domain": "new-tenant",
    "user_id": 1,
    "expire_date": "2025-12-31"
  }'
```

#### Get Settings
```bash
curl -X GET "$CENTRAL_API/settings" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Accept: application/json"
```

#### List Media
```bash
curl -X GET "$CENTRAL_API/media" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Accept: application/json"
```

### Tenant API Examples

#### Get Dashboard
```bash
curl -X GET "$TENANT_API/dashboard" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### List Products
```bash
curl -X GET "$TENANT_API/products" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### Create Product
```bash
curl -X POST "$TENANT_API/products" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "New Product",
    "price": 99.99,
    "category_id": 1,
    "status_id": 1
  }'
```

#### List Blogs
```bash
curl -X GET "$TENANT_API/blogs" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### List Services
```bash
curl -X GET "$TENANT_API/services" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### List Brands
```bash
curl -X GET "$TENANT_API/brands" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### Get Sales Report
```bash
curl -X GET "$TENANT_API/sales-reports" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

#### Get Site Analytics
```bash
curl -X GET "$TENANT_API/site-analytics" \
  -H "Authorization: Bearer $TENANT_TOKEN" \
  -H "Accept: application/json"
```

---

## <a name="automated-testing"></a>الاختبار التلقائي

### استخدام السكريبت

```bash
cd core
chmod +x test-endpoints.sh
./test-endpoints.sh
```

### إعداد متغيرات السكريبت

```bash
export BASE_URL="http://asaas.local"
export TENANT_DOMAIN="tenant1"
export ADMIN_EMAIL="admin@example.com"
export ADMIN_PASSWORD="password"
export TENANT_EMAIL="admin@tenant.com"
export TENANT_PASSWORD="password"
```

### مثال على الإخراج

```
==========================================
API Endpoints Testing Script
==========================================

Central API: http://asaas.local/api/central/v1
Tenant API: http://tenant1.asaas.local/api/tenant/v1

=== Central API Tests ===
Testing: Central Login ... PASS (HTTP 200)
Testing: Central Get Me ... PASS (HTTP 200)
Testing: Central Dashboard ... PASS (HTTP 200)
...

=== Tenant API Tests ===
Testing: Tenant Login ... PASS (HTTP 200)
Testing: Tenant Get Me ... PASS (HTTP 200)
Testing: Tenant Dashboard ... PASS (HTTP 200)
...

==========================================
Test Summary
==========================================
Total Tests: 50
Passed: 48
Failed: 0
Skipped: 2

All tests passed!
```

---

## <a name="postman-testing"></a>الاختبار باستخدام Postman

### 1. استيراد Collection

1. افتح Postman
2. اضغط على **Import**
3. اختر ملف `docs/API_POSTMAN_COLLECTION.json`
4. Collection سيتم استيراده مع جميع الـ endpoints

### 2. إعداد Variables

1. افتح Collection Settings
2. اذهب إلى **Variables** tab
3. قم بتحديث المتغيرات:
   - `base_url`: http://asaas.local
   - `tenant_base_url`: http://tenant1.asaas.local
   - `admin_email`: admin@example.com
   - `admin_password`: password

### 3. الحصول على Token

1. افتح **Central API** → **Authentication** → **Central Login**
2. اضغط **Send**
3. Token سيتم حفظه تلقائياً في `central_token`
4. نفس الشيء لـ **Tenant API**

### 4. اختبار Endpoints

- جميع الـ endpoints جاهزة للاستخدام
- Token سيتم إضافته تلقائياً من Variables
- فقط اضغط **Send** وستحصل على النتيجة

---

## 📊 إحصائيات API

### Central API
- **Sections:** 10 sections
- **Endpoints:** 37+ endpoints
- **Controllers:** 11 controllers

### Tenant API
- **Sections:** 40 sections
- **Endpoints:** 198+ endpoints
- **Controllers:** 41 controllers

### Total
- **Total Endpoints:** 235+ endpoints
- **Total Controllers:** 53 controllers
- **Postman Collection:** ✅ 100% مكتمل

---

## 🔍 التحقق من الـ Endpoints

### فحص Routes باستخدام Laravel

```bash
php artisan route:list | grep "api/tenant/v1"
php artisan route:list | grep "api/central/v1"
```

### فحص عدد Routes

```bash
# Tenant Routes
php artisan route:list | grep "api/tenant/v1" | wc -l

# Central Routes
php artisan route:list | grep "api/central/v1" | wc -l
```

---

## ⚠️ ملاحظات مهمة

1. **Base URL**: تأكد من أن BASE_URL صحيح في بيئتك
2. **Tenant Domain**: يجب استبدال `tenant1` بـ domain الـ tenant الخاص بك
3. **Authentication**: جميع الـ endpoints (عدا login) تحتاج token
4. **Content-Type**: يجب إرسال `Content-Type: application/json`
5. **Accept**: يجب إرسال `Accept: application/json`

---

## 🐛 حل المشاكل

### مشكلة: HTTP 000 (Connection Failed)
- تحقق من أن الخادم يعمل
- تحقق من BASE_URL
- تحقق من DNS/hosts file

### مشكلة: HTTP 401 (Unauthorized)
- تحقق من أن Token صحيح
- تحقق من أن Token لم ينتهي
- قم بتسجيل الدخول مرة أخرى

### مشكلة: HTTP 422 (Validation Error)
- تحقق من البيانات المرسلة
- تحقق من الـ validation rules
- راجع Response للتفاصيل

### مشكلة: HTTP 404 (Not Found)
- تحقق من URL
- تحقق من Route موجود
- تحقق من Tenant context

---

## 📚 موارد إضافية

- **API Documentation:** `docs/API_DOCUMENTATION.md`
- **API Coverage Report:** `docs/API_COVERAGE_REPORT.md`
- **Implementation Status:** `docs/API_IMPLEMENTATION_STATUS_REPORT.md`
- **Postman Collection:** `docs/API_POSTMAN_COLLECTION.json`

---

**Last Updated:** 2025-11-03

