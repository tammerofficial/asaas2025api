# 🔐 دليل الحصول على Token للـ Postman

## الطريقة 1: تلقائية (مُوصى بها) ⭐

Postman Collection محدد بحفظ Token تلقائياً بعد تسجيل الدخول:

### الخطوات:
1. **افتح Postman Collection:**
   - استورد `API_POSTMAN_COLLECTION.json`

2. **حدّث Collection Variables:**
   - اضغط على Collection → Variables
   - حدّث:
     - `admin_email`: admin@example.com
     - `admin_password`: password
     - `base_url`: http://asaas.local
     - `tenant_base_url`: http://tenant1.asaas.local

3. **سجّل الدخول:**
   - شغّل "Central API > Authentication > Login"
   - أو "Tenant API > Authentication > Login"
   
4. **Token سيُحفظ تلقائياً:**
   - سيُحفظ في `central_token` أو `tenant_token`
   - سترى رسالة في Console: `✅ Central token saved: ...`

---

## الطريقة 2: سكريبت Terminal

### استخدام Script:

```bash
cd core
bash get-token.sh
```

**المخرجات:**
```
✅ Central Login successful!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Central Token:
1|xxxxxxxxxxxxxxxxxxxxx...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

**ثم:**
1. انسخ Token
2. في Postman → Collection → Variables
3. الصق في `central_token` أو `tenant_token`

---

## الطريقة 3: يدوية

### 1. Login Request في Postman:

**Central Login:**
```http
POST http://asaas.local/api/central/v1/auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

**Tenant Login:**
```http
POST http://tenant1.asaas.local/api/tenant/v1/auth/login
Content-Type: application/json

{
  "email": "admin@tenant.com",
  "password": "password"
}
```

### 2. Response:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "admin": {...},
    "token": "1|xxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### 3. نسخ Token:
- انسخ القيمة من `data.token`

### 4. إضافة في Postman:
- Collection → Variables
- حدّث `central_token` أو `tenant_token`
- الصق Token

---

## الطريقة 4: cURL

### Central Token:
```bash
curl -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.data.token'
```

### Tenant Token:
```bash
curl -X POST http://tenant1.asaas.local/api/tenant/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tenant.com","password":"password"}' \
  | jq -r '.data.token'
```

---

## ✅ التحقق من Token

بعد الحصول على Token، اختبره:

**في Postman:**
- شغّل "Central API > Authentication > Get Current Admin"
- إذا رأيت بيانات Admin = Token يعمل ✅

**في Terminal:**
```bash
curl -X GET http://asaas.local/api/central/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## 🔧 Troubleshooting

### Token لا يعمل؟
1. تأكد أن Token نسخته كاملة (لا توجد مسافات)
2. تأكد من البداية بـ `Bearer ` (مع مسافة)
3. تحقق من صلاحية Token (قد يكون منتهياً)
4. جرب تسجيل الدخول مرة أخرى

### Login يفشل؟
1. تأكد من بيانات الدخول:
   - Email: admin@example.com
   - Password: password
2. تحقق من أن Server يعمل
3. تحقق من Database connection

### Token لا يُحفظ تلقائياً؟
1. تأكد من تشغيل Login request
2. تحقق من Collection Events (Test Script)
3. افتح Console في Postman لرؤية الأخطاء

---

## 📝 ملاحظات

- **Token lifetime:** غير محدود (حسب إعدادات Sanctum)
- **Token format:** `Bearer {token}`
- **Storage:** يُنصح بحفظ Token في Collection Variables وليس Environment Variables

---

**آخر تحديث:** $(date)

