<!-- 4046e04d-fe3c-4932-8c3d-c541fb567b77 dcbcbc42-f031-48cb-9cc7-c7f06b8a6290 -->
# تحويل Dashboard المركزي إلى SPA مع Livewire + Octane

## الهدف

تحويل Dashboard المركزي إلى Single Page Application (SPA) باستخدام Livewire للتنقل بدون إعادة تحميل، مع Octane لتحسين الأداء.

## الصفحات المستهدفة

جميع صفحات Dashboard المركزي:

- Dashboard
- Admin Role Manage  
- Users Manage
- Blogs
- Pages
- Themes
- Price Plan
- Package Order Manage
- Wallet Manage
- Custom Domain
- Support Tickets
- Form Builder
- Appearance Settings
- Domain Reseller
- Plugins Manage
- Site Analytics
- Webhook Manage
- General Settings
- Payment Settings

## الخطوات الرئيسية

### 1. تثبيت Livewire 3 و Octane

- إضافة `livewire/livewire` إلى composer.json
- إضافة `laravel/octane` إلى composer.json
- تشغيل `composer require`
- نشر ملفات التكوين

### 2. إنشاء SPA Layout مع Livewire

- إنشاء `app/Livewire/Landlord/Admin/AdminLayout.php`
- إنشاء `resources/views/livewire/landlord/admin/admin-layout.blade.php`
- تحديث Layout الرئيسي لاستخدام Livewire SPA

### 3. إنشاء Navigation Component

- إنشاء `app/Livewire/Landlord/Admin/Navigation.php`
- تحديث Sidebar لاستخدام Livewire Navigation
- إضافة التنقل الديناميكي بدون إعادة تحميل

### 4. تحويل Controllers إلى Livewire Components

لكل صفحة من الصفحات المذكورة:

- إنشاء Livewire Component في `app/Livewire/Landlord/Admin/`
- تحويل Logic من Controller إلى Component
- إنشاء View في `resources/views/livewire/landlord/admin/`

### 5. إعداد Routes للـ SPA

- إنشاء Route واحد للـ Dashboard الرئيسي
- جميع Routes الأخرى تعمل عبر Livewire Navigation
- تحديث `routes/admin.php`

### 6. إعداد Octane

- إنشاء `config/octane.php`
- إعداد Swoole أو RoadRunner
- إضافة Octane Service Provider
- إعداد Cache و Sessions للـ Octane

### 7. تحسينات الأداء

- إضافة Livewire Navigation مع Caching
- إعداد Eager Loading للـ Queries
- تحسين Asset Loading
- إضافة Loading States

## الملفات الرئيسية

### Livewire Components

- `app/Livewire/Landlord/Admin/AdminLayout.php` - Layout الرئيسي
- `app/Livewire/Landlord/Admin/Navigation.php` - Navigation
- `app/Livewire/Landlord/Admin/Dashboard.php` - Dashboard
- `app/Livewire/Landlord/Admin/AdminRoleManage.php` - Admin Roles
- `app/Livewire/Landlord/Admin/UsersManage.php` - Users
- `app/Livewire/Landlord/Admin/Blogs.php` - Blogs
- `app/Livewire/Landlord/Admin/Pages.php` - Pages
- `app/Livewire/Landlord/Admin/Themes.php` - Themes
- `app/Livewire/Landlord/Admin/PricePlan.php` - Price Plans
- `app/Livewire/Landlord/Admin/PackageOrderManage.php` - Orders
- `app/Livewire/Landlord/Admin/WalletManage.php` - Wallet
- `app/Livewire/Landlord/Admin/CustomDomain.php` - Custom Domain
- `app/Livewire/Landlord/Admin/SupportTickets.php` - Support
- `app/Livewire/Landlord/Admin/FormBuilder.php` - Form Builder
- `app/Livewire/Landlord/Admin/AppearanceSettings.php` - Appearance
- `app/Livewire/Landlord/Admin/SiteAnalytics.php` - Analytics
- `app/Livewire/Landlord/Admin/WebhookManage.php` - Webhooks
- `app/Livewire/Landlord/Admin/GeneralSettings.php` - General
- `app/Livewire/Landlord/Admin/PaymentSettings.php` - Payment

### Views

- `resources/views/livewire/landlord/admin/admin-layout.blade.php`
- `resources/views/livewire/landlord/admin/navigation.blade.php`
- Views لكل Component في `resources/views/livewire/landlord/admin/`

### Config Files

- `config/octane.php` - Octane configuration
- `config/livewire.php` - Livewire configuration (if customized)

### Routes

- تحديث `routes/admin.php` لاستخدام Livewire SPA

## المزايا المتوقعة

1. **سرعة التنقل**: لا توجد إعادة تحميل كاملة للصفحة
2. **أداء عالي**: Octane يزيد الأداء 3-5x
3. **تجربة مستخدم أفضل**: انتقالات سلسة بين الصفحات
4. **تقليل استهلاك الموارد**: تقليل Memory و CPU Usage

## ملاحظات مهمة

1. **التوافق مع الكود الحالي**: لن نغير Controllers الموجودة، سنضيف Livewire Components بجانبها
2. **التدرج في التطبيق**: يمكن تطبيق Livewire تدريجياً على الصفحات
3. **Octane متطلب**: يحتاج Swoole أو RoadRunner على السيرفر

## تحذيرات هامة - CRITICAL

⚠️ **مهم جداً - كل شيء باللغة الإنجليزية فقط**:

- **الكود بالكامل**: جميع الأكواد (Variables, Functions, Classes, Comments, Names, Methods, Properties) يجب أن تكون **بالإنجليزية فقط**
- **UI Text بالكامل**: جميع النصوص في الواجهة (Labels, Buttons, Messages, Placeholders) يجب أن تكون **بالإنجليزية فقط**
- **أسماء الملفات**: جميع أسماء الملفات والمجلدات يجب أن تكون **بالإنجليزية فقط**
- **Database Fields**: جميع أسماء الأعمدة والجداول **بالإنجليزية فقط**
- **Validation Messages**: جميع رسائل التحقق **بالإنجليزية فقط**
- **Error Messages**: جميع رسائل الأخطاء **بالإنجليزية فقط**
- **لا يوجد أي نص عربي** في الكود أو الواجهة أو أي ملف تقني

**ملاحظة**: الخطة نفسها بالعربية فقط لتسهيل القراءة، لكن التنفيذ 100% بالإنجليزية.

## تحذيرات هامة

⚠️ **مهم جداً**:

- **لا تكتب أي كود بالعربي** - جميع الأكواد (Variables, Functions, Classes, Comments, Names, etc.) يجب أن تكون بالإنجليزية فقط
- جميع أسماء الملفات، Classes، Functions، Variables يجب أن تكون بالإنجليزية
- جميع التعليقات في الكود يجب أن تكون بالإنجليزية

### To-dos

- [ ] تثبيت Livewire 3 و Octane عبر composer وتشغيل publish commands
- [ ] إنشاء AdminLayout Livewire Component مع SPA structure
- [ ] إنشاء Navigation Livewire Component للتنقل الديناميكي
- [ ] تحويل Dashboard إلى Livewire Component
- [ ] تحويل Admin Role Manage إلى Livewire Component
- [ ] تحويل Users Manage إلى Livewire Component
- [ ] تحويل Blogs إلى Livewire Component
- [ ] تحويل Pages إلى Livewire Component
- [ ] تحويل Themes إلى Livewire Component
- [ ] تحويل Price Plan إلى Livewire Component
- [ ] تحويل Package Order Manage إلى Livewire Component
- [ ] تحويل Wallet Manage إلى Livewire Component
- [ ] تحويل Custom Domain إلى Livewire Component
- [ ] تحويل Support Tickets إلى Livewire Component
- [ ] تحويل Form Builder إلى Livewire Component
- [ ] تحويل Appearance Settings إلى Livewire Component
- [ ] تحويل Site Analytics إلى Livewire Component
- [ ] تحويل Webhook Manage إلى Livewire Component
- [ ] تحويل General Settings إلى Livewire Component
- [ ] تحويل Payment Settings إلى Livewire Component
- [ ] إعداد Octane مع Swoole/RoadRunner وضبط Configuration
- [ ] تحديث routes/admin.php لاستخدام Livewire SPA routing
- [ ] إضافة Caching و Eager Loading و Loading States