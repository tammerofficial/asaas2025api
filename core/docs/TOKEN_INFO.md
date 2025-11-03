# 🔐 Token Information

**Date:** 2025-11-03  
**Email:** alalawi310@gmail.com

---

## 📋 Central API Token

```
14|fi6YhSb66YepusQGhXMZNSCEKVSR6UDQu0FSLCaD6dc4056d
```

### ✅ Status
- ✅ Token saved to Postman Collection
- ✅ Token is valid and ready to use
- ✅ Base URL: https://asaas.local

---

## 🚀 كيفية الاستخدام

### في Postman
1. افتح Postman Collection
2. Token موجود تلقائياً في variable `central_token`
3. جميع الـ requests ستستخدم Token تلقائياً

### في cURL
```bash
export CENTRAL_TOKEN="14|fi6YhSb66YepusQGhXMZNSCEKVSR6UDQu0FSLCaD6dc4056d"

curl -X GET "https://asaas.local/api/central/v1/dashboard" \
  -H "Authorization: Bearer $CENTRAL_TOKEN" \
  -H "Accept: application/json"
```

### في Postman Collection Variables
- `base_url`: https://asaas.local
- `central_token`: 14|fi6YhSb66YepusQGhXMZNSCEKVSR6UDQu0FSLCaD6dc4056d
- `admin_email`: alalawi310@gmail.com
- `admin_password`: 12345678

---

## ⚠️ ملاحظات

1. **Token Expiration**: Token قد ينتهي بعد فترة، قم بتسجيل الدخول مرة أخرى
2. **HTTPS**: يجب استخدام HTTPS (https://asaas.local)
3. **Tenant Token**: للحصول على Tenant token، قم بتسجيل الدخول من tenant domain

---

**Last Updated:** 2025-11-03

