<!-- 68cc091b-381a-4601-9f6d-9abadf60cf43 0eb36880-c052-4669-a3b3-fb62acbbfd28 -->
# إنشاء صفحات جديدة بـ Alpine.js + Livewire + Octane + Redis

## الهدف

إنشاء صفحات جديدة بالكامل باستخدام **Alpine.js** في الـ frontend + **Livewire** للـ backend logic + **Octane** + **Redis** مع الحفاظ على الصفحات الحالية وعدم التأثير على middleware أو أي جزء آخر من المشروع.

## البنية الحالية (يُحافظ عليها كما هي - بدون تعديل)

- ✅ **AdminLayout**: Component رئيسي يحتوي على navigation ونظام SPA (يُحافظ عليه كما هو)
- ✅ **18-20 Livewire Components**: nested components تُحمّل داخل AdminLayout (يُحافظ عليها كما هي)
- ✅ **Route واحد**: `Route::get('/', AdminLayout::class)` (يُحافظ عليه كما هو)
- ✅ **Middleware**: `auth:admin`, `adminglobalVariable`, `set_lang` (يُحافظ عليها كما هي)

## البنية الجديدة (صفحات جديدة بالكامل)

### المبادئ الأساسية:

1. **صفحات جديدة تماماً**: إنشاء Livewire components جديدة بأسماء مختلفة تماماً (مثل `DashboardV2`, `AdminRoleManageV2`, إلخ)
2. **Routes جديدة**: استخدام نفس middleware group لكن مع prefix مختلف (مثل `/admin-v2/`)
3. **Lazy Loading**: كل route جديد يستخدم `->lazy()` لتحميل الصفحة عند الحاجة فقط
4. **Alpine.js للـ Frontend**: استخدام Alpine.js بشكل كامل للتفاعلية (modals, dropdowns, forms, إلخ)
5. **Livewire للـ Backend**: استخدام Livewire للـ server-side logic (CRUD operations, data fetching, إلخ)
6. **Octane + Redis**: استخدام Octane للـ server-side performance و Redis للـ caching
7. **Layout جديد**: إنشاء layout جديد للصفحات الجديدة مع navigation component

## خطوات التنفيذ

### 1. إنشاء Admin Layout Component للـ Navigation

**الملف**: `core/app/Livewire/Landlord/Admin/AdminLayoutWrapper.php`

- Component جديد يحتوي على navigation sidebar فقط
- يُستخدم كـ layout wrapper للصفحات

### 2. تحديث جميع Livewire Components

**الملفات**: `core/app/Livewire/Landlord/Admin/*.php` (18 ملف)

- إضافة `use Livewire\Attributes\Lazy;`
- إضافة `#[Lazy]` attribute على كل class
- تحديث `render()` method لاستخدام `->layout('layouts.landlord.admin.master')`
- إضافة `dispatch('setPageTitle')` في `mount()`

**مثال**:

```php
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class Dashboard extends Component
{
    public function mount()
    {
        $this->dispatch('setPageTitle', ['title' => 'Dashboard']);
    }

    public function render()
    {
        return view('livewire.landlord.admin.dashboard')
            ->layout('layouts.landlord.admin.master', [
                'title' => 'Dashboard'
            ]);
    }
}
```

### 3. إنشاء Routes منفصلة لكل Component

**الملف**: `core/routes/admin.php`

- حذف route `AdminLayout::class` القديم
- إنشاء routes منفصلة لكل component مع `->lazy()`

**مثال**:

```php
Route::get('/', Dashboard::class)->lazy()->name('landlord.admin.dashboard');
Route::get('/admin/all', AdminRoleManage::class)->lazy()->name('landlord.admin.admin-role-manage');
Route::get('/tenant', Tenant::class)->lazy()->name('landlord.admin.tenant');
// ... بقية الصفحات
```

### 4. تحديث Navigation Component

**الملف**: `core/app/Livewire/Landlord/Admin/Navigation.php`

- تحديث navigation لاستخدام routes جديدة بدلاً من SPA navigation
- استخدام `wire:navigate` للتنقل بين الصفحات

### 5. تحديث Views

**الملفات**: `core/resources/views/livewire/landlord/admin/*.blade.php`

- إزالة SPA navigation JavaScript من views
- إضافة navigation component في layout

### 6. إضافة Lazy Loading Placeholder

**الملف**: `core/config/livewire.php`

- إضافة placeholder view للـ lazy loading
- أو استخدام placeholder افتراضي

### 7. تحديث Layout Master

**الملف**: `core/resources/views/layouts/landlord/admin/master.blade.php`

- إضافة navigation component
- إضافة loading states للـ lazy components

## تقسيم العمل بين مبرمجين

### المبرمج الأول - القسم الأول (10 صفحات)

**Components المطلوبة:**

1. DashboardV2
2. AdminRoleManageV2
3. TenantV2
4. UsersManageV2
5. PagesV2
6. ThemesV2
7. PricePlanV2
8. PackageOrderManageV2
9. WalletManageV2
10. CustomDomainV2

**Routes المطلوبة:**

- `/admin-v2/` → DashboardV2
- `/admin-v2/admin/all` → AdminRoleManageV2
- `/admin-v2/tenant` → TenantV2
- `/admin-v2/users` → UsersManageV2
- `/admin-v2/pages` → PagesV2
- `/admin-v2/themes` → ThemesV2
- `/admin-v2/price-plan` → PricePlanV2
- `/admin-v2/package-orders` → PackageOrderManageV2
- `/admin-v2/wallet` → WalletManageV2
- `/admin-v2/custom-domain` → CustomDomainV2

**ملفات المبرمج الأول:**

- 10 Livewire Components (PHP)
- 10 Views (Blade)
- Routes في `routes/admin-v2.php` (القسم الأول)
- **المجموع: 21 ملف**

---

### المبرمج الثاني - القسم الثاني (9 صفحات)

**Components المطلوبة:**

1. SupportTicketsV2
2. FormBuilderV2
3. AppearanceSettingsV2
4. SiteAnalyticsV2
5. WebhookManageV2
6. GeneralSettingsV2
7. PaymentSettingsV2
8. DomainResellerV2
9. PluginManageV2

**Routes المطلوبة:**

- `/admin-v2/support-tickets` → SupportTicketsV2
- `/admin-v2/form-builder` → FormBuilderV2
- `/admin-v2/appearance-settings` → AppearanceSettingsV2
- `/admin-v2/analytics` → SiteAnalyticsV2
- `/admin-v2/webhooks` → WebhookManageV2
- `/admin-v2/settings/general` → GeneralSettingsV2
- `/admin-v2/settings/payment` → PaymentSettingsV2
- `/admin-v2/domain-reseller` → DomainResellerV2
- `/admin-v2/plugins` → PluginManageV2

**ملفات المبرمج الثاني:**

- 9 Livewire Components (PHP)
- 9 Views (Blade)
- Routes في `routes/admin-v2.php` (القسم الثاني)
- **المجموع: 19 ملف**

---

### الملفات المشتركة (يُنشئها المبرمج الأول):

**ملفات مشتركة:**

1. `resources/views/layouts/landlord/admin/v2/master.blade.php` - Layout
2. `app/Livewire/Landlord/Admin/NavigationV2.php` - Navigation Component
3. `resources/views/livewire/landlord/admin/navigation-v2.blade.php` - Navigation View
4. `resources/views/components/livewire/lazy-placeholder.blade.php` - Lazy Placeholder
5. `routes/admin-v2.php` - Routes file (هيكلة الملف، المبرمج الثاني يضيف routes)

**ملاحظة:** المبرمج الأول يُنشئ هيكلة الـ routes file، والمبرمج الثاني يضيف routes الخاصة به.

---

### ملخص التقسيم:

**المبرمج الأول:**

- 10 Livewire Components
- 10 Views
- Routes file (هيكلة + routes الخاصة به)
- Layout
- Navigation
- Lazy Placeholder
- **المجموع: 25 ملف (21 + 4 مشتركة)**

**المبرمج الثاني:**

- 9 Livewire Components
- 9 Views
- Routes في نفس الملف (إضافة فقط)
- **المجموع: 18 ملف**

**الإجمالي الكلي: 43 ملف (25 + 18)**

## الفوائد المتوقعة

- تحسين الأداء: تحميل الصفحات عند الحاجة فقط
- تقليل حجم الـ bundle: كل صفحة تُحمّل منفصلة
- أفضل تجربة مستخدم: أسرع booting للصفحة الأولى
- سهولة الصيانة: كل صفحة route منفصل

## ملاحظات

- الحفاظ على middleware موجودة (`auth:admin`, `adminglobalVariable`, `set_lang`)
- الاحتفاظ بـ Navigation component كـ shared component
- استخدام `wire:navigate` للتنقل السلس بين الصفحات
- التأكد من تحديث جميع links في navigation