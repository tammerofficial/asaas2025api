# 📋 قائمة الـ APIs المتبقية - Missing APIs Checklist

## ✅ ما تم تنفيذه (14 API Controllers)

### Central API (7 controllers)
1. ✅ AuthController - 4 endpoints
2. ✅ DashboardController - 4 endpoints
3. ✅ TenantController - 7 endpoints
4. ✅ PricePlanController - 5 endpoints
5. ✅ OrderController - 3 endpoints
6. ✅ PaymentController - 3 endpoints
7. ✅ AdminController - 7 endpoints

### Tenant API (6 controllers)
8. ✅ AuthController - 4 endpoints
9. ✅ DashboardController - 4 endpoints
10. ✅ ProductController - 7 endpoints
11. ✅ OrderController - 7 endpoints
12. ✅ CustomerController - 5 endpoints
13. ✅ CategoryController - 5 endpoints

### Base (1 controller)
14. ✅ BaseApiController - Helper methods

---

## ❌ ما لم يتم تنفيذه (27 Modules تحتاج API)

### Priority 1 (High) - ميزات أساسية

#### 1. Blog Module (`Modules/Blog`)
- ❌ `BlogController` (Tenant)
  - GET `/api/tenant/v1/blogs` - List blogs
  - POST `/api/tenant/v1/blogs` - Create blog
  - GET `/api/tenant/v1/blogs/{id}` - Get blog
  - PUT `/api/tenant/v1/blogs/{id}` - Update blog
  - DELETE `/api/tenant/v1/blogs/{id}` - Delete blog
  - POST `/api/tenant/v1/blogs/{id}/publish` - Publish blog
  - POST `/api/tenant/v1/blogs/{id}/unpublish` - Unpublish blog

- ❌ `BlogCategoryController` (Tenant)
  - GET `/api/tenant/v1/blog-categories` - List categories
  - POST `/api/tenant/v1/blog-categories` - Create category
  - GET `/api/tenant/v1/blog-categories/{id}` - Get category
  - PUT `/api/tenant/v1/blog-categories/{id}` - Update category
  - DELETE `/api/tenant/v1/blog-categories/{id}` - Delete category
  - GET `/api/tenant/v1/blog-categories/{id}/blogs` - Get blogs by category

**Estimated Endpoints:** 13 endpoints

#### 2. Pages Module (`app/Models/Page`)
- ❌ `PageController` (Tenant)
  - GET `/api/tenant/v1/pages` - List pages
  - POST `/api/tenant/v1/pages` - Create page
  - GET `/api/tenant/v1/pages/{id}` - Get page
  - PUT `/api/tenant/v1/pages/{id}` - Update page
  - DELETE `/api/tenant/v1/pages/{id}` - Delete page
  - POST `/api/tenant/v1/pages/{id}/publish` - Publish page

**Estimated Endpoints:** 6 endpoints

#### 3. Media Upload Module (`app/Models/MediaUploader`)
- ❌ `MediaController` (Tenant & Central)
  - POST `/api/tenant/v1/media/upload` - Upload file
  - POST `/api/tenant/v1/media/upload-multiple` - Upload multiple files
  - GET `/api/tenant/v1/media` - List media files
  - GET `/api/tenant/v1/media/{id}` - Get media file
  - DELETE `/api/tenant/v1/media/{id}` - Delete media file
  - DELETE `/api/tenant/v1/media/bulk-delete` - Bulk delete
  - GET `/api/tenant/v1/media/search` - Search media

**Estimated Endpoints:** 7 endpoints (Tenant) + 7 endpoints (Central) = 14 endpoints

#### 4. Settings Module
- ❌ `SettingsController` (Central)
  - GET `/api/central/v1/settings` - Get all settings
  - GET `/api/central/v1/settings/{key}` - Get setting by key
  - PUT `/api/central/v1/settings/{key}` - Update setting
  - POST `/api/central/v1/settings/bulk-update` - Bulk update

- ❌ `TenantSettingsController` (Tenant)
  - GET `/api/tenant/v1/settings` - Get all settings
  - GET `/api/tenant/v1/settings/{key}` - Get setting by key
  - PUT `/api/tenant/v1/settings/{key}` - Update setting
  - POST `/api/tenant/v1/settings/bulk-update` - Bulk update

**Estimated Endpoints:** 8 endpoints

---

### Priority 2 (Medium) - ميزات مهمة

#### 5. Coupons Module (`Modules/CouponManage`)
- ❌ `CouponController` (Tenant)
  - GET `/api/tenant/v1/coupons` - List coupons
  - POST `/api/tenant/v1/coupons` - Create coupon
  - GET `/api/tenant/v1/coupons/{id}` - Get coupon
  - PUT `/api/tenant/v1/coupons/{id}` - Update coupon
  - DELETE `/api/tenant/v1/coupons/{id}` - Delete coupon
  - POST `/api/tenant/v1/coupons/{id}/activate` - Activate coupon
  - POST `/api/tenant/v1/coupons/{id}/deactivate` - Deactivate coupon
  - POST `/api/tenant/v1/coupons/validate` - Validate coupon code

**Estimated Endpoints:** 8 endpoints

#### 6. Shipping Module (`Modules/ShippingModule`)
- ❌ `ShippingZoneController` (Tenant)
  - GET `/api/tenant/v1/shipping/zones` - List zones
  - POST `/api/tenant/v1/shipping/zones` - Create zone
  - GET `/api/tenant/v1/shipping/zones/{id}` - Get zone
  - PUT `/api/tenant/v1/shipping/zones/{id}` - Update zone
  - DELETE `/api/tenant/v1/shipping/zones/{id}` - Delete zone

- ❌ `ShippingMethodController` (Tenant)
  - GET `/api/tenant/v1/shipping/methods` - List methods
  - POST `/api/tenant/v1/shipping/methods` - Create method
  - GET `/api/tenant/v1/shipping/methods/{id}` - Get method
  - PUT `/api/tenant/v1/shipping/methods/{id}` - Update method
  - DELETE `/api/tenant/v1/shipping/methods/{id}` - Delete method
  - POST `/api/tenant/v1/shipping/methods/{id}/set-default` - Set default

**Estimated Endpoints:** 11 endpoints

#### 7. Inventory Module (`Modules/Inventory`)
- ❌ `InventoryController` (Tenant)
  - GET `/api/tenant/v1/inventory` - List inventory
  - GET `/api/tenant/v1/inventory/{product_id}` - Get product inventory
  - PUT `/api/tenant/v1/inventory/{product_id}` - Update inventory
  - POST `/api/tenant/v1/inventory/adjust` - Adjust inventory
  - GET `/api/tenant/v1/inventory/low-stock` - Get low stock items
  - POST `/api/tenant/v1/inventory/bulk-update` - Bulk update

**Estimated Endpoints:** 6 endpoints

#### 8. Wallet Module (`Modules/Wallet`)
- ❌ `WalletController` (Tenant)
  - GET `/api/tenant/v1/wallet` - Get wallet balance
  - GET `/api/tenant/v1/wallet/transactions` - Get transactions
  - POST `/api/tenant/v1/wallet/add-funds` - Add funds
  - POST `/api/tenant/v1/wallet/withdraw` - Withdraw funds
  - GET `/api/tenant/v1/wallet/statistics` - Get wallet statistics

**Estimated Endpoints:** 5 endpoints

#### 9. SupportTicket Module (`Modules/SupportTicket`)
- ❌ `SupportTicketController` (Tenant & Central)
  - GET `/api/tenant/v1/support-tickets` - List tickets
  - POST `/api/tenant/v1/support-tickets` - Create ticket
  - GET `/api/tenant/v1/support-tickets/{id}` - Get ticket
  - PUT `/api/tenant/v1/support-tickets/{id}` - Update ticket
  - POST `/api/tenant/v1/support-tickets/{id}/close` - Close ticket
  - POST `/api/tenant/v1/support-tickets/{id}/reply` - Reply to ticket
  - GET `/api/tenant/v1/support-tickets/{id}/messages` - Get messages

**Estimated Endpoints:** 7 endpoints (Tenant) + 7 endpoints (Central) = 14 endpoints

#### 10. Reports & Analytics
- ❌ `ReportController` (Central)
  - GET `/api/central/v1/reports/overview` - Overview report
  - GET `/api/central/v1/reports/revenue` - Revenue report
  - GET `/api/central/v1/reports/tenants` - Tenants report
  - GET `/api/central/v1/reports/orders` - Orders report

- ❌ `ReportController` (Tenant)
  - GET `/api/tenant/v1/reports/overview` - Overview report
  - GET `/api/tenant/v1/reports/sales` - Sales report
  - GET `/api/tenant/v1/reports/products` - Products report
  - GET `/api/tenant/v1/reports/customers` - Customers report

**Estimated Endpoints:** 8 endpoints

---

### Priority 3 (Low) - ميزات إضافية

#### 11. Reviews Module (`Modules/Product/ProductReviews`)
- ❌ `ProductReviewController` (Tenant)
  - GET `/api/tenant/v1/products/{id}/reviews` - Get product reviews
  - POST `/api/tenant/v1/products/{id}/reviews` - Add review
  - PUT `/api/tenant/v1/reviews/{id}` - Update review
  - DELETE `/api/tenant/v1/reviews/{id}` - Delete review
  - POST `/api/tenant/v1/reviews/{id}/approve` - Approve review
  - POST `/api/tenant/v1/reviews/{id}/reject` - Reject review

**Estimated Endpoints:** 6 endpoints

#### 12. Refund Module (`Modules/RefundModule`)
- ❌ `RefundController` (Tenant)
  - GET `/api/tenant/v1/refunds` - List refunds
  - POST `/api/tenant/v1/refunds` - Create refund request
  - GET `/api/tenant/v1/refunds/{id}` - Get refund
  - POST `/api/tenant/v1/refunds/{id}/approve` - Approve refund
  - POST `/api/tenant/v1/refunds/{id}/reject` - Reject refund
  - GET `/api/tenant/v1/refunds/{id}/messages` - Get refund messages

**Estimated Endpoints:** 6 endpoints

#### 13. Tax Module (`Modules/TaxModule`)
- ❌ `TaxController` (Tenant)
  - GET `/api/tenant/v1/taxes` - List taxes
  - POST `/api/tenant/v1/taxes` - Create tax
  - GET `/api/tenant/v1/taxes/{id}` - Get tax
  - PUT `/api/tenant/v1/taxes/{id}` - Update tax
  - DELETE `/api/tenant/v1/taxes/{id}` - Delete tax

**Estimated Endpoints:** 5 endpoints

#### 14. NewsLetter Module (`Modules/NewsLetter`)
- ❌ `NewsletterController` (Tenant)
  - GET `/api/tenant/v1/newsletters` - List subscribers
  - POST `/api/tenant/v1/newsletters/subscribe` - Subscribe
  - POST `/api/tenant/v1/newsletters/unsubscribe` - Unsubscribe
  - GET `/api/tenant/v1/newsletters/campaigns` - List campaigns
  - POST `/api/tenant/v1/newsletters/campaigns` - Create campaign
  - POST `/api/tenant/v1/newsletters/campaigns/{id}/send` - Send campaign

**Estimated Endpoints:** 6 endpoints

#### 15. Badge Module (`Modules/Badge`)
- ❌ `BadgeController` (Tenant)
  - GET `/api/tenant/v1/badges` - List badges
  - POST `/api/tenant/v1/badges` - Create badge
  - PUT `/api/tenant/v1/badges/{id}` - Update badge
  - DELETE `/api/tenant/v1/badges/{id}` - Delete badge

**Estimated Endpoints:** 4 endpoints

#### 16. Campaign Module (`Modules/Campaign`)
- ❌ `CampaignController` (Tenant)
  - GET `/api/tenant/v1/campaigns` - List campaigns
  - POST `/api/tenant/v1/campaigns` - Create campaign
  - GET `/api/tenant/v1/campaigns/{id}` - Get campaign
  - PUT `/api/tenant/v1/campaigns/{id}` - Update campaign
  - DELETE `/api/tenant/v1/campaigns/{id}` - Delete campaign
  - POST `/api/tenant/v1/campaigns/{id}/activate` - Activate campaign

**Estimated Endpoints:** 6 endpoints

#### 17. DigitalProduct Module (`Modules/DigitalProduct`)
- ❌ `DigitalProductController` (Tenant)
  - GET `/api/tenant/v1/digital-products` - List digital products
  - POST `/api/tenant/v1/digital-products` - Create digital product
  - GET `/api/tenant/v1/digital-products/{id}` - Get digital product
  - PUT `/api/tenant/v1/digital-products/{id}` - Update digital product
  - DELETE `/api/tenant/v1/digital-products/{id}` - Delete digital product
  - POST `/api/tenant/v1/digital-products/{id}/download` - Download product

**Estimated Endpoints:** 6 endpoints

#### 18. Country Manage Module (`Modules/CountryManage`)
- ❌ `CountryController` (Tenant)
  - GET `/api/tenant/v1/countries` - List countries
  - GET `/api/tenant/v1/countries/{id}/states` - Get states
  - GET `/api/tenant/v1/countries/{id}/cities` - Get cities

**Estimated Endpoints:** 3 endpoints

#### 19. Service Module (`Modules/Service`)
- ❌ `ServiceController` (Tenant)
  - GET `/api/tenant/v1/services` - List services
  - POST `/api/tenant/v1/services` - Create service
  - GET `/api/tenant/v1/services/{id}` - Get service
  - PUT `/api/tenant/v1/services/{id}` - Update service
  - DELETE `/api/tenant/v1/services/{id}` - Delete service

**Estimated Endpoints:** 5 endpoints

#### 20-27. Other Modules (Low Priority)
- ❌ SalesReport Module
- ❌ CloudStorage Module
- ❌ DomainReseller Module
- ❌ Integrations Module
- ❌ SiteAnalytics Module
- ❌ CpanelAutomation Module
- ❌ PluginManage Module
- ❌ ThemeManage Module

**Estimated Endpoints:** ~30-40 endpoints

---

## 📊 الملخص

### إجمالي الـ APIs المطلوبة:
| Priority | Modules | Controllers | Estimated Endpoints |
|----------|---------|-------------|---------------------|
| **Priority 1** | 4 modules | 5 controllers | ~41 endpoints |
| **Priority 2** | 6 modules | 8 controllers | ~48 endpoints |
| **Priority 3** | 17 modules | 20+ controllers | ~80+ endpoints |
| **Total** | **27 modules** | **33+ controllers** | **~170+ endpoints** |

### التغطية الحالية:
- ✅ **14 API Controllers** موجودة
- ❌ **33+ API Controllers** مطلوبة
- ✅ **45+ endpoints** موجودة
- ❌ **170+ endpoints** مطلوبة

### النسبة:
- **Coverage:** ~18-20% من الميزات الكاملة
- **Missing:** ~80-82% من الميزات

---

**آخر تحديث:** 2025-11-03

