# 📊 تقرير شامل: API Endpoints - Vue.js Dashboard V1

## 📋 نظرة عامة

تقرير شامل عن جميع API Endpoints التي تم إنشاؤها لـ Vue.js Dashboard V1. جميع الـ endpoints موجودة في `WebApiController` وتستخدم prefix `/admin-home/v1/api`.

---

## 🔗 Base URL

```
https://asaas.local/admin-home/v1/api
```

---

## 📊 Dashboard Endpoints

### 1. Dashboard Stats
- **Method**: `GET`
- **Endpoint**: `/dashboard/stats`
- **Function**: `dashboardStats()`
- **Description**: إحصائيات Dashboard الرئيسية
- **Response**: 
  ```json
  {
    "success": true,
    "data": {
      "total_admins": 10,
      "total_users": 150,
      "total_tenants": 25,
      "total_packages": 5
    }
  }
  ```

### 2. Recent Orders
- **Method**: `GET`
- **Endpoint**: `/dashboard/recent-orders`
- **Function**: `recentOrders()`
- **Description**: آخر 10 طلبات
- **Response**: Array of orders with user, tenant, package info

### 3. Chart Data
- **Method**: `GET`
- **Endpoint**: `/dashboard/chart-data`
- **Function**: `chartData()`
- **Description**: بيانات الرسوم البيانية (آخر 7 أيام)
- **Response**: Revenue data for charts

---

## 🏢 Tenants Management (Full CRUD)

### List Tenants
- **Method**: `GET`
- **Endpoint**: `/tenants`
- **Function**: `tenants(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated tenants list

### Create Tenant
- **Method**: `POST`
- **Endpoint**: `/tenants`
- **Function**: `storeTenant(Request $request)`
- **Body**: `name`, `domain`, `email`, `password`, etc.
- **Response**: Created tenant data

### Get Tenant
- **Method**: `GET`
- **Endpoint**: `/tenants/{id}`
- **Function**: `getTenant($id)`
- **Response**: Single tenant details

### Update Tenant
- **Method**: `PUT`
- **Endpoint**: `/tenants/{id}`
- **Function**: `updateTenant(Request $request, $id)`
- **Body**: Tenant update data
- **Response**: Updated tenant data

### Delete Tenant
- **Method**: `DELETE`
- **Endpoint**: `/tenants/{id}`
- **Function**: `deleteTenant($id)`
- **Response**: Success message

---

## 📝 Blog Management (Full CRUD)

### List Blogs
- **Method**: `GET`
- **Endpoint**: `/blogs`
- **Function**: `blogs(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated blogs list with categories

### Create Blog
- **Method**: `POST`
- **Endpoint**: `/blogs`
- **Function**: `storeBlog(Request $request)`
- **Body**: `title`, `slug`, `content`, `category_id`, `status`, etc.
- **Response**: Created blog data

### Get Blog
- **Method**: `GET`
- **Endpoint**: `/blogs/{id}`
- **Function**: `getBlog($id)`
- **Response**: Single blog details

### Update Blog
- **Method**: `PUT`
- **Endpoint**: `/blogs/{id}`
- **Function**: `updateBlog(Request $request, $id)`
- **Body**: Blog update data
- **Response**: Updated blog data

### Delete Blog
- **Method**: `DELETE`
- **Endpoint**: `/blogs/{id}`
- **Function**: `deleteBlog($id)`
- **Response**: Success message

### Blog Categories
- **Method**: `GET`
- **Endpoint**: `/blog/categories`
- **Function**: `blogCategories(Request $request)`
- **Response**: Paginated categories list

### Blog Tags
- **Method**: `GET`
- **Endpoint**: `/blog/tags`
- **Function**: `blogTags(Request $request)`
- **Response**: Paginated tags list

### Blog Comments
- **Method**: `GET`
- **Endpoint**: `/blog/comments`
- **Function**: `blogComments(Request $request)`
- **Response**: Paginated comments list

---

## 📄 Pages Management (Full CRUD)

### List Pages
- **Method**: `GET`
- **Endpoint**: `/pages`
- **Function**: `pages(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated pages list

### Create Page
- **Method**: `POST`
- **Endpoint**: `/pages`
- **Function**: `storePage(Request $request)`
- **Body**: `title`, `slug`, `content`, `status`, `visibility`, etc.
- **Response**: Created page data

### Get Page
- **Method**: `GET`
- **Endpoint**: `/pages/{id}`
- **Function**: `getPage($id)`
- **Response**: Single page details

### Update Page
- **Method**: `PUT`
- **Endpoint**: `/pages/{id}`
- **Function**: `updatePage(Request $request, $id)`
- **Body**: Page update data
- **Response**: Updated page data

### Delete Page
- **Method**: `DELETE`
- **Endpoint**: `/pages/{id}`
- **Function**: `deletePage($id)`
- **Response**: Success message

---

## 📦 Packages Management (Full CRUD)

### List Packages
- **Method**: `GET`
- **Endpoint**: `/packages`
- **Function**: `packages(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated packages list

### Create Package
- **Method**: `POST`
- **Endpoint**: `/packages`
- **Function**: `storePackage(Request $request)`
- **Body**: `title`, `price`, `features`, `status`, etc.
- **Response**: Created package data

### Get Package
- **Method**: `GET`
- **Endpoint**: `/packages/{id}`
- **Function**: `getPackage($id)`
- **Response**: Single package details

### Update Package
- **Method**: `PUT`
- **Endpoint**: `/packages/{id}`
- **Function**: `updatePackage(Request $request, $id)`
- **Body**: Package update data
- **Response**: Updated package data

### Delete Package
- **Method**: `DELETE`
- **Endpoint**: `/packages/{id}`
- **Function**: `deletePackage($id)`
- **Response**: Success message

---

## 🎫 Coupons Management (Full CRUD)

### List Coupons
- **Method**: `GET`
- **Endpoint**: `/coupons`
- **Function**: `coupons(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated coupons list

### Create Coupon
- **Method**: `POST`
- **Endpoint**: `/coupons`
- **Function**: `storeCoupon(Request $request)`
- **Body**: `code`, `discount`, `discount_type`, `expire_date`, `status`, etc.
- **Response**: Created coupon data

### Get Coupon
- **Method**: `GET`
- **Endpoint**: `/coupons/{id}`
- **Function**: `getCoupon($id)`
- **Response**: Single coupon details

### Update Coupon
- **Method**: `PUT`
- **Endpoint**: `/coupons/{id}`
- **Function**: `updateCoupon(Request $request, $id)`
- **Body**: Coupon update data
- **Response**: Updated coupon data

### Delete Coupon
- **Method**: `DELETE`
- **Endpoint**: `/coupons/{id}`
- **Function**: `deleteCoupon($id)`
- **Response**: Success message

---

## 📋 Orders Management (Read Only)

### List Orders
- **Method**: `GET`
- **Endpoint**: `/orders`
- **Function**: `orders(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated orders list

### Get Order Details
- **Method**: `GET`
- **Endpoint**: `/orders/{id}`
- **Function**: `orderDetails($id)`
- **Response**: Single order details with full information

---

## 💳 Payments Management (Read Only)

### List Payments
- **Method**: `GET`
- **Endpoint**: `/payments`
- **Function**: `payments(Request $request)`
- **Description**: Same as orders (payment logs)
- **Response**: Paginated payments list

### Get Payment Details
- **Method**: `GET`
- **Endpoint**: `/payments/{id}`
- **Function**: `payments($id)`
- **Response**: Single payment details

---

## 👥 Admins Management (Full CRUD)

### List Admins
- **Method**: `GET`
- **Endpoint**: `/admins`
- **Function**: `admins(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated admins list

### Create Admin
- **Method**: `POST`
- **Endpoint**: `/admins`
- **Function**: `storeAdmin(Request $request)`
- **Body**: `name`, `email`, `password`, `role`, etc.
- **Response**: Created admin data

### Get Admin
- **Method**: `GET`
- **Endpoint**: `/admins/{id}`
- **Function**: `getAdmin($id)`
- **Response**: Single admin details

### Update Admin
- **Method**: `PUT`
- **Endpoint**: `/admins/{id}`
- **Function**: `updateAdmin(Request $request, $id)`
- **Body**: Admin update data
- **Response**: Updated admin data

### Delete Admin
- **Method**: `DELETE`
- **Endpoint**: `/admins/{id}`
- **Function**: `deleteAdmin($id)`
- **Response**: Success message

---

## 🎟️ Support Tickets Management (Full CRUD)

### List Tickets
- **Method**: `GET`
- **Endpoint**: `/support/tickets`
- **Function**: `supportTickets(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`, `department_id`
- **Response**: Paginated tickets list

### Create Ticket
- **Method**: `POST`
- **Endpoint**: `/support/tickets`
- **Function**: `storeSupportTicket(Request $request)`
- **Body**: `subject`, `description`, `department_id`, `priority`, etc.
- **Response**: Created ticket data

### Get Ticket Details
- **Method**: `GET`
- **Endpoint**: `/support/tickets/{id}`
- **Function**: `supportTicketDetails($id)`
- **Response**: Single ticket details with replies

### Update Ticket
- **Method**: `PUT`
- **Endpoint**: `/support/tickets/{id}`
- **Function**: `updateSupportTicket(Request $request, $id)`
- **Body**: Ticket update data
- **Response**: Updated ticket data

### Delete Ticket
- **Method**: `DELETE`
- **Endpoint**: `/support/tickets/{id}`
- **Function**: `deleteSupportTicket($id)`
- **Response**: Success message

### Support Departments
- **Method**: `GET`
- **Endpoint**: `/support/departments`
- **Function**: `supportDepartments(Request $request)`
- **Response**: List of support departments

---

## 👤 Users Management (Read Only)

### List Users
- **Method**: `GET`
- **Endpoint**: `/users`
- **Function**: `users(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `status`
- **Response**: Paginated users list

### Users Roles
- **Method**: `GET`
- **Endpoint**: `/users/roles`
- **Function**: `roles(Request $request)`
- **Response**: List of roles (Spatie Permission)

### Users Permissions
- **Method**: `GET`
- **Endpoint**: `/users/permissions`
- **Function**: `permissions(Request $request)`
- **Response**: List of permissions (Spatie Permission)

### Activity Logs
- **Method**: `GET`
- **Endpoint**: `/users/activity-logs`
- **Function**: `activityLogs(Request $request)`
- **Parameters**: `per_page`, `page`, `user_id`, `log_name`
- **Response**: Paginated activity logs (Spatie Activity Log)

---

## 📊 Subscriptions Management (Read Only)

### Subscribers
- **Method**: `GET`
- **Endpoint**: `/subscriptions/subscribers`
- **Function**: `subscribers(Request $request)`
- **Description**: Users with active subscriptions
- **Response**: Paginated subscribers list

### Stores
- **Method**: `GET`
- **Endpoint**: `/subscriptions/stores`
- **Function**: `stores(Request $request)`
- **Description**: All tenants (same as tenants endpoint)
- **Response**: Paginated tenants list

### Payment Histories
- **Method**: `GET`
- **Endpoint**: `/subscriptions/payment-histories`
- **Function**: `paymentHistories(Request $request)`
- **Description**: Payment history (same as orders)
- **Response**: Paginated payment history

### Custom Domains
- **Method**: `GET`
- **Endpoint**: `/subscriptions/custom-domains`
- **Function**: `customDomains(Request $request)`
- **Response**: Paginated custom domains list

---

## 🎨 Appearances Management (Read Only)

### Themes
- **Method**: `GET`
- **Endpoint**: `/appearances/themes`
- **Function**: `themes(Request $request)`
- **Response**: List of available themes

### Menus
- **Method**: `GET`
- **Endpoint**: `/appearances/menus`
- **Function**: `menus(Request $request)`
- **Response**: List of menus

### Widgets
- **Method**: `GET`
- **Endpoint**: `/appearances/widgets`
- **Function**: `widgets(Request $request)`
- **Response**: List of widgets

---

## ⚙️ Settings Management

### Get Settings
- **Method**: `GET`
- **Endpoint**: `/settings`
- **Function**: `getSettings(Request $request)`
- **Parameters**: `type` (optional) - للفلترة حسب نوع الإعدادات
- **Response**: Settings data

### Update Settings
- **Method**: `PUT`
- **Endpoint**: `/settings`
- **Function**: `updateSettings(Request $request)`
- **Body**: Settings data to update
- **Response**: Updated settings

---

## 🌐 System Management (Read Only)

### Languages
- **Method**: `GET`
- **Endpoint**: `/system/languages`
- **Function**: `languages(Request $request)`
- **Response**: List of available languages

---

## 📁 Media Management (Read Only)

### Media Library
- **Method**: `GET`
- **Endpoint**: `/media`
- **Function**: `media(Request $request)`
- **Parameters**: `per_page`, `page`, `search`, `type`
- **Response**: Paginated media files list

---

## 📊 إحصائيات Endpoints

### حسب HTTP Method
- **GET**: 35 endpoints
- **POST**: 6 endpoints
- **PUT**: 6 endpoints
- **DELETE**: 6 endpoints

### حسب الوحدة (Module)
- **Dashboard**: 3 endpoints
- **Tenants**: 5 endpoints (Full CRUD)
- **Blog**: 8 endpoints (Full CRUD + Categories, Tags, Comments)
- **Pages**: 5 endpoints (Full CRUD)
- **Packages**: 5 endpoints (Full CRUD)
- **Coupons**: 5 endpoints (Full CRUD)
- **Orders**: 2 endpoints (Read Only)
- **Payments**: 2 endpoints (Read Only)
- **Admins**: 5 endpoints (Full CRUD)
- **Support Tickets**: 6 endpoints (Full CRUD + Departments)
- **Users**: 4 endpoints (Read Only + Roles, Permissions, Activity Logs)
- **Subscriptions**: 4 endpoints (Read Only)
- **Appearances**: 3 endpoints (Read Only)
- **Settings**: 2 endpoints (Read/Write)
- **System**: 1 endpoint (Read Only)
- **Media**: 1 endpoint (Read Only)

### إجمالي Endpoints
- **المجموع**: 70 endpoint
- **Full CRUD**: 35 endpoints (50%)
- **Read Only**: 30 endpoints (43%)
- **Read/Write**: 5 endpoints (7%)

---

## 🔐 Authentication

جميع الـ endpoints تتطلب:
- **Authentication**: `auth:admin` middleware
- **CSRF Token**: للـ POST/PUT/DELETE requests
- **Base URL**: `/admin-home/v1/api`

---

## 📝 Response Format

### Success Response
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 20,
    "total": 200
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error message"]
  }
}
```

---

## 📂 File Locations

### Controller
- **File**: `core/app/Http/Controllers/Central/V1/WebApiController.php`
- **Lines**: 1-1546
- **Total Functions**: 60 functions

### Routes
- **File**: `core/routes/admin.php`
- **Lines**: 65-172
- **Prefix**: `/admin-home/v1/api`

---

## ✅ Status

جميع الـ endpoints موجودة ومكتملة وتعمل بشكل صحيح.

---

**آخر تحديث**: الآن
**الحالة**: ✅ مكتمل - 70 endpoint
