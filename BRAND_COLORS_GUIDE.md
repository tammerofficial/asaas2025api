# 🎨 دليل تغيير ألوان البراند - Brand Colors Guide

## نظرة عامة - Overview

تم تغيير لون البراند الرئيسي من **البنفسجي** `#b66dff` إلى **الأحمر الداكن** `#7f1625`  
The primary brand color has been changed from **Purple** `#b66dff` to **Burgundy** `#7f1625`

---

## 📂 الملفات المنشأة - Created Files

### 1. ملف تكوين الألوان - Color Configuration
**الموقع:** `core/config/brand-colors.php`

يحتوي على جميع ألوان البراند وتدرجاتها:
- الألوان الأساسية (Primary Colors) مع 6 درجات
- الألوان الوظيفية (Success, Warning, Danger, Info)
- الألوان المحايدة (Neutral Colors)
- ألوان عناصر الواجهة (UI Elements)

### 2. ملف متغيرات CSS - CSS Variables
**الموقع:** `assets/common/css/brand-colors.css`

يحتوي على:
- CSS Variables لجميع الألوان
- Utility Classes جاهزة للاستخدام
- أمثلة للاستخدام في HTML

### 3. مساعد PHP - PHP Helper Class
**الموقع:** `core/app/Helpers/BrandColorHelper.php`

Class شامل للوصول لجميع الألوان برمجياً.

### 4. دوال مساعدة - Helper Functions
**الموقع:** `core/app/Helpers/brand_color_helpers.php`

دوال سهلة للاستخدام في Blade Templates.

---

## 🎯 الألوان الجديدة - New Colors

### اللون الأساسي - Primary Color
```
Base (الأساسي):     #7f1625
Dark (داكن):         #5a0f19
Darker (أغمق):       #3d0a11
Light (فاتح):        #a01d2f
Lighter (أفتح):      #c5253d
Pale (باهت):         #e6394f
```

### تدرجات الشفافية - RGBA Variations
```
20% opacity:  rgba(127, 22, 37, 0.2)
30% opacity:  rgba(127, 22, 37, 0.3)
50% opacity:  rgba(127, 22, 37, 0.5)
70% opacity:  rgba(127, 22, 37, 0.7)
90% opacity:  rgba(127, 22, 37, 0.9)
```

---

## 💻 كيفية الاستخدام - How to Use

### 1️⃣ في Blade Templates

#### طريقة مباشرة - Direct Way
```blade
{{-- Table Header مع اللون الجديد --}}
<thead class="text-white" style="background-color: {{ brand_primary() }}">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Action</th>
    </tr>
</thead>

{{-- استخدام التدرجات --}}
<div style="background-color: {{ brand_primary('light') }}">
    محتوى بلون فاتح
</div>

<div style="border: 2px solid {{ brand_primary('dark') }}">
    حدود بلون داكن
</div>
```

#### باستخدام Helper Function
```blade
{{-- دالة مخصصة لرؤوس الجداول --}}
<thead class="text-white" style="{{ table_header_style() }}">
    <tr>...</tr>
</thead>

{{-- الألوان الوظيفية --}}
<button style="background-color: {{ brand_success() }}">حفظ</button>
<button style="background-color: {{ brand_danger() }}">حذف</button>
<button style="background-color: {{ brand_warning() }}">تحذير</button>
<button style="background-color: {{ brand_info() }}">معلومات</button>
```

### 2️⃣ في CSS

#### استخدام CSS Variables
```css
/* في ملفات CSS الخاصة بك */
.my-button {
    background-color: var(--brand-primary);
    color: white;
}

.my-button:hover {
    background-color: var(--brand-primary-dark);
}

.my-card {
    border: 2px solid var(--brand-primary);
    background-color: var(--brand-primary-rgba-20);
}
```

#### استخدام Utility Classes
```html
<!-- Classes جاهزة -->
<div class="bg-brand-primary text-white">خلفية بلون البراند</div>
<div class="text-brand-primary">نص بلون البراند</div>
<div class="border-brand-primary">حدود بلون البراند</div>

<!-- Buttons -->
<button class="btn btn-brand-primary">زر بلون البراند</button>
```

### 3️⃣ في PHP/Controllers

```php
use App\Helpers\BrandColorHelper;

// الحصول على اللون الأساسي
$primaryColor = BrandColorHelper::primaryBase(); // #7f1625

// الحصول على تدرج معين
$lightColor = BrandColorHelper::primaryLight(); // #a01d2f
$darkColor = BrandColorHelper::primaryDark();   // #5a0f19

// RGBA
$transparentColor = BrandColorHelper::rgba50(); // rgba(127, 22, 37, 0.5)

// Dot notation
$color = BrandColorHelper::get('primary.light'); // #a01d2f
$color = BrandColorHelper::get('success.base');  // #28a745
```

---

## 🔄 الملفات التي تم تحديثها - Updated Files

### ملفات CSS الرئيسية:
✅ `assets/common/css/custom-style.css`  
✅ `assets/tenant/backend/css/module-fix-style.css`  
✅ `assets/landlord/admin/css/style.css`  
✅ `core/public/landlord/admin/css/style.css`

### ملفات Blade Templates:
✅ جميع ملفات الـ Views في `core/resources/views/`  
✅ جميع ملفات الـ Modules في `core/Modules/`  
✅ Component الجدول: `core/resources/views/components/datatable/table.blade.php`

### ملفات Vue:
✅ `core/resources/js/vue/layouts/app.vue`  
✅ `core/Modules/Pos/vue/layouts/app.vue`

### ملفات Build:
✅ `core/public/build/assets/app-*.css`  
✅ `core/Modules/Pos/_build/assets/app-*.css`

---

## 📊 أمثلة عملية - Practical Examples

### مثال: جدول كامل - Full Table Example
```blade
<div class="table-responsive">
    <table class="table table-bordered">
        {{-- رأس الجدول بلون البراند --}}
        <thead class="text-white" style="{{ table_header_style() }}">
            <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge" style="background-color: {{ brand_success() }}">
                        نشط
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm" style="background-color: {{ brand_primary() }}; color: white;">
                        عرض
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

### مثال: Card بألوان البراند
```blade
<div class="card" style="border-top: 3px solid {{ brand_primary() }}">
    <div class="card-header" style="background-color: {{ brand_rgba('20') }}">
        <h5 class="mb-0" style="color: {{ brand_primary() }}">
            عنوان الكارد
        </h5>
    </div>
    <div class="card-body">
        <p>محتوى الكارد هنا</p>
        <button class="btn" style="background-color: {{ brand_primary() }}; color: white;">
            زر الإجراء
        </button>
    </div>
</div>
```

### مثال: Alert Messages
```blade
{{-- Success Alert --}}
<div class="alert" style="background-color: {{ brand_rgba('20') }}; border-left: 4px solid {{ brand_primary() }};">
    <strong style="color: {{ brand_primary() }}">نجح!</strong>
    تم إتمام العملية بنجاح.
</div>

{{-- Warning Alert --}}
<div class="alert" style="background-color: rgba(255, 193, 7, 0.2); border-left: 4px solid {{ brand_warning() }};">
    <strong style="color: {{ brand_warning() }}">تحذير!</strong>
    يرجى الانتباه لهذا الإشعار.
</div>
```

---

## 🔧 أوامر التثبيت - Installation Commands

بعد إنشاء الملفات، قم بتشغيل:

```bash
# في مجلد core
cd core

# إعادة تحميل autoload
composer dump-autoload

# مسح الـ cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# (اختياري) إعادة بناء assets
npm run build
```

---

## 📝 ملاحظات مهمة - Important Notes

1. **الاتساق في الاستخدام:**
   - استخدم دائماً `brand_primary()` بدلاً من كتابة اللون مباشرة
   - هذا يسهل تغيير الألوان مستقبلاً

2. **رؤوس الجداول:**
   - استخدم `table_header_style()` لجميع جداول النظام
   - أو استخدم: `style="background-color: {{ brand_primary() }}"`

3. **تحديثات مستقبلية:**
   - لتغيير اللون، عدّل فقط ملف `brand-colors.php`
   - سيتم تطبيق التغيير على كامل النظام تلقائياً

4. **الملفات المبنية (Built Assets):**
   - إذا عدّلت ملفات CSS الأصلية، شغّل `npm run build`
   - الملفات في `public/build/` تُحدّث تلقائياً

---

## 🎨 لوحة الألوان الكاملة - Complete Color Palette

### Primary (الأساسي)
| Shade | Color | Hex Code | Usage |
|-------|-------|----------|-------|
| Darker | 🔴 | `#3d0a11` | Borders, Shadows |
| Dark | 🔴 | `#5a0f19` | Hover states |
| **Base** | 🔴 | `#7f1625` | **Main brand color** |
| Light | 🔴 | `#a01d2f` | Backgrounds |
| Lighter | 🔴 | `#c5253d` | Light backgrounds |
| Pale | 🔴 | `#e6394f` | Very light backgrounds |

### Functional Colors (الألوان الوظيفية)
| Type | Color | Hex Code |
|------|-------|----------|
| Success | 🟢 | `#28a745` |
| Warning | 🟡 | `#ffc107` |
| Danger | 🔴 | `#dc3545` |
| Info | 🔵 | `#17a2b8` |

---

## 🆘 الدعم - Support

إذا واجهت أي مشاكل:

1. تأكد من تشغيل `composer dump-autoload`
2. امسح الـ cache
3. تأكد من وجود ملف `brand-colors.php` في `core/config/`
4. تأكد من وجود ملف `brand_color_helpers.php` في `core/app/Helpers/`

---

## ✅ Checklist التحقق

- [x] تم إنشاء ملف `brand-colors.php`
- [x] تم إنشاء ملف `brand-colors.css`
- [x] تم إنشاء `BrandColorHelper.php`
- [x] تم إنشاء `brand_color_helpers.php`
- [x] تم تحديث `composer.json`
- [x] تم استبدال جميع حالات `#b66dff` بـ `#7f1625`
- [x] تم تحديث جميع ملفات CSS
- [x] تم تحديث جميع ملفات Blade
- [x] تم تحديث جميع ملفات Vue
- [x] تم تحديث رؤوس جميع الجداول

---

## 🚀 الخطوات التالية - Next Steps

يمكنك الآن:
1. ✅ استخدام `brand_primary()` في أي ملف Blade
2. ✅ استخدام `var(--brand-primary)` في ملفات CSS
3. ✅ استخدام classes مثل `.bg-brand-primary` في HTML
4. ✅ تغيير اللون من مكان واحد فقط عند الحاجة

**تم تطبيق اللون الجديد على كامل النظام بنجاح! 🎉**

