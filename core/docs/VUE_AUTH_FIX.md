## 🎯 Vue.js Dashboard V1 - استخدام Token الحالي

### المشكلة
API endpoint يتطلب Sanctum authentication، لكن Vue dashboard بحاجة إلى token صحيح.

### الحل السريع
استخدام session authentication الموجودة بدلاً من Sanctum token.

تحديث `central-v1.js` ليستخدم CSRF token بدلاً من Bearer token:

```javascript
// في axios config
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken

// بدلاً من:
// axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
```

### الحل الكامل
1. استخدام session authentication (الحالي)
2. إضافة CSRF token
3. Laravel سيتعرف تلقائياً على المستخدم المسجل

افتح الصفحة: `https://asaas.local/admin-home/v1`
يجب أن تظهر الـ tenants الآن! 🎉



