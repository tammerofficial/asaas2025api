<!-- ae71bd7d-8713-4ebc-9831-718a12081c70 0c3d5e6f-dc0b-4a25-9e4f-c4ec59c5c5dc -->
# حل مشكلة Section Error في Page Builder

## المشكلة

- خطأ "Cannot end a section without first starting one" في السطر 130 من `page-builder.blade.php`
- المشكلة أن widgets المُحملة ديناميكياً قد تحتوي على `@section/@endsection` في outputها
- Laravel يحاول compile هذه directives مما يسبب تعارض مع sections الرئيسية

## الحل المطلوب

### 1. إضافة تنظيف نهائي في preview.blade.php

**الملف**: `core/plugins/PageBuilder/views/components/elementor/preview.blade.php`

- إضافة تنظيف في السطر 64 قبل عرض `render_frontend_pagebuilder_content_for_dynamic_page`
- إضافة تنظيف في السطر 66 قبل عرض `render_frontend_pagebuilder_content_by_location`
- إضافة تنظيف في السطر 78 قبل عرض footer content
```blade
@php
    $pageContent = \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page($pageType, $pageId);
    // Remove any @section/@endsection directives
    $pageContent = preg_replace('/@section\s*\([^)]+\)/i', '', $pageContent);
    $pageContent = preg_replace('/@endsection/i', '', $pageContent);
@endphp
{!! $pageContent !!}
```


### 2. إضافة تنظيف في draggable.blade.php

**الملف**: `core/plugins/PageBuilder/views/components/draggable.blade.php`

- تنظيف output في السطر 8 قبل عرض `get_saved_addons_for_dynamic_page`

### 3. إضافة تنظيف في widgets.blade.php  

**الملف**: `core/plugins/PageBuilder/views/components/widgets.blade.php`

- تنظيف output في السطر 9 و 11 قبل عرض widgets list

### 4. إضافة تنظيف في PageBuilderSetup.php

**الملف**: `core/plugins/PageBuilder/PageBuilderSetup.php`

- إضافة تنظيف في `get_saved_addons_for_dynamic_page()` method
- إضافة تنظيف في `get_admin_panel_widgets()` method
- إضافة تنظيف في `get_tenant_admin_panel_widgets()` method

### 5. إضافة تنظيف نهائي في page-builder.blade.php

**الملف**: `core/resources/views/landlord/admin/pages/page-builder.blade.php`

- إضافة helper function في بداية الملف لتنظيف أي @section/@endsection من output
- استخدام هذه الـ function في جميع الأماكن التي تُحمل فيها widgets

## الخطوات التنفيذية

1. تعديل `preview.blade.php` لإضافة تنظيف قبل عرض المحتوى
2. تعديل `draggable.blade.php` لإضافة تنظيف
3. تعديل `widgets.blade.php` لإضافة تنظيف
4. إضافة تنظيف في `PageBuilderSetup.php` methods
5. إضافة تنظيف نهائي في `page-builder.blade.php` كـ safety net
6. مسح cache واختبار

## الملفات المطلوب تعديلها

1. `core/plugins/PageBuilder/views/components/elementor/preview.blade.php`
2. `core/plugins/PageBuilder/views/components/draggable.blade.php`
3. `core/plugins/PageBuilder/views/components/widgets.blade.php`
4. `core/plugins/PageBuilder/PageBuilderSetup.php`
5. `core/resources/views/landlord/admin/pages/page-builder.blade.php`

### To-dos

- [ ] إضافة تنظيف @section/@endsection في preview.blade.php قبل عرض المحتوى
- [ ] إضافة تنظيف @section/@endsection في draggable.blade.php
- [ ] إضافة تنظيف @section/@endsection في widgets.blade.php
- [ ] إضافة تنظيف في get_saved_addons_for_dynamic_page و get_admin_panel_widgets methods
- [ ] إضافة تنظيف نهائي في page-builder.blade.php كـ safety net
- [ ] مسح cache شامل واختبار الحل