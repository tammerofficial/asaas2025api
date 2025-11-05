<!-- 535cb845-2eff-4e99-a5c5-5af96d076435 849393a1-8f02-4e4d-a617-aa46665f87a9 -->
# تطوير جميع API Endpoints المفقودة - خطة شاملة

## الصفحات التي تحتاج إلى تطوير API

### 1. Media Library

- **الوضع الحالي**: API موجود لكن placeholder (يرجع empty array)
- **المطلوب**: CRUD كامل مع upload
- **Endpoints المطلوبة**:
- `GET /media` - List (مع search, pagination) ✅ موجود لكن placeholder
- `GET /media/{id}` - Get single ❌ مفقود
- `POST /media/upload` - Upload file ❌ مفقود
- `PUT /media/{id}` - Update metadata ❌ مفقود
- `DELETE /media/{id}` - Delete ❌ مفقود
- `POST /media/bulk-delete` - Bulk delete ❌ مفقود
- **Model**: `MediaUploader`
- **Vue Component**: `pages/media/MediaLibrary.vue` (موجود)

### 2. Plugins

- **الوضع الحالي**: لا يوجد API، الصفحة تستخدم mock data
- **المطلوب**: CRUD كامل + activate/deactivate
- **Endpoints المطلوبة**:
- `GET /plugins` - List ❌ مفقود
- `GET /plugins/{id}` - Get single ❌ مفقود
- `POST /plugins` - Create/Install ❌ مفقود
- `PUT /plugins/{id}` - Update ❌ مفقود
- `PUT /plugins/{id}/activate` - Activate ❌ مفقود
- `PUT /plugins/{id}/deactivate` - Deactivate ❌ مفقود
- `DELETE /plugins/{id}` - Delete/Uninstall ❌ مفقود
- **Model**: قد يحتاج إنشاء `Plugin` model أو استخدام `StaticOption`
- **Vue Component**: `pages/plugins/PluginsList.vue` (يستخدم mock data)

### 3. Theme Options

- **الوضع الحالي**: API commented out
- **المطلوب**: GET/PUT settings
- **Endpoints المطلوبة**:
- `GET /appearances/theme-options` - Get theme options ❌ مفقود
- `PUT /appearances/theme-options` - Update theme options ❌ مفقود
- **Model**: `StaticOption`
- **Vue Component**: `pages/appearances/ThemeOptions.vue` (API commented)

### 4. Appearance General Settings

- **الوضع الحالي**: API commented out
- **المطلوب**: GET/PUT settings
- **Endpoints المطلوبة**:
- `GET /appearances/general` - Get general appearance settings ❌ مفقود
- `PUT /appearances/general` - Update general appearance settings ❌ مفقود
- **Model**: `StaticOption`
- **Vue Component**: `pages/appearances/GeneralSettings.vue` (API commented)

### 5. Email Templates

- **الوضع الحالي**: بعض API calls commented out
- **المطلوب**: CRUD كامل
- **Endpoints المطلوبة**:
- `GET /settings/email-templates` - List ❌ مفقود (يستخدم settings.get)
- `GET /settings/email-templates/{id}` - Get single ❌ مفقود
- `POST /settings/email-templates` - Create ❌ مفقود (commented)
- `PUT /settings/email-templates/{id}` - Update ❌ مفقود (commented)
- `DELETE /settings/email-templates/{id}` - Delete ❌ مفقود (commented)
- **Model**: `StaticOption` (email_templates stored as JSON)
- **Vue Component**: `pages/settings/EmailTemplates.vue` (API commented)

### 6. Login Activity

- **الوضع الحالي**: يستخدم users.list بدلاً من login-activity endpoint
- **المطلوب**: GET list
- **Endpoints المطلوبة**:
- `GET /users/login-activity` - List (مع search, status filter, type filter, pagination) ❌ مفقود
- **Model**: قد يحتاج إنشاء `LoginActivity` model أو استخدام existing activity logs
- **Vue Component**: `pages/users/LoginActivity.vue` (يستخدم users.list)

### 7. Roles CRUD

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: CRUD كامل
- **Endpoints المطلوبة**:
- `GET /users/roles` - List ✅ موجود
- `GET /users/roles/{id}` - Get single ❌ مفقود
- `POST /users/roles` - Create ❌ مفقود (commented)
- `PUT /users/roles/{id}` - Update ❌ مفقود (commented)
- `DELETE /users/roles/{id}` - Delete ❌ مفقود (commented)
- **Model**: `Role` (Spatie Permission)
- **Vue Component**: `pages/users/Roles.vue` (API create/update/delete commented)

### 8. Permissions CRUD

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: CRUD كامل
- **Endpoints المطلوبة**:
- `GET /users/permissions` - List ✅ موجود
- `GET /users/permissions/{id}` - Get single ❌ مفقود
- `POST /users/permissions` - Create ❌ مفقود (commented)
- `PUT /users/permissions/{id}` - Update ❌ مفقود (commented)
- `DELETE /users/permissions/{id}` - Delete ❌ مفقود (commented)
- **Model**: `Permission` (Spatie Permission)
- **Vue Component**: `pages/users/Permissions.vue` (API create/update/delete commented)

### 9. Languages CRUD

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: CRUD كامل + setDefault
- **Endpoints المطلوبة**:
- `GET /system/languages` - List ✅ موجود
- `GET /system/languages/{id}` - Get single ❌ مفقود
- `POST /system/languages` - Create ❌ مفقود (commented)
- `PUT /system/languages/{id}` - Update ❌ مفقود (commented)
- `DELETE /system/languages/{id}` - Delete ❌ مفقود (commented)
- `PUT /system/languages/{id}/set-default` - Set default ❌ مفقود (commented)
- **Model**: `Language`
- **Vue Component**: `pages/settings/Languages.vue` (API create/update/delete/setDefault commented)

### 10. Users Delete

- **الوضع الحالي**: DELETE endpoint مفقود
- **المطلوب**: DELETE endpoint
- **Endpoints المطلوبة**:
- `DELETE /users/{id}` - Delete ❌ مفقود (commented)
- **Model**: `User`
- **Vue Component**: `pages/users/UsersList.vue` (API delete commented)

### 11. Menus CRUD

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: CRUD كامل
- **Endpoints المطلوبة**:
- `GET /appearances/menus` - List ✅ موجود
- `GET /appearances/menus/{id}` - Get single ❌ مفقود
- `POST /appearances/menus` - Create ❌ مفقود (alert فقط)
- `PUT /appearances/menus/{id}` - Update ❌ مفقود (alert فقط)
- `DELETE /appearances/menus/{id}` - Delete ❌ مفقود (commented)
- **Model**: `Menu`
- **Vue Component**: `pages/appearances/Menus.vue` (API create/update/delete missing)

### 12. Widgets CRUD

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: CRUD كامل + activate/deactivate
- **Endpoints المطلوبة**:
- `GET /appearances/widgets` - List ✅ موجود
- `GET /appearances/widgets/{id}` - Get single ❌ مفقود
- `POST /appearances/widgets` - Create ❌ مفقود (alert فقط)
- `PUT /appearances/widgets/{id}` - Update ❌ مفقود (alert فقط)
- `PUT /appearances/widgets/{id}/activate` - Activate ❌ مفقود (local update فقط)
- `PUT /appearances/widgets/{id}/deactivate` - Deactivate ❌ مفقود (local update فقط)
- `DELETE /appearances/widgets/{id}` - Delete ❌ مفقود (local delete فقط)
- **Model**: `Widgets`
- **Vue Component**: `pages/appearances/Widgets.vue` (API calls missing)

### 13. Themes Actions

- **الوضع الحالي**: GET موجود فقط
- **المطلوب**: activate/deactivate/delete
- **Endpoints المطلوبة**:
- `GET /appearances/themes` - List ✅ موجود
- `GET /appearances/themes/{id}` - Get single ❌ مفقود
- `PUT /appearances/themes/{id}/activate` - Activate ❌ مفقود (alert فقط)
- `DELETE /appearances/themes/{id}` - Delete ❌ مفقود (alert فقط)
- **Model**: `Themes`
- **Vue Component**: `pages/appearances/Themes.vue` (API activate/delete missing)

### 14. System Backups

- **الوضع الحالي**: API commented out، يستخدم mock data
- **المطلوب**: CRUD كامل
- **Endpoints المطلوبة**:
- `GET /system/backups` - List ❌ مفقود (commented)
- `POST /system/backups` - Create backup ❌ مفقود (commented)
- `POST /system/backups/{id}/restore` - Restore backup ❌ مفقود (commented)
- `DELETE /system/backups/{id}` - Delete backup ❌ مفقود (commented)
- **Model**: قد يحتاج إنشاء `Backup` model
- **Vue Component**: `pages/system/Backups.vue` (API commented, uses mock data)

### 15. System Sitemap

- **الوضع الحالي**: API commented out، يستخدم mock data
- **المطلوب**: GET info + generate + update
- **Endpoints المطلوبة**:
- `GET /system/sitemap` - Get sitemap info ❌ مفقود (commented)
- `POST /system/sitemap/generate` - Generate sitemap ❌ مفقود (commented)
- `PUT /system/sitemap` - Update sitemap settings ❌ مفقود (commented)
- **Model**: `StaticOption`
- **Vue Component**: `pages/system/Sitemap.vue` (API commented, uses mock data)

## خطة التنفيذ التفصيلية

### Phase 1: Backend API Development (WebApiController.php)

#### 1.1 Media Library API

- تطوير `getMedia($id)` - Get single
- تطوير `storeMedia(Request $request)` - Upload file
- تحديث `media()` - List مع caching وتحسين
- تطوير `updateMedia(Request $request, $id)` - Update metadata
- تطوير `deleteMedia($id)` - Delete
- تطوير `bulkDeleteMedia(Request $request)` - Bulk delete

#### 1.2 Plugins API

- تطوير `plugins(Request $request)` - List
- تطوير `getPlugin($id)` - Get single
- تطوير `storePlugin(Request $request)` - Install
- تطوير `updatePlugin(Request $request, $id)` - Update
- تطوير `activatePlugin($id)` - Activate
- تطوير `deactivatePlugin($id)` - Deactivate
- تطوير `deletePlugin($id)` - Uninstall

#### 1.3 Theme Options API

- تطوير `themeOptions(Request $request)` - Get
- تطوير `updateThemeOptions(Request $request)` - Update

#### 1.4 Appearance General Settings API

- تطوير `appearanceGeneralSettings(Request $request)` - Get
- تطوير `updateAppearanceGeneralSettings(Request $request)` - Update

#### 1.5 Email Templates API

- تطوير `emailTemplates(Request $request)` - List
- تطوير `getEmailTemplate($id)` - Get single
- تطوير `storeEmailTemplate(Request $request)` - Create
- تطوير `updateEmailTemplate(Request $request, $id)` - Update
- تطوير `deleteEmailTemplate($id)` - Delete

#### 1.6 Login Activity API

- تطوير `loginActivity(Request $request)` - List مع filters

#### 1.7 Roles CRUD API

- تطوير `getRole($id)` - Get single
- تطوير `storeRole(Request $request)` - Create
- تطوير `updateRole(Request $request, $id)` - Update
- تطوير `deleteRole($id)` - Delete

#### 1.8 Permissions CRUD API

- تطوير `getPermission($id)` - Get single
- تطوير `storePermission(Request $request)` - Create
- تطوير `updatePermission(Request $request, $id)` - Update
- تطوير `deletePermission($id)` - Delete

#### 1.9 Languages CRUD API

- تطوير `getLanguage($id)` - Get single
- تطوير `storeLanguage(Request $request)` - Create
- تطوير `updateLanguage(Request $request, $id)` - Update
- تطوير `deleteLanguage($id)` - Delete
- تطوير `setDefaultLanguage($id)` - Set default

#### 1.10 Users Delete API

- تطوير `deleteUser($id)` - Delete (إذا لم يكن موجود)

#### 1.11 Menus CRUD API

- تطوير `getMenu($id)` - Get single
- تطوير `storeMenu(Request $request)` - Create
- تطوير `updateMenu(Request $request, $id)` - Update
- تطوير `deleteMenu($id)` - Delete

#### 1.12 Widgets CRUD API

- تطوير `getWidget($id)` - Get single
- تطوير `storeWidget(Request $request)` - Create
- تطوير `updateWidget(Request $request, $id)` - Update
- تطوير `activateWidget($id)` - Activate
- تطوير `deactivateWidget($id)` - Deactivate
- تطوير `deleteWidget($id)` - Delete

#### 1.13 Themes Actions API

- تطوير `getTheme($id)` - Get single
- تطوير `activateTheme($id)` - Activate
- تطوير `deleteTheme($id)` - Delete

#### 1.14 System Backups API

- تطوير `backups(Request $request)` - List
- تطوير `createBackup(Request $request)` - Create backup
- تطوير `restoreBackup($id)` - Restore backup
- تطوير `deleteBackup($id)` - Delete backup

#### 1.15 System Sitemap API

- تطوير `sitemap(Request $request)` - Get sitemap info
- تطوير `generateSitemap(Request $request)` - Generate sitemap
- تطوير `updateSitemap(Request $request)` - Update sitemap settings

### Phase 2: Routes Configuration (admin.php)

- إضافة جميع routes الجديدة تحت `WebApiController` group
- التأكد من ترتيب routes (static قبل dynamic)
- إضافة routes للـ CRUD operations المفقودة

### Phase 3: API Service Updates (api.js)

- تحديث `media` object - إضافة methods المفقودة
- إضافة `plugins` object كامل
- إضافة `appearances.themeOptions` و `appearances.general`
- إضافة `appearances.menus` CRUD methods
- إضافة `appearances.widgets` CRUD methods
- إضافة `appearances.themes` actions methods
- إضافة `settings.emailTemplates` object
- إضافة `users.loginActivity` method
- إضافة `users.roles` CRUD methods
- إضافة `users.permissions` CRUD methods
- إضافة `users.delete` method
- إضافة `system.languages` CRUD methods
- إضافة `system.backups` object
- إضافة `system.sitemap` object

### Phase 4: Vue Components Updates

- **MediaLibrary.vue**: ربط جميع API calls
- **PluginsList.vue**: إزالة mock data وربط API
- **ThemeOptions.vue**: تفعيل API calls
- **GeneralSettings.vue**: تفعيل API calls
- **EmailTemplates.vue**: تفعيل API calls (إزالة comments)
- **LoginActivity.vue**: تحديث لاستخدام login-activity endpoint
- **Roles.vue**: تفعيل API calls (إزالة comments)
- **Permissions.vue**: تفعيل API calls (إزالة comments)
- **Languages.vue**: تفعيل API calls (إزالة comments)
- **UsersList.vue**: تفعيل delete API call
- **Menus.vue**: ربط API calls
- **Widgets.vue**: ربط API calls
- **Themes.vue**: ربط API calls
- **Backups.vue**: إزالة mock data وربط API
- **Sitemap.vue**: إزالة mock data وربط API

### Phase 5: Testing

- اختبار جميع endpoints
- اختبار caching مع Octane & Redis
- اختبار Vue components
- التأكد من عدم وجود أخطاء في console

## الملفات التي سيتم تعديلها

### Backend

- `core/app/Http/Controllers/Central/V1/WebApiController.php` - إضافة جميع methods (حوالي 50+ method)
- `core/routes/admin.php` - إضافة جميع routes (حوالي 40+ route)

### Frontend

- `core/resources/js/central/services/api.js` - تحديث وإضافة API service methods
- `core/resources/js/central/pages/media/MediaLibrary.vue` - ربط API
- `core/resources/js/central/pages/plugins/PluginsList.vue` - إزالة mock data وربط API
- `core/resources/js/central/pages/appearances/ThemeOptions.vue` - تفعيل API
- `core/resources/js/central/pages/appearances/GeneralSettings.vue` - تفعيل API
- `core/resources/js/central/pages/settings/EmailTemplates.vue` - تفعيل API
- `core/resources/js/central/pages/users/LoginActivity.vue` - تحديث API endpoint
- `core/resources/js/central/pages/users/Roles.vue` - تفعيل API
- `core/resources/js/central/pages/users/Permissions.vue` - تفعيل API
- `core/resources/js/central/pages/settings/Languages.vue` - تفعيل API
- `core/resources/js/central/pages/users/UsersList.vue` - تفعيل delete API
- `core/resources/js/central/pages/appearances/Menus.vue` - ربط API
- `core/resources/js/central/pages/appearances/Widgets.vue` - ربط API
- `core/resources/js/central/pages/appearances/Themes.vue` - ربط API
- `core/resources/js/central/pages/system/Backups.vue` - إزالة mock data وربط API
- `core/resources/js/central/pages/system/Sitemap.vue` - إزالة mock data وربط API

## ملاحظات مهمة

1. جميع endpoints ستستخدم `UsesApiCache` trait مع Octane & Redis caching
2. Media upload يحتاج إلى معالجة FormData بشكل صحيح
3. Plugins قد تحتاج إلى إنشاء migration و model إذا لم يكن موجوداً
4. Email Templates قد يتم تخزينها في `StaticOption` كـ JSON
5. Login Activity قد يحتاج إلى استخدام `Activity` model من Spatie أو إنشاء model جديد
6. Backups قد يحتاج إلى integration مع Laravel backup packages أو إنشاء custom solution
7. Sitemap قد يحتاج إلى integration مع Laravel sitemap packages أو إنشاء custom solution
8. جميع endpoints ستتبع نفس النمط المستخدم في الـ endpoints الموجودة
9. التأكد من validation في جميع endpoints
10. التأكد من authorization في جميع endpoints

## إحصائيات

- **إجمالي Endpoints المطلوبة**: حوالي 60+ endpoint
- **إجمالي Vue Components المطلوب تحديثها**: 15 component
- **إجمالي Routes المطلوبة**: حوالي 40+ route

### To-dos

- [x] تطوير Media Library API - CRUD كامل مع upload
- [x] تطوير Plugins API - CRUD كامل مع activate/deactivate
- [x] تطوير Theme Options API - GET/PUT
- [x] تطوير Appearance General Settings API - GET/PUT
- [x] تطوير Email Templates API - CRUD كامل
- [x] تطوير Login Activity API - GET list
- [x] إضافة جميع routes في admin.php
- [x] تحديث api.js service file
- [x] ربط جميع Vue components مع API