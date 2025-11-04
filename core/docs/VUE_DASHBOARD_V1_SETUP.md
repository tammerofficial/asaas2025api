# 🎯 Vue.js Dashboard V1 Setup

## 📋 Overview

تم إنشاء Dashboard مركزي جديد باستخدام Vue.js مع إصدار V1 للاختبار والمقارنة مع الداشبورد الحالي.

## 🚀 الميزات

- ✅ Vue 3 + Vue Router 4
- ✅ SPA (Single Page Application)
- ✅ صفحتين تجريبيتين:
  - Dashboard Page - عرض الإحصائيات
  - Tenants Page - إدارة المستأجرين
- ✅ تصميم حديث مع TailwindCSS-like styling
- ✅ تكامل مع API المركزي

## 📁 الملفات المُنشأة

### Vue.js Files
```
resources/js/central/
├── app.js (لم يتم استخدامه - تم إنشاء central-v1.js بدلاً منه)
├── central-v1.js (Entry point للـ Vue app)
├── App.vue (Root component)
├── layouts/
│   └── DashboardLayout.vue (Layout مع sidebar و header)
└── pages/
    ├── DashboardPage.vue (صفحة Dashboard الرئيسية)
    └── TenantsPage.vue (صفحة إدارة Tenants)
```

### Laravel Files
```
app/Http/Controllers/Central/V1/
└── VueDashboardController.php (Controller للـ Vue dashboard)

resources/views/central/v1/
└── dashboard.blade.php (Blade template للـ Vue app)
```

### CSS
```
resources/css/
└── central-v1.css (Styles للـ Vue dashboard)
```

## 🔗 الوصول للـ Dashboard

### URL
```
http://asaas.local/admin-home/v1
```

أو حسب الدومين الخاص بك:
```
http://your-domain.com/admin-home/v1
```

## 📦 Dependencies

تم إضافة:
- `vue-router@^4.6.3` - للـ routing

## ⚙️ الإعداد

### 1. تثبيت Dependencies
```bash
cd core
npm install
```

### 2. Build Assets
```bash
npm run dev
```

أو للـ production:
```bash
npm run build
```

### 3. الوصول للـ Dashboard

1. تسجيل الدخول كـ Admin في `/admin-home`
2. اذهب إلى `/admin-home/v1` للوصول للـ Vue Dashboard

## 🔐 Authentication

الـ Dashboard يستخدم:
- **Sanctum Token** من `localStorage.getItem('central_token')`
- أو من `window.centralAuthToken` إذا كان متوفراً

### ملاحظات:
- حالياً الـ authentication يتم التحقق منه في `router.beforeEach`
- يمكن إضافة login page لاحقاً

## 📊 API Integration

الـ Dashboard يتصل بـ:
- Base URL: `/api/central/v1`
- Endpoints:
  - `GET /dashboard/stats` - إحصائيات Dashboard
  - `GET /tenants` - قائمة Tenants

## 🎨 التصميم

- **Sidebar**: نافذة جانبية مع navigation
- **Top Bar**: Header مع title و user menu
- **Stats Cards**: بطاقات إحصائية ملونة
- **Tables**: جداول responsive مع pagination

## 🔄 المقارنة مع الداشبورد الحالي

| Feature | Dashboard الحالي | Vue Dashboard V1 |
|---------|------------------|------------------|
| Technology | Blade | Vue.js SPA |
| Routing | Server-side | Client-side |
| API Calls | Mixed | API only |
| Interactivity | Low | High |
| Performance | Good | Better |
| Development | Traditional | Modern |

## 🚧 الخطوات القادمة (Optional)

1. ✅ إضافة صفحة Login
2. ✅ إضافة المزيد من الصفحات (Plans, Orders, etc.)
3. ✅ إضافة Charts باستخدام Chart.js أو Vue-Chartjs
4. ✅ إضافة Real-time updates
5. ✅ إضافة Toast notifications
6. ✅ تحسين الـ authentication flow

## 📝 ملاحظات

- الـ Dashboard V1 منفصل تماماً عن الداشبورد الحالي
- يمكن استخدامهما معاً بدون مشاكل
- إذا أعجبك التصميم، يمكن توسيعه ليشمل جميع الصفحات

