<!-- 1f9106df-cea4-4233-bea0-ffaa94651754 1be6955f-cb69-4373-a741-9932dad88a0c -->
# خطة تحسين أداء PageBuilder مع Redis و Octane

## الهدف

تحسين أداء PageBuilder عند كثرة الحفظ والتحديث باستخدام:

- Redis للـ Caching السريع (Write-Behind Pattern)
- Queue للـ Database writes في الخلفية
- تحسين Preview Loading مع Redis Cache
- ضمان العزل الكامل بين Tenants

## المراحل

### المرحلة 1: إنشاء Queue Job للـ Database Writes

**الملف:** `core/app/Jobs/SavePageBuilderJob.php`

- Job جديد لحفظ PageBuilder في Database عبر Queue
- معالجة البيانات بشكل آمن مع عزل Tenants
- Error handling و Retry logic

**الملف:** `core/app/Jobs/DeletePageBuilderJob.php`

- Job لحذف PageBuilder من Database
- معالجة Cache cleanup

### المرحلة 2: تحديث Controllers للـ Redis Caching

**الملفات:**

- `core/app/Http/Controllers/Landlord/Admin/PageBuilderController.php`
- `core/app/Http/Controllers/Tenant/Admin/HeaderBuilderController.php`
- `core/app/Http/Controllers/Tenant/Admin/FooterBuilderController.php`

**التحسينات:**

- حفظ فوري في Redis عند `store` و `update`
- إرسال Job للـ Queue لحفظ Database في الخلفية
- استخدام `TenantCacheServiceProvider::getTenantCacheKey()` للعزل
- Invalid Preview Cache تلقائياً

### المرحلة 3: تحسين PageBuilder Model

**الملف:** `core/app/Models/PageBuilder.php`

- تحديث `get_settings()` لقراءة من Redis أولاً
- Fallback للـ Database إذا Redis غير متوفر
- تحسين `clearPageBuilderCache()` لاستخدام Redis patterns

### المرحلة 4: تحسين PageBuilderBase للـ Cache

**الملف:** `core/plugins/PageBuilder/PageBuilderBase.php`

- تحديث `get_settings()` لاستخدام Redis Cache
- Cache key generation محسّن
- Fallback strategy

### المرحلة 5: إضافة Cache Helper Functions

**الملف:** `core/app/Helpers/pagebuilder_cache_helpers.php`

- Helper functions للـ PageBuilder caching
- `pagebuilder_cache_remember()` - Cache with fallback
- `pagebuilder_cache_forget_pattern()` - Clear cache by pattern
- `pagebuilder_cache_warm()` - Pre-cache widgets

### المرحلة 6: تحديث Configuration

**الملف:** `core/config/cache-tenancy.php`

- إضافة TTL مخصصة لـ PageBuilder widgets
- إضافة tags للـ PageBuilder cache

**الملف:** `core/config/queue.php`

- التأكد من وجود `redis` connection
- إضافة queue مخصصة لـ PageBuilder

### المرحلة 7: تحسين Frontend Rendering

**الملفات:**

- `core/plugins/PageBuilder/PageBuilderSetup.php`
- Views التي تستخدم `frontend_render()`

**التحسينات:**

- Cache widget settings في Redis
- استخدام `remember()` للـ widget rendering
- Cache invalidation عند التحديث

### المرحلة 8: Testing & Monitoring

**الملفات:**

- Tests للـ Cache isolation
- Tests للـ Queue jobs
- Performance benchmarks

## التوقعات

### قبل التحسين:

- Save Widget: 200-500ms
- Preview Load: 300-600ms
- Database writes: Blocking

### بعد التحسين:

- Save Widget: 5-10ms (40-100x أسرع)
- Preview Load: 10-20ms (15-30x أسرع)
- Database writes: Non-blocking (Queue)

## ملاحظات الأمان

- كل Tenant له cache منفصل (عزل كامل)
- Fallback للـ Database إذا Redis فشل
- Queue jobs تتعامل مع Tenant context بشكل آمن
- Cache keys تستخدم Tenant ID للعزل

## التراجع

- يمكن تعطيل Redis caching عبر env variable
- يمكن الرجوع للـ Database مباشرة
- لا يوجد breaking changes في APIs

### To-dos

- [ ] إنشاء SavePageBuilderJob و DeletePageBuilderJob للـ Database writes في الخلفية
- [ ] تحديث PageBuilderController لاستخدام Redis Caching و Queue
- [ ] تحديث HeaderBuilderController و FooterBuilderController للـ Redis Caching
- [ ] تحسين PageBuilder Model لقراءة من Redis أولاً مع Fallback
- [ ] تحسين PageBuilderBase.get_settings() لاستخدام Redis Cache
- [ ] إنشاء Helper functions للـ PageBuilder caching
- [ ] تحديث cache-tenancy.php بإعدادات PageBuilder
- [ ] تحسين Frontend rendering مع Redis Cache
- [ ] إضافة Tests للـ Cache isolation و Queue jobs