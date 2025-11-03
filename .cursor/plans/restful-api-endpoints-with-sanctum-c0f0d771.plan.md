<!-- c0f0d771-abec-4b81-960c-aded13b4a19d 4a005ba1-d838-4df0-a43b-d75b15d2d015 -->
# خطة إنشاء RESTful API Endpoints بالـ Sanctum

## نظرة عامة

إنشاء نظام RESTful API كامل للداشبورد المركزي (Landlord/Central) وداشبورد المستأجر (Tenant) باستخدام Laravel Sanctum للمصادقة.

## البنية المستهدفة

### 1. هيكل المسارات (Routes Structure)

#### Central API Routes

- `routes/api/central.php` - مسارات الداشبورد المركزي
  - `/api/central/v1/auth/*` - Authentication endpoints
  - `/api/central/v1/dashboard/*` - Dashboard statistics
  - `/api/central/v1/tenants/*` - Tenants management
  - `/api/central/v1/plans/*` - Price plans management
  - `/api/central/v1/orders/*` - Orders management
  - `/api/central/v1/payments/*` - Payments management

#### Tenant API Routes

- `routes/api/tenant.php` - مسارات داشبورد المستأجر
  - `/api/tenant/v1/auth/*` - Authentication endpoints
  - `/api/tenant/v1/dashboard/*` - Dashboard statistics
  - `/api/tenant/v1/products/*` - Products management
  - `/api/tenant/v1/orders/*` - Orders management
  - `/api/tenant/v1/customers/*` - Customers management

## الملفات المطلوب إنشاؤها

### 2. Authentication Controllers

#### Central Authentication

- `app/Http/Controllers/Api/Central/Auth/AuthController.php`
  - `login()` - Login for Admin users
  - `logout()` - Logout current user
  - `register()` - Register new admin (if needed)
  - `me()` - Get current authenticated user
  - `refresh()` - Refresh token

#### Tenant Authentication

- `app/Http/Controllers/Api/Tenant/Auth/AuthController.php`
  - `login()` - Login for Tenant Admin
  - `logout()` - Logout current user
  - `me()` - Get current authenticated user
  - `refresh()` - Refresh token

### 3. Dashboard Controllers

#### Central Dashboard API

- `app/Http/Controllers/Api/Central/DashboardController.php`
  - `index()` - Dashboard statistics (total tenants, users, orders, revenue)
  - `stats()` - Detailed statistics
  - `recentOrders()` - Recent payment logs
  - `chartData()` - Chart data for analytics

#### Tenant Dashboard API

- `app/Http/Controllers/Api/Tenant/DashboardController.php`
  - `index()` - Dashboard statistics (total products, orders, sales, customers)
  - `stats()` - Detailed statistics
  - `recentOrders()` - Recent orders
  - `chartData()` - Chart data for analytics

### 4. Resource Controllers (Central)

- `app/Http/Controllers/Api/Central/TenantController.php` - CRUD for tenants
- `app/Http/Controllers/Api/Central/PricePlanController.php` - CRUD for price plans
- `app/Http/Controllers/Api/Central/OrderController.php` - List/view orders
- `app/Http/Controllers/Api/Central/PaymentController.php` - Payment logs management
- `app/Http/Controllers/Api/Central/AdminController.php` - Admin users management

### 5. Resource Controllers (Tenant)

- `app/Http/Controllers/Api/Tenant/ProductController.php` - Products CRUD
- `app/Http/Controllers/Api/Tenant/OrderController.php` - Orders management
- `app/Http/Controllers/Api/Tenant/CustomerController.php` - Customers management
- `app/Http/Controllers/Api/Tenant/CategoryController.php` - Categories management

### 6. API Resources (JSON Response Formatting)

#### Central Resources

- `app/Http/Resources/Api/Central/AdminResource.php`
- `app/Http/Resources/Api/Central/TenantResource.php`
- `app/Http/Resources/Api/Central/PricePlanResource.php`
- `app/Http/Resources/Api/Central/OrderResource.php`
- `app/Http/Resources/Api/Central/PaymentResource.php`
- `app/Http/Resources/Api/Central/DashboardResource.php`

#### Tenant Resources

- `app/Http/Resources/Api/Tenant/AdminResource.php`
- `app/Http/Resources/Api/Tenant/ProductResource.php`
- `app/Http/Resources/Api/Tenant/OrderResource.php`
- `app/Http/Resources/Api/Tenant/CustomerResource.php`
- `app/Http/Resources/Api/Tenant/DashboardResource.php`

### 7. Form Requests (Validation)

#### Central Requests

- `app/Http/Requests/Api/Central/Auth/LoginRequest.php`
- `app/Http/Requests/Api/Central/Tenant/StoreTenantRequest.php`
- `app/Http/Requests/Api/Central/Tenant/UpdateTenantRequest.php`
- `app/Http/Requests/Api/Central/PricePlan/StorePricePlanRequest.php`
- `app/Http/Requests/Api/Central/PricePlan/UpdatePricePlanRequest.php`

#### Tenant Requests

- `app/Http/Requests/Api/Tenant/Auth/LoginRequest.php`
- `app/Http/Requests/Api/Tenant/Product/StoreProductRequest.php`
- `app/Http/Requests/Api/Tenant/Product/UpdateProductRequest.php`
- `app/Http/Requests/Api/Tenant/Order/UpdateOrderRequest.php`

### 8. Middleware

- `app/Http/Middleware/Api/EnsureCentralContext.php` - Ensure central context
- `app/Http/Middleware/Api/EnsureTenantContext.php` - Ensure tenant context
- Update existing middleware for API compatibility

## تقسيم العمل على المبرمجين

### المبرمج 1 (Programmer 1)

**المسؤول عن: Central API**

1. إعداد البنية الأساسية للـ Central API
2. Central Authentication API (login, logout, me, refresh)
3. Central Dashboard API
4. Central Tenants Management API
5. Central Price Plans API
6. Testing بالـ cURL لكل endpoint

### المبرمج 2 (Programmer 2)

**المسؤول عن: Tenant API - Authentication & Dashboard**

1. إعداد البنية الأساسية للـ Tenant API
2. Tenant Authentication API (login, logout, me, refresh)
3. Tenant Dashboard API
4. Tenant Products API
5. Testing بالـ cURL لكل endpoint

### المبرمج 3 (Programmer 3)

**المسؤول عن: Tenant API - Resources & Shared Components**

1. Tenant Orders API
2. Tenant Customers API
3. Tenant Categories API
4. API Resources (JSON formatting) - Central & Tenant
5. Form Requests (Validation) - Central & Tenant
6. Middleware setup
7. Error handling
8. Testing بالـ cURL لكل endpoint

## التنفيذ

### المرحلة 1: إعداد البنية الأساسية (المبرمج 1 & 2)

**المبرمج 1:**

1. إنشاء مجلدات Central API controllers
2. إنشاء `routes/api/central.php`
3. تسجيل Central routes في RouteServiceProvider

**المبرمج 2:**

1. إنشاء مجلدات Tenant API controllers
2. إنشاء `routes/api/tenant.php`
3. تسجيل Tenant routes في RouteServiceProvider
4. إعداد Tenant middleware في routes

### المرحلة 2: Authentication (المبرمج 1 & 2)

**المبرمج 1:**

1. إنشاء `app/Http/Controllers/Api/Central/Auth/AuthController.php`
2. إنشاء `app/Http/Requests/Api/Central/Auth/LoginRequest.php`
3. إعداد Sanctum tokens للـ Central Admin
4. Testing بالـ cURL:
   ```bash
   # Test Central Login
   curl -X POST http://asaas.local/api/central/v1/auth/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@example.com","password":"password"}'
   
   # Test Central Me (بعد الحصول على token)
   curl -X GET http://asaas.local/api/central/v1/auth/me \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   ```


**المبرمج 2:**

1. إنشاء `app/Http/Controllers/Api/Tenant/Auth/AuthController.php`
2. إنشاء `app/Http/Requests/Api/Tenant/Auth/LoginRequest.php`
3. إعداد Sanctum tokens للـ Tenant Admin (مع tenant_id)
4. Testing بالـ cURL:
   ```bash
   # Test Tenant Login (استخدام tenant domain)
   curl -X POST http://tenant1.asaas.local/api/tenant/v1/auth/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@tenant.com","password":"password"}'
   
   # Test Tenant Me
   curl -X GET http://tenant1.asaas.local/api/tenant/v1/auth/me \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   ```


### المرحلة 3: Dashboard APIs (المبرمج 1 & 2)

**المبرمج 1:**

1. إنشاء `app/Http/Controllers/Api/Central/DashboardController.php`
2. إنشاء `app/Http/Resources/Api/Central/DashboardResource.php`
3. إضافة statistics methods
4. Testing بالـ cURL:
   ```bash
   curl -X GET http://asaas.local/api/central/v1/dashboard \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   ```


**المبرمج 2:**

1. إنشاء `app/Http/Controllers/Api/Tenant/DashboardController.php`
2. إنشاء `app/Http/Resources/Api/Tenant/DashboardResource.php`
3. إضافة statistics methods
4. Testing بالـ cURL:
   ```bash
   curl -X GET http://tenant1.asaas.local/api/tenant/v1/dashboard \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   ```


### المرحلة 4: Central Resource APIs (المبرمج 1)

1. إنشاء `app/Http/Controllers/Api/Central/TenantController.php`
2. إنشاء `app/Http/Controllers/Api/Central/PricePlanController.php`
3. إنشاء `app/Http/Controllers/Api/Central/OrderController.php`
4. إنشاء `app/Http/Controllers/Api/Central/PaymentController.php`
5. إنشاء API Resources للـ Central
6. Testing بالـ cURL لكل endpoint:
   ```bash
   # Test Tenants List
   curl -X GET http://asaas.local/api/central/v1/tenants \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   
   # Test Create Tenant
   curl -X POST http://asaas.local/api/central/v1/tenants \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"name":"Test Tenant","domain":"test","email":"test@example.com"}'
   ```


### المرحلة 5: Tenant Resource APIs (المبرمج 2 & 3)

**المبرمج 2:**

1. إنشاء `app/Http/Controllers/Api/Tenant/ProductController.php`
2. إنشاء `app/Http/Requests/Api/Tenant/Product/StoreProductRequest.php`
3. إنشاء `app/Http/Requests/Api/Tenant/Product/UpdateProductRequest.php`
4. Testing بالـ cURL:
   ```bash
   curl -X GET http://tenant1.asaas.local/api/tenant/v1/products \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
   ```


**المبرمج 3:**

1. إنشاء `app/Http/Controllers/Api/Tenant/OrderController.php`
2. إنشاء `app/Http/Controllers/Api/Tenant/CustomerController.php`
3. إنشاء `app/Http/Controllers/Api/Tenant/CategoryController.php`
4. Testing بالـ cURL لكل endpoint

### المرحلة 6: Shared Components (المبرمج 3)

1. إنشاء جميع API Resources (Central & Tenant)
2. إنشاء جميع Form Requests (Central & Tenant)
3. إعداد Middleware (EnsureCentralContext, EnsureTenantContext)
4. إعداد Error Handling الموحد
5. إعداد Rate Limiting
6. إعداد CORS إذا لزم الأمر

### المرحلة 7: Final Testing & Documentation (جميع المبرمجين)

1. Integration testing لجميع endpoints
2. كتابة API documentation
3. إنشاء Postman collection
4. مراجعة Security & Performance

## ملاحظات هامة

1. **Sanctum Configuration**: التأكد من إعداد Sanctum بشكل صحيح في `config/sanctum.php`
2. **Tenancy Middleware**: استخدام middleware مناسبة لضمان context صحيح
3. **API Response Format**: توحيد تنسيق JSON responses
4. **Error Handling**: معالجة الأخطاء بشكل موحد
5. **Rate Limiting**: إضافة rate limiting للـ API endpoints
6. **CORS**: إعداد CORS إذا كانت API للاستخدام من frontend منفصل

## ملفات الإعداد المطلوبة

- تحديث `app/Providers/RouteServiceProvider.php` لتسجيل API routes
- إعداد `config/sanctum.php` إذا لزم الأمر
- إضافة rate limiting في `app/Http/Kernel.php`

## هيكل الاستجابة الموحد (Standard Response Format)

```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...},
  "meta": {...}
}
```

## متطلبات حرجة - Tenancy & Sanctum

### 1. Context Isolation

#### Central API Routes

- **مسار**: `/api/central/v1/*`
- **Middleware**: `api`, `auth:sanctum` (بدون أي tenant middleware)
- **مهم**: لا يجب استخدام `InitializeTenancyByDomain` أو أي tenant middleware
- **Database**: Central database فقط

#### Tenant API Routes

- **مسار**: `/api/tenant/v1/*`
- **Middleware**: `api`, `tenant`, `auth:sanctum` (بعد tenant initialization)
- **مهم**: يجب أن يمر على tenant middleware قبل أي شيء
- **Database**: Tenant database (isolated per tenant)

### 2. Sanctum Configuration

#### Token-Based Authentication (NOT SPA Cookies)

- استخدام **Personal Access Tokens** وليس stateful authentication
- **Bearer Token** في Authorization header
- إعداد `config/sanctum.php`:
  - `guard` => `['web']` (يمكن إضافة admin guard)
  - **لا** استخدام stateful domains للـ API
  - Token expiration: حسب متطلبات المشروع

#### Token Claims - Tenant ID

- عند login للـ Tenant Admin: إضافة `tenant_id` في token claims
- عند login للـ Central Admin: لا يحتاج tenant_id
- Structure:
  ```php
  // Token abilities example
  ['tenant_id' => 1, 'role' => 'admin']
  ```


### 3. Middleware Structure

#### Central API Middleware Stack

```php
Route::middleware([
    'api',
    'auth:sanctum'
])->prefix('api/central/v1')->group(function () {
    // Routes - NO tenant middleware
});
```

#### Tenant API Middleware Stack

```php
Route::middleware([
    'api',
    InitializeTenancyByDomainCustomisedMiddleware::class, // أو EnsureTenantContext
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->prefix('api/tenant/v1')->group(function () {
    // Routes - WITH tenant context
});
```

### 4. Routes Structure

```
/api/central/v1/auth/login          → Central Admin Login
/api/central/v1/dashboard           → Central Dashboard Stats
/api/central/v1/tenants             → Manage Tenants
/api/central/v1/plans               → Manage Price Plans

/api/tenant/v1/auth/login            → Tenant Admin Login (requires tenant domain)
/api/tenant/v1/dashboard             → Tenant Dashboard Stats
/api/tenant/v1/products              → Manage Products
/api/tenant/v1/orders                → Manage Orders
```

### 5. Token Generation Strategy

#### Central Admin Token

```php
$token = $admin->createToken('api-token', [
    'type' => 'central_admin',
    'role' => $admin->roles->pluck('name')->toArray()
])->plainTextToken;
```

#### Tenant Admin Token

```php
$tenant = tenant(); // Get current tenant context
$token = $admin->createToken('api-token', [
    'type' => 'tenant_admin',
    'tenant_id' => $tenant->id,
    'tenant_domain' => $tenant->domains->first()->domain ?? null,
    'role' => $admin->roles->pluck('name')->toArray()
])->plainTextToken;
```

### 6. Validation & Security

#### Central API

- لا يحتاج tenant validation
- Authorization: Admin roles فقط
- Guard: `admin` guard

#### Tenant API

- **يجب** التحقق من tenant context قبل أي query
- Authorization: Tenant Admin roles
- Guard: `admin` guard (tenant context)
- Validation: التأكد من token tenant_id matches request tenant

### 7. Mobile App Flexibility

- Token claims تحتوي tenant_id → يمكن استخدام نفس token من mobile apps
- Testing أسهل (لا حاجة لتغيير domain)
- Future multi-store support

## الأمان

- استخدام Sanctum Personal Access Tokens (Bearer Token)
- التحقق من الـ permissions للـ operations
- Rate limiting للـ API requests
- Validation لجميع الـ inputs
- **Tenant context validation** في Tenant API
- **No tenant context** في Central API

### To-dos

- [ ] إنشاء هيكل المجلدات والملفات الأساسية لـ API (routes, controllers, resources, requests)
- [ ] إنشاء Authentication API للداشبورد المركزي (login, logout, me, refresh)
- [ ] إنشاء Authentication API لداشبورد المستأجر (login, logout, me, refresh)
- [ ] إنشاء Dashboard API للداشبورد المركزي (statistics, charts, recent orders)
- [ ] إنشاء Dashboard API لداشبورد المستأجر (statistics, charts, recent orders)
- [ ] إنشاء Resource APIs للداشبورد المركزي (tenants, plans, orders, payments CRUD)
- [ ] إنشاء Resource APIs لداشبورد المستأجر (products, orders, customers CRUD)
- [ ] إنشاء API Resources لجميع الـ models (JSON response formatting)
- [ ] إنشاء Form Requests للـ validation في جميع الـ endpoints
- [ ] إعداد وإضافة Middleware للـ API (context, rate limiting, CORS)
- [ ] إعداد معالجة موحدة للأخطاء في الـ API responses
- [ ] كتابة API tests وإعداد التوثيق (Postman collection أو Swagger)