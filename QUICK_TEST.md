# فحص Tenants من خلال API - دليل سريع

## الطريقة الأولى: استخدام السكريبت

### 1. تشغيل السكريبت البسيط

```bash
cd /Users/alialalawi/Sites/localhost/asaas
./check-tenants-simple.sh your-admin@email.com your-password
```

أو بدون كلمات المرور (سيستخدم القيم الافتراضية):
```bash
./check-tenants-simple.sh
```

### 2. تشغيل السكريبت الشامل

```bash
cd core
./check-tenants.sh your-admin@email.com your-password
```

## الطريقة الثانية: استخدام cURL مباشرة

### الخطوة 1: تسجيل الدخول والحصول على Token

```bash
curl -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"your-admin@email.com","password":"your-password"}'
```

**احفظ الـ token من الرد**

### الخطوة 2: عرض قائمة Tenants

```bash
# استبدل {TOKEN} بالـ token الذي حصلت عليه
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

## الطريقة الثالثة: One-liner (سطر واحد)

```bash
# احصل على Token وقائمة Tenants في أمر واحد
TOKEN=$(curl -s -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"your-admin@email.com","password":"your-password"}' | \
  grep -o '"token":"[^"]*' | sed 's/"token":"//') && \
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"
```

## أمثلة إضافية

### عرض معلومات tenant معين

```bash
# استبدل {ID} بـ ID الـ tenant
curl -X GET "http://asaas.local/api/central/v1/tenants/{ID}" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

### استخدام jq لعرض أجمل (إذا كان مثبتاً)

```bash
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json" | jq '.data[] | {id, name, domain, is_active, user: .user.email}'
```

## ملاحظات

1. **تأكد من تحديث بيانات اعتماد Admin** في الأوامر
2. **الـ Base URL** الافتراضي هو `http://asaas.local` - يمكن تغييره
3. **Token** صالح حتى تسجيل الخروج أو انتهاء صلاحيته
4. للتفاصيل الكاملة راجع `core/API_DOCUMENTATION_CENTRAL.md`

