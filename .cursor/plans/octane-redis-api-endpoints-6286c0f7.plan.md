<!-- 6286c0f7-f1f5-46ea-9652-2ac6c5d984a4 dbd9e86b-4e96-481e-87ca-f2e7ff4566f4 -->
# إضافة Octane و Redis للـ API Endpoints

## نظرة عامة

تثبيت وإعداد Laravel Octane مع RoadRunner و Redis caching لجميع API endpoints في WebApiController لتحسين الأداء بشكل كبير.

## الحالة الحالية

- Octane غير مثبت (لا يوجد في composer.json)
- Redis موجود ومُكوّن في config/database.php
- WebApiController لا يستخدم caching حالياً
- يوجد OctaneTenantIsolation middleware موجود
- يوجد BaseApiController به caching helpers لكن WebApiController لا يستخدمه

## الخطوات التنفيذية

### 1. تثبيت Laravel Octane

#### 1.1 إضافة Octane إلى composer.json

- إضافة `"laravel/octane": "^2.0"` إلى require
- تشغيل `composer require laravel/octane`

#### 1.2 تثبيت RoadRunner

- تشغيل `php artisan octane:install --server=roadrunner`
- هذا ينشئ ملف `config/octane.php` تلقائياً

#### 1.3 تحديث Octane Config

- تحديث `config/octane.php` لضمان التوافق مع multi-tenancy
- إضافة OctaneTenantIsolation middleware إلى Octane middleware list

### 2. إضافة Redis Caching للـ WebApiController

#### 2.1 تحديث WebApiController

- جعل WebApiController extends BaseApiController أو إضافة caching methods
- إضافة caching لكل endpoint حسب نوع البيانات:
  - **Stats endpoints** (dashboardStats, chartData): 1-3 دقائق
  - **Lists endpoints** (tenants, orders, packages, etc.): 5-10 دقائق  
  - **Details endpoints** (getTenant, getOrder, etc.): 15-30 دقيقة

#### 2.2 إضافة Cache Keys

- استخدام مفتاح cache واضح لكل endpoint
- Format: `api:v1:{endpoint}:{params_hash}`
- مثال: `api:v1:dashboard:stats`, `api:v1:tenants:page_1_search_`

#### 2.3 Cache Invalidation

- إضافة cache clearing عند Create/Update/Delete operations
- استخدام cache tags إذا أمكن (Redis 2.6+)

### 3. تحديث Routes Middleware

#### 3.1 إضافة OctaneTenantIsolation

- التأكد من تطبيق OctaneTenantIsolation middleware على API routes
- تحديث `routes/admin.php` إذا لزم الأمر

### 4. إعداد Environment Variables

#### 4.1 تحديث .env

- إضافة `OCTANE_SERVER=roadrunner`
- التأكد من `CACHE_DRIVER=redis`
- التأكد من Redis connection settings

### 5. Testing

#### 5.1 اختبار Octane

- تشغيل `php artisan octane:start --server=roadrunner`
- اختبار API endpoints للتأكد من عملها

#### 5.2 اختبار Redis Caching

- اختبار endpoints للتأكد من caching
- التحقق من cache hits في Redis

## ملفات التعديل

### ملفات جديدة

- `core/config/octane.php` (سيُنشأ تلقائياً عند التثبيت)

### ملفات التعديل

- `core/composer.json` - إضافة Octane package
- `core/app/Http/Controllers/Central/V1/WebApiController.php` - إضافة Redis caching
- `core/routes/admin.php` - إضافة OctaneTenantIsolation middleware (إذا لزم الأمر)
- `.env` - إضافة Octane و Redis settings

## تفاصيل Caching Strategy

### Stats Endpoints (1-3 دقائق)

- `dashboardStats()`: 2 دقيقة
- `chartData()`: 3 دقائق (أطول لأنها تتضمن حسابات)
- `recentOrders()`: 1 دقيقة

### Lists Endpoints (5-10 دقائق)

- `tenants()`: 10 دقائق (search params في cache key)
- `packages()`: 10 دقائق
- `orders()`: 5 دقائق (تتغير أكثر)
- `payments()`: 5 دقائق
- `admins()`: 10 دقائق
- `blogs()`: 10 دقائق
- `pages()`: 10 دقائق
- `coupons()`: 10 دقائق
- `supportTickets()`: 5 دقائق (تتغير أكثر)

### Details Endpoints (15-30 دقيقة)

- `getTenant($id)`: 30 دقيقة
- `getOrder($id)`: 15 دقيقة (قد تتغير)
- `getPackage($id)`: 30 دقيقة
- `getBlog($id)`: 30 دقيقة
- `getPage($id)`: 30 دقيقة
- `supportTicketDetails($id)`: 15 دقيقة

### Create/Update/Delete Operations

- Clear cache عند Create/Update/Delete
- استخدام cache pattern matching: `api:v1:{resource}:*`

## ملاحظات مهمة

1. **Octane Tenant Isolation**: التأكد من استخدام OctaneTenantIsolation middleware لمنع memory leaks
2. **Cache Keys**: استخدام tenant-specific cache keys إذا لزم الأمر
3. **Cache Tags**: استخدام Cache Tags بدلاً من pattern matching - أسرع وأكثر scalability
4. **Trait Pattern**: استخدام UsesApiCache trait لتجنب تكرار الكود في 70 endpoint
5. **Fallback**: إذا فشل Redis، fallback إلى default cache driver
6. **Testing**: اختبار شامل بعد التطبيق للتأكد من عدم وجود مشاكل

## تحسينات مستقبلية (لاحقاً)

### Auto-Adaptive TTL

- إضافة dynamic TTL حسب الـ load
- عندما يكون الـ traffic قليل → TTL أعلى
- عندما يكون الـ traffic عالي → TTL أقل
- مراقبة الـ request rate وضبط TTL تلقائياً
- ليس الآن، تحسين مستقبلي

## الأوامر المطلوبة

```bash
cd core
composer require laravel/octane
php artisan octane:install --server=roadrunner
php artisan config:clear
php artisan cache:clear
php artisan octane:start --server=roadrunner
```