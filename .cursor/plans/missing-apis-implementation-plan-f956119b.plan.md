<!-- f956119b-b3ec-400b-85e7-ee00407d466f cd8526f4-c792-496a-a674-0f2f15600655 -->
# خطة تنفيذ الـ APIs المتبقية - Missing APIs Implementation Plan

## نظرة عامة

**الوضع الحالي:**

- ✅ 14 API Controllers موجودة (45+ endpoints)
- ❌ 27 Modules بدون API (~170+ endpoints مطلوبة)
- **Coverage:** ~18-20%

**الهدف:**

تنفيذ APIs لجميع الـ Modules المتبقية على 3 مراحل حسب الأولوية

---

## المرحلة 1 (Priority 1) - ميزات أساسية

### Phase 1.1: Blog API (Tenant)

**المطلوب:** 2 Controllers + 13 endpoints

**ملفات للإنشاء:**

- `core/app/Http/Controllers/Api/Tenant/BlogController.php`
- `core/app/Http/Controllers/Api/Tenant/BlogCategoryController.php`
- `core/app/Http/Requests/Api/Tenant/Blog/StoreBlogRequest.php`
- `core/app/Http/Requests/Api/Tenant/Blog/UpdateBlogRequest.php`
- `core/app/Http/Requests/Api/Tenant/Blog/Category/StoreBlogCategoryRequest.php`
- `core/app/Http/Requests/Api/Tenant/Blog/Category/UpdateBlogCategoryRequest.php`
- `core/app/Http/Resources/Api/Tenant/BlogResource.php`
- `core/app/Http/Resources/Api/Tenant/BlogCategoryResource.php`

**Routes:** إضافة إلى `core/routes/api/tenant.php`

```php
Route::apiResource('blogs', BlogController::class);
Route::apiResource('blog-categories', BlogCategoryController::class);
Route::post('blogs/{blog}/publish', [BlogController::class, 'publish']);
Route::post('blogs/{blog}/unpublish', [BlogController::class, 'unpublish']);
Route::get('blog-categories/{category}/blogs', [BlogCategoryController::class, 'blogs']);
```

**Model:** `Modules\Blog\Entities\Blog`

**Endpoints:** 13 endpoints

---

### Phase 1.2: Pages API (Tenant)

**المطلوب:** 1 Controller + 6 endpoints

**ملفات للإنشاء:**

- `core/app/Http/Controllers/Api/Tenant/PageController.php`
- `core/app/Http/Requests/Api/Tenant/Page/StorePageRequest.php`
- `core/app/Http/Requests/Api/Tenant/Page/UpdatePageRequest.php`
- `core/app/Http/Resources/Api/Tenant/PageResource.php`

**Routes:** إضافة إلى `core/routes/api/tenant.php`

```php
Route::apiResource('pages', PageController::class);
Route::post('pages/{page}/publish', [PageController::class, 'publish']);
```

**Model:** `App\Models\Page`

**Endpoints:** 6 endpoints

---

### Phase 1.3: Media Upload API (Tenant & Central)

**المطلوب:** 1 Controller (Tenant) + 1 Controller (Central) + 14 endpoints

**ملفات للإنشاء:**

- `core/app/Http/Controllers/Api/Tenant/MediaController.php`
- `core/app/Http/Controllers/Api/Central/MediaController.php`
- `core/app/Http/Requests/Api/Tenant/Media/UploadMediaRequest.php`
- `core/app/Http/Requests/Api/Tenant/Media/BulkDeleteMediaRequest.php`
- `core/app/Http/Resources/Api/Tenant/MediaResource.php`
- `core/app/Http/Resources/Api/Central/MediaResource.php`

**Routes:**

- إضافة إلى `core/routes/api/tenant.php`
- إضافة إلى `core/routes/api/central.php`

**Model:** `App\Models\MediaUploader`

**Endpoints:** 7 endpoints (Tenant) + 7 endpoints (Central) = 14 endpoints

---

### Phase 1.4: Settings API (Central & Tenant)

**المطلوب:** 2 Controllers + 8 endpoints

**ملفات للإنشاء:**

- `core/app/Http/Controllers/Api/Central/SettingsController.php`
- `core/app/Http/Controllers/Api/Tenant/SettingsController.php`
- `core/app/Http/Requests/Api/Central/Settings/UpdateSettingsRequest.php`
- `core/app/Http/Requests/Api/Tenant/Settings/UpdateSettingsRequest.php`
- `core/app/Http/Resources/Api/Central/SettingsResource.php`
- `core/app/Http/Resources/Api/Tenant/SettingsResource.php`

**Routes:**

- إضافة إلى `core/routes/api/central.php`
- إضافة إلى `core/routes/api/tenant.php`

**Models:** `App\Models\StaticOptionCentral`, `App\Models\StaticOption`

**Endpoints:** 4 endpoints (Central) + 4 endpoints (Tenant) = 8 endpoints

**Phase 1 Total:** 4 modules, 6 controllers, ~41 endpoints

---

## المرحلة 2 (Priority 2) - ميزات مهمة

### Phase 2.1: Coupons API (Tenant)

**ملفات:** CouponController + Form Requests + Resource

**Model:** `Modules\CouponManage\Entities\Coupon`

**Endpoints:** 8 endpoints

### Phase 2.2: Shipping API (Tenant)

**ملفات:** ShippingZoneController + ShippingMethodController + Form Requests + Resources

**Models:** `Modules\ShippingModule\Entities\ShippingZone`, `Modules\ShippingModule\Entities\ShippingMethod`

**Endpoints:** 11 endpoints

### Phase 2.3: Inventory API (Tenant)

**ملفات:** InventoryController + Form Requests + Resource

**Model:** `Modules\Inventory\Entities\Inventory`

**Endpoints:** 6 endpoints

### Phase 2.4: Wallet API (Tenant)

**ملفات:** WalletController + Form Requests + Resource

**Model:** `Modules\Wallet\Entities\Wallet`

**Endpoints:** 5 endpoints

### Phase 2.5: SupportTicket API (Tenant & Central)

**ملفات:** SupportTicketController (Tenant) + SupportTicketController (Central) + Form Requests + Resources

**Models:** `App\Models\SupportTicket`, `App\Models\SupportTicketMessage`

**Endpoints:** 14 endpoints (7 + 7)

### Phase 2.6: Reports API (Central & Tenant)

**ملفات:** ReportController (Central) + ReportController (Tenant) + Resources

**Endpoints:** 8 endpoints (4 + 4)

**Phase 2 Total:** 6 modules, 8 controllers, ~70+ endpoints (Full CRUD + Custom Actions)

---

## المرحلة 3 (Priority 3) - ميزات إضافية

### Phase 3.1: Reviews API (Tenant)

**ملفات:** ProductReviewController + Form Requests + Resource

**Model:** `Modules\Product\Entities\ProductReviews`

**Endpoints:** 6 endpoints

### Phase 3.2: Refund API (Tenant)

**ملفات:** RefundController + Form Requests + Resource

**Models:** `Modules\RefundModule\Entities\RefundProduct`, `Modules\RefundModule\Entities\RefundChat`

**Endpoints:** 6 endpoints

### Phase 3.3: Tax API (Tenant)

**ملفات:** TaxController + Form Requests + Resource

**Model:** `Modules\TaxModule\Entities\TaxClass`

**Endpoints:** 5 endpoints

### Phase 3.4: NewsLetter API (Tenant)

**ملفات:** NewsletterController + Form Requests + Resource

**Model:** `App\Models\Newsletter`

**Endpoints:** 6 endpoints

### Phase 3.5: Badge API (Tenant)

**ملفات:** BadgeController + Form Requests + Resource

**Model:** `Modules\Badge\Entities\Badge`

**Endpoints:** 4 endpoints

### Phase 3.6: Campaign API (Tenant)

**ملفات:** CampaignController + Form Requests + Resource

**Model:** `Modules\Campaign\Entities\Campaign`

**Endpoints:** 6 endpoints

### Phase 3.7: DigitalProduct API (Tenant)

**ملفات:** DigitalProductController + Form Requests + Resource

**Model:** `Modules\DigitalProduct\Entities\DigitalProduct`

**Endpoints:** 6 endpoints

### Phase 3.8: Other Modules

- CountryManage (3 endpoints)
- Service (5 endpoints)
- SalesReport (3 endpoints)
- SiteAnalytics (4 endpoints)
- وغيرها من Modules حسب الحاجة

**Phase 3 Total:** ~17 modules, 20+ controllers, ~80+ endpoints

---

## معايير التنفيذ

### لكل Controller جديد:

1. ✅ Extend `BaseApiController` لاستخدام Redis caching
2. ✅ استخدام `LogsActivity` trait في Models
3. ✅ إنشاء Form Requests للـ validation
4. ✅ إنشاء API Resources للـ response formatting
5. ✅ إضافة Policies للـ authorization
6. ✅ تحديث Routes في `core/routes/api/central.php` أو `core/routes/api/tenant.php`
7. ✅ استخدام Redis caching في `index()` و `show()` methods
8. ✅ Cache invalidation عند `store`, `update`, `destroy`
9. ✅ Error handling مناسب
10. ✅ تحديث Postman Collection

### البنية القياسية:

```
core/app/Http/
├── Controllers/Api/
│   ├── Tenant/
│   │   ├── BlogController.php
│   │   ├── BlogCategoryController.php
│   │   └── ...
│   └── Central/
│       ├── MediaController.php
│       └── SettingsController.php
├── Requests/Api/
│   ├── Tenant/
│   │   ├── Blog/
│   │   │   ├── StoreBlogRequest.php
│   │   │   └── UpdateBlogRequest.php
│   └── Central/
└── Resources/Api/
    ├── Tenant/
    │   ├── BlogResource.php
    │   └── BlogCategoryResource.php
    └── Central/
```

---

## تحديث Postman Collection

بعد كل Phase:

1. إضافة جميع endpoints الجديدة إلى `core/docs/API_POSTMAN_COLLECTION.json`
2. تحديث Collection Variables إذا لزم الأمر
3. إضافة أمثلة للـ request/response

---

## التوثيق

بعد كل Phase:

1. تحديث `core/docs/API_DOCUMENTATION.md`
2. إضافة examples للـ cURL
3. تحديث `core/docs/API_COVERAGE_REPORT.md`

---

## ملاحظات مهمة

1. **البدء بـ Phase 1.1 (Blog API)** - الأسهل والأكثر استخداماً
2. **استخدام BaseApiController** - لاستخدام Redis caching
3. **اتباع نفس نمط Controllers الموجودة** - للحفاظ على الاتساق
4. **اختبار كل API بعد إنشائها** - باستخدام Postman أو cURL
5. **تحديث Postman Collection تدريجياً** - بعد كل Phase

---

## الجدول الزمني المقترح

- **Phase 1:** 4-5 أيام (Blog, Pages, Media, Settings)
- **Phase 2:** 6-7 أيام (Coupons, Shipping, Inventory, Wallet, SupportTicket, Reports)
- **Phase 3:** 8-10 أيام (Reviews, Refund, Tax, NewsLetter, Badge, Campaign, DigitalProduct, Others)

**الإجمالي:** ~18-22 يوم عمل

---

**تاريخ الخطة:** 2025-11-03

### To-dos

- [x] تحسين Dashboard queries - دمج multiple queries
- [x] تحسين Customer stats - دمج queries
- [x] إضافة caching للـ statistics
- [x] تحسين updateStatus - إزالة fresh()
- [x] إضافة select() لتحديد الأعمدة المطلوبة فقط
- [x] Phase 1.1: إنشاء Blog API (Tenant) - BlogController + BlogCategoryController + Form Requests + Resources + Routes - 13 endpoints
- [x] Phase 1.2: إنشاء Pages API (Tenant) - PageController + Form Requests + Resource + Routes - 6 endpoints
- [x] Phase 1.3: إنشاء Media Upload API (Tenant & Central) - MediaController (Tenant) + MediaController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [x] Phase 1.4: إنشاء Settings API (Central & Tenant) - SettingsController (Central) + SettingsController (Tenant) + Form Requests + Resources + Routes - 8 endpoints
- [ ] Phase 1.1: إنشاء Blog API (Tenant) - BlogController + BlogCategoryController + Form Requests + Resources + Routes - 13 endpoints
- [ ] Phase 1.2: إنشاء Pages API (Tenant) - PageController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 1.3: إنشاء Media Upload API (Tenant & Central) - MediaController (Tenant) + MediaController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [ ] Phase 1.4: إنشاء Settings API (Central & Tenant) - SettingsController (Central) + SettingsController (Tenant) + Form Requests + Resources + Routes - 8 endpoints
- [ ] Phase 1: تحديث Postman Collection - إضافة جميع endpoints من Phase 1 إلى API_POSTMAN_COLLECTION.json
- [ ] Phase 1: تحديث التوثيق - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md
- [ ] Phase 2.1: إنشاء Coupons API (Tenant) - CouponController + Form Requests + Resource + Routes - 8 endpoints
- [ ] Phase 2.2: إنشاء Shipping API (Tenant) - ShippingZoneController + ShippingMethodController + Form Requests + Resources + Routes - 11 endpoints
- [ ] Phase 2.3: إنشاء Inventory API (Tenant) - InventoryController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 2.4: إنشاء Wallet API (Tenant) - WalletController + Form Requests + Resource + Routes - 5 endpoints
- [ ] Phase 2.5: إنشاء SupportTicket API (Tenant & Central) - SupportTicketController (Tenant) + SupportTicketController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [ ] Phase 2.6: إنشاء Reports API (Central & Tenant) - ReportController (Central) + ReportController (Tenant) + Resources + Routes - 8 endpoints
- [ ] Phase 2: تحديث Postman Collection - إضافة جميع endpoints من Phase 2
- [ ] Phase 2: تحديث التوثيق - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md
- [ ] Phase 3.1: إنشاء Reviews API (Tenant) - ProductReviewController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.2: إنشاء Refund API (Tenant) - RefundController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.3: إنشاء Tax API (Tenant) - TaxController + Form Requests + Resource + Routes - 5 endpoints
- [ ] Phase 3.4: إنشاء NewsLetter API (Tenant) - NewsletterController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.5: إنشاء Badge API (Tenant) - BadgeController + Form Requests + Resource + Routes - 4 endpoints
- [ ] Phase 3.6: إنشاء Campaign API (Tenant) - CampaignController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.7: إنشاء DigitalProduct API (Tenant) - DigitalProductController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.8: إنشاء APIs للـ Modules الأخرى (CountryManage, Service, SalesReport, SiteAnalytics, etc.) - ~30+ endpoints
- [ ] Phase 3: تحديث Postman Collection - إضافة جميع endpoints من Phase 3
- [ ] Phase 3: تحديث التوثيق النهائي - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md
- [x] Phase 2.1: إنشاء Coupons API (Tenant) - CouponController + Form Requests + Resource + Routes - 8 endpoints
- [x] Phase 2.2: إنشاء Shipping API (Tenant) - ShippingZoneController + ShippingMethodController + Form Requests + Resources + Routes - 11 endpoints
- [x] Phase 2.3: إنشاء Inventory API (Tenant) - InventoryController + Form Requests + Resource + Routes - 6 endpoints
- [x] Phase 2.4: إنشاء Wallet API (Tenant) - WalletController + Form Requests + Resource + Routes - 5 endpoints
- [x] تحسين Dashboard queries - دمج multiple queries
- [x] تحسين Customer stats - دمج queries
- [x] إضافة caching للـ statistics
- [x] تحسين updateStatus - إزالة fresh()
- [x] إضافة select() لتحديد الأعمدة المطلوبة فقط
- [x] Phase 1.1: إنشاء Blog API (Tenant) - BlogController + BlogCategoryController + Form Requests + Resources + Routes - 13 endpoints
- [x] Phase 1.2: إنشاء Pages API (Tenant) - PageController + Form Requests + Resource + Routes - 6 endpoints
- [x] Phase 1.3: إنشاء Media Upload API (Tenant & Central) - MediaController (Tenant) + MediaController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [x] Phase 1.4: إنشاء Settings API (Central & Tenant) - SettingsController (Central) + SettingsController (Tenant) + Form Requests + Resources + Routes - 8 endpoints
- [ ] Phase 1.1: إنشاء Blog API (Tenant) - BlogController + BlogCategoryController + Form Requests + Resources + Routes - 13 endpoints
- [ ] Phase 1.2: إنشاء Pages API (Tenant) - PageController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 1.3: إنشاء Media Upload API (Tenant & Central) - MediaController (Tenant) + MediaController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [ ] Phase 1.4: إنشاء Settings API (Central & Tenant) - SettingsController (Central) + SettingsController (Tenant) + Form Requests + Resources + Routes - 8 endpoints
- [ ] Phase 1: تحديث Postman Collection - إضافة جميع endpoints من Phase 1 إلى API_POSTMAN_COLLECTION.json
- [ ] Phase 1: تحديث التوثيق - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md
- [ ] Phase 2.1: إنشاء Coupons API (Tenant) - CouponController + Form Requests + Resource + Routes - 8 endpoints
- [ ] Phase 2.2: إنشاء Shipping API (Tenant) - ShippingZoneController + ShippingMethodController + Form Requests + Resources + Routes - 11 endpoints
- [ ] Phase 2.3: إنشاء Inventory API (Tenant) - InventoryController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 2.4: إنشاء Wallet API (Tenant) - WalletController + Form Requests + Resource + Routes - 5 endpoints
- [ ] Phase 2.5: إنشاء SupportTicket API (Tenant & Central) - SupportTicketController (Tenant) + SupportTicketController (Central) + Form Requests + Resources + Routes - 14 endpoints
- [ ] Phase 2.6: إنشاء Reports API (Central & Tenant) - ReportController (Central) + ReportController (Tenant) + Resources + Routes - 8 endpoints
- [ ] Phase 2: تحديث Postman Collection - إضافة جميع endpoints من Phase 2
- [ ] Phase 2: تحديث التوثيق - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md
- [ ] Phase 3.1: إنشاء Reviews API (Tenant) - ProductReviewController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.2: إنشاء Refund API (Tenant) - RefundController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.3: إنشاء Tax API (Tenant) - TaxController + Form Requests + Resource + Routes - 5 endpoints
- [ ] Phase 3.4: إنشاء NewsLetter API (Tenant) - NewsletterController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.5: إنشاء Badge API (Tenant) - BadgeController + Form Requests + Resource + Routes - 4 endpoints
- [ ] Phase 3.6: إنشاء Campaign API (Tenant) - CampaignController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.7: إنشاء DigitalProduct API (Tenant) - DigitalProductController + Form Requests + Resource + Routes - 6 endpoints
- [ ] Phase 3.8: إنشاء APIs للـ Modules الأخرى (CountryManage, Service, SalesReport, SiteAnalytics, etc.) - ~30+ endpoints
- [ ] Phase 3: تحديث Postman Collection - إضافة جميع endpoints من Phase 3
- [ ] Phase 3: تحديث التوثيق النهائي - تحديث API_DOCUMENTATION.md و API_COVERAGE_REPORT.md