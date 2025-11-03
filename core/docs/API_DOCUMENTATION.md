# 📚 RESTful API Documentation

## Base URLs

- **Central API**: `http://asaas.local/api/central/v1`
- **Tenant API**: `http://{tenant-domain}.asaas.local/api/tenant/v1`

---

## Authentication

All API endpoints (except login) require Bearer token authentication.

### Headers Required

```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

### Central Admin Login

**Endpoint:** `POST /api/central/v1/auth/login`

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "admin": {
      "id": 1,
      "name": "Admin",
      "email": "admin@example.com",
      "roles": ["admin"],
      "permissions": ["*"]
    },
    "token": "1|xxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### Tenant Admin Login

**Endpoint:** `POST /api/tenant/v1/auth/login`

**Note:** Must be called from tenant domain (e.g., `tenant1.asaas.local`)

**Request Body:**
```json
{
  "email": "admin@tenant.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "admin": {
      "id": 1,
      "name": "Tenant Admin",
      "email": "admin@tenant.com"
    },
    "tenant": {
      "id": 1,
      "domain": "tenant1"
    },
    "token": "1|xxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

---

## Response Format

All API responses follow this standard format:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  },
  "meta": {
    // Pagination meta (if applicable)
    "current_page": 1,
    "total": 100,
    "per_page": 20
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // Validation errors (if applicable)
  }
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthenticated
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests (Rate Limited)
- `500` - Server Error

---

## Central API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/central/v1/auth/login` | Central admin login | No |
| POST | `/api/central/v1/auth/register` | Register admin | No |
| GET | `/api/central/v1/auth/me` | Get current admin | Yes |
| POST | `/api/central/v1/auth/logout` | Logout | Yes |
| POST | `/api/central/v1/auth/refresh` | Refresh token | Yes |

### Dashboard

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/dashboard` | Dashboard statistics | Yes |
| GET | `/api/central/v1/dashboard/stats` | Detailed statistics | Yes |
| GET | `/api/central/v1/dashboard/recent-orders` | Recent orders | Yes |
| GET | `/api/central/v1/dashboard/chart-data` | Chart data | Yes |

### Tenants Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/tenants` | List tenants | Yes |
| POST | `/api/central/v1/tenants` | Create tenant | Yes |
| GET | `/api/central/v1/tenants/{id}` | Get tenant | Yes |
| PUT | `/api/central/v1/tenants/{id}` | Update tenant | Yes |
| DELETE | `/api/central/v1/tenants/{id}` | Delete tenant | Yes |
| POST | `/api/central/v1/tenants/{id}/activate` | Activate tenant | Yes |
| POST | `/api/central/v1/tenants/{id}/deactivate` | Deactivate tenant | Yes |

**Create Tenant Request:**
```json
{
  "name": "Test Tenant",
  "user_id": 1,
  "domain": "test-tenant",
  "data": {},
  "expire_date": "2025-12-31"
}
```

### Price Plans Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/plans` | List plans | Yes |
| POST | `/api/central/v1/plans` | Create plan | Yes |
| GET | `/api/central/v1/plans/{id}` | Get plan | Yes |
| PUT | `/api/central/v1/plans/{id}` | Update plan | Yes |
| DELETE | `/api/central/v1/plans/{id}` | Delete plan | Yes |

### Orders Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/orders` | List orders | Yes |
| GET | `/api/central/v1/orders/{id}` | Get order | Yes |
| GET | `/api/central/v1/orders/{id}/payment-logs` | Get payment logs | Yes |

### Payments Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/payments` | List payments | Yes |
| GET | `/api/central/v1/payments/{id}` | Get payment | Yes |
| PUT | `/api/central/v1/payments/{id}` | Update payment | Yes |

### Admins Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/admins` | List admins | Yes |
| POST | `/api/central/v1/admins` | Create admin | Yes |
| GET | `/api/central/v1/admins/{id}` | Get admin | Yes |
| PUT | `/api/central/v1/admins/{id}` | Update admin | Yes |
| DELETE | `/api/central/v1/admins/{id}` | Delete admin | Yes |
| POST | `/api/central/v1/admins/{id}/activate` | Activate admin | Yes |
| POST | `/api/central/v1/admins/{id}/deactivate` | Deactivate admin | Yes |

---

## Tenant API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/tenant/v1/auth/login` | Tenant admin login | No |
| GET | `/api/tenant/v1/auth/me` | Get current admin | Yes |
| POST | `/api/tenant/v1/auth/logout` | Logout | Yes |
| POST | `/api/tenant/v1/auth/refresh` | Refresh token | Yes |

**Note:** All Tenant API endpoints must be accessed from tenant domain.

### Dashboard

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/dashboard` | Dashboard statistics | Yes |
| GET | `/api/tenant/v1/dashboard/stats` | Detailed statistics | Yes |
| GET | `/api/tenant/v1/dashboard/recent-orders` | Recent orders | Yes |
| GET | `/api/tenant/v1/dashboard/chart-data` | Chart data | Yes |

### Products Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/products` | List products | Yes |
| POST | `/api/tenant/v1/products` | Create product | Yes |
| GET | `/api/tenant/v1/products/{id}` | Get product | Yes |
| PUT | `/api/tenant/v1/products/{id}` | Update product | Yes |
| DELETE | `/api/tenant/v1/products/{id}` | Delete product | Yes |
| POST | `/api/tenant/v1/products/{id}/activate` | Activate product | Yes |
| POST | `/api/tenant/v1/products/{id}/deactivate` | Deactivate product | Yes |

### Orders Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/orders` | List orders | Yes |
| GET | `/api/tenant/v1/orders/{id}` | Get order | Yes |
| POST | `/api/tenant/v1/orders/{id}/update-status` | Update order status | Yes |
| POST | `/api/tenant/v1/orders/{id}/cancel` | Cancel order | Yes |

**Update Order Status Request:**
```json
{
  "status": "complete",
  "payment_status": "success"
}
```

### Customers Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/customers` | List customers | Yes |
| GET | `/api/tenant/v1/customers/{id}` | Get customer | Yes |
| GET | `/api/tenant/v1/customers/{id}/orders` | Get customer orders | Yes |
| GET | `/api/tenant/v1/customers/{id}/stats` | Get customer statistics | Yes |

### Categories Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/categories` | List categories | Yes |
| POST | `/api/tenant/v1/categories` | Create category | Yes |
| GET | `/api/tenant/v1/categories/{id}` | Get category | Yes |
| PUT | `/api/tenant/v1/categories/{id}` | Update category | Yes |
| DELETE | `/api/tenant/v1/categories/{id}` | Delete category | Yes |
| GET | `/api/tenant/v1/categories/{id}/products` | Get category products | Yes |

**Create Category Request:**
```json
{
  "name": "Electronics",
  "slug": "electronics",
  "description": "Electronic products",
  "image_id": 1,
  "status_id": 1
}
```

---

## Rate Limiting

API endpoints are rate-limited to 60 requests per minute per user/IP.

When rate limit is exceeded, you'll receive:
- **HTTP Status:** `429 Too Many Requests`
- **Response:**
```json
{
  "success": false,
  "message": "Too many requests"
}
```

---

## Error Handling

### Validation Errors (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authentication Errors (401)

```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Not Found Errors (404)

```json
{
  "success": false,
  "message": "Resource not found"
}
```

### Forbidden Errors (403)

```json
{
  "success": false,
  "message": "This endpoint is only accessible from central domain"
}
```

---

## Pagination

List endpoints support pagination:

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 20, max: 100)

**Response:**
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": [
    // Array of items
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 100
  },
  "links": {
    "first": "http://api.example.com/endpoint?page=1",
    "last": "http://api.example.com/endpoint?page=5",
    "prev": null,
    "next": "http://api.example.com/endpoint?page=2"
  }
}
```

---

## Best Practices

1. **Always use HTTPS** in production
2. **Store tokens securely** - Never expose tokens in frontend code
3. **Handle errors gracefully** - Check `success` field before using `data`
4. **Use pagination** - Don't fetch all data at once
5. **Respect rate limits** - Implement exponential backoff
6. **Validate input** - Always validate user input
7. **Use appropriate HTTP methods** - GET for read, POST for create, PUT for update, DELETE for delete

---

## Support

For issues or questions:
- Email: support@asaas.local
- Documentation: `/docs/api`

---

## Additional Endpoints

### Central API Additional Endpoints

#### Media Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/media` | List media files | Yes |
| POST | `/api/central/v1/media` | Upload media file | Yes |
| GET | `/api/central/v1/media/{id}` | Get media file | Yes |
| PUT | `/api/central/v1/media/{id}` | Update media file | Yes |
| DELETE | `/api/central/v1/media/{id}` | Delete media file | Yes |
| POST | `/api/central/v1/media/bulk-delete` | Bulk delete media | Yes |

#### Settings Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/settings` | Get all settings | Yes |
| GET | `/api/central/v1/settings/{key}` | Get setting by key | Yes |
| PUT | `/api/central/v1/settings/{key}` | Update setting | Yes |
| DELETE | `/api/central/v1/settings/{key}` | Delete setting | Yes |

#### Support Tickets
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/support-tickets` | List support tickets | Yes |
| GET | `/api/central/v1/support-tickets/{id}` | Get support ticket | Yes |
| PUT | `/api/central/v1/support-tickets/{id}` | Update support ticket | Yes |
| POST | `/api/central/v1/support-tickets/{id}/update-status` | Update ticket status | Yes |

#### Reports
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/central/v1/reports/sales` | Sales report | Yes |
| GET | `/api/central/v1/reports/products` | Products report | Yes |
| GET | `/api/central/v1/reports/customers` | Customers report | Yes |
| GET | `/api/central/v1/reports/orders` | Orders report | Yes |

---

### Tenant API Additional Endpoints

#### Blog Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/blogs` | List blogs | Yes |
| POST | `/api/tenant/v1/blogs` | Create blog | Yes |
| GET | `/api/tenant/v1/blogs/{id}` | Get blog | Yes |
| PUT | `/api/tenant/v1/blogs/{id}` | Update blog | Yes |
| DELETE | `/api/tenant/v1/blogs/{id}` | Delete blog | Yes |
| POST | `/api/tenant/v1/blogs/{id}/publish` | Publish blog | Yes |
| POST | `/api/tenant/v1/blogs/{id}/unpublish` | Unpublish blog | Yes |

#### Blog Categories
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/blog-categories` | List categories | Yes |
| POST | `/api/tenant/v1/blog-categories` | Create category | Yes |
| GET | `/api/tenant/v1/blog-categories/{id}` | Get category | Yes |
| PUT | `/api/tenant/v1/blog-categories/{id}` | Update category | Yes |
| DELETE | `/api/tenant/v1/blog-categories/{id}` | Delete category | Yes |
| GET | `/api/tenant/v1/blog-categories/{id}/blogs` | Get category blogs | Yes |

#### Pages Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/pages` | List pages | Yes |
| POST | `/api/tenant/v1/pages` | Create page | Yes |
| GET | `/api/tenant/v1/pages/{id}` | Get page | Yes |
| PUT | `/api/tenant/v1/pages/{id}` | Update page | Yes |
| DELETE | `/api/tenant/v1/pages/{id}` | Delete page | Yes |
| POST | `/api/tenant/v1/pages/{id}/publish` | Publish page | Yes |
| POST | `/api/tenant/v1/pages/{id}/unpublish` | Unpublish page | Yes |

#### Media Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/media` | List media files | Yes |
| POST | `/api/tenant/v1/media` | Upload media file | Yes |
| GET | `/api/tenant/v1/media/{id}` | Get media file | Yes |
| PUT | `/api/tenant/v1/media/{id}` | Update media file | Yes |
| DELETE | `/api/tenant/v1/media/{id}` | Delete media file | Yes |
| POST | `/api/tenant/v1/media/bulk-delete` | Bulk delete media | Yes |

#### Settings Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/settings` | Get all settings | Yes |
| GET | `/api/tenant/v1/settings/{key}` | Get setting by key | Yes |
| PUT | `/api/tenant/v1/settings/{key}` | Update setting | Yes |
| DELETE | `/api/tenant/v1/settings/{key}` | Delete setting | Yes |

#### Coupons Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/coupons` | List coupons | Yes |
| POST | `/api/tenant/v1/coupons` | Create coupon | Yes |
| GET | `/api/tenant/v1/coupons/{id}` | Get coupon | Yes |
| PUT | `/api/tenant/v1/coupons/{id}` | Update coupon | Yes |
| DELETE | `/api/tenant/v1/coupons/{id}` | Delete coupon | Yes |
| POST | `/api/tenant/v1/coupons/{id}/activate` | Activate coupon | Yes |
| POST | `/api/tenant/v1/coupons/{id}/deactivate` | Deactivate coupon | Yes |
| POST | `/api/tenant/v1/coupons/validate` | Validate coupon code | Yes |

#### Shipping Zones
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/shipping-zones` | List zones | Yes |
| POST | `/api/tenant/v1/shipping-zones` | Create zone | Yes |
| GET | `/api/tenant/v1/shipping-zones/{id}` | Get zone | Yes |
| PUT | `/api/tenant/v1/shipping-zones/{id}` | Update zone | Yes |
| DELETE | `/api/tenant/v1/shipping-zones/{id}` | Delete zone | Yes |

#### Shipping Methods
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/shipping-methods` | List methods | Yes |
| POST | `/api/tenant/v1/shipping-methods` | Create method | Yes |
| GET | `/api/tenant/v1/shipping-methods/{id}` | Get method | Yes |
| PUT | `/api/tenant/v1/shipping-methods/{id}` | Update method | Yes |
| DELETE | `/api/tenant/v1/shipping-methods/{id}` | Delete method | Yes |

#### Inventory Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/inventory` | List inventory | Yes |
| POST | `/api/tenant/v1/inventory` | Create inventory | Yes |
| GET | `/api/tenant/v1/inventory/{id}` | Get inventory | Yes |
| PUT | `/api/tenant/v1/inventory/{id}` | Update inventory | Yes |
| DELETE | `/api/tenant/v1/inventory/{id}` | Delete inventory | Yes |
| POST | `/api/tenant/v1/inventory/{id}/adjust-stock` | Adjust stock | Yes |

#### Wallet Management
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/wallet` | List wallets | Yes |
| GET | `/api/tenant/v1/wallet/{id}` | Get wallet | Yes |
| PUT | `/api/tenant/v1/wallet/{id}` | Update wallet | Yes |
| POST | `/api/tenant/v1/wallet/{id}/add-balance` | Add balance | Yes |
| POST | `/api/tenant/v1/wallet/{id}/deduct-balance` | Deduct balance | Yes |

#### Support Tickets
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/support-tickets` | List tickets | Yes |
| POST | `/api/tenant/v1/support-tickets` | Create ticket | Yes |
| GET | `/api/tenant/v1/support-tickets/{id}` | Get ticket | Yes |
| PUT | `/api/tenant/v1/support-tickets/{id}` | Update ticket | Yes |
| DELETE | `/api/tenant/v1/support-tickets/{id}` | Delete ticket | Yes |
| POST | `/api/tenant/v1/support-tickets/{id}/update-status` | Update status | Yes |

#### Reports
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/reports/sales` | Sales report | Yes |
| GET | `/api/tenant/v1/reports/products` | Products report | Yes |
| GET | `/api/tenant/v1/reports/customers` | Customers report | Yes |
| GET | `/api/tenant/v1/reports/orders` | Orders report | Yes |

#### Product Reviews
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/product-reviews` | List reviews | Yes |
| POST | `/api/tenant/v1/product-reviews` | Create review | Yes |
| GET | `/api/tenant/v1/product-reviews/{id}` | Get review | Yes |
| PUT | `/api/tenant/v1/product-reviews/{id}` | Update review | Yes |
| DELETE | `/api/tenant/v1/product-reviews/{id}` | Delete review | Yes |
| POST | `/api/tenant/v1/product-reviews/{id}/approve` | Approve review | Yes |
| POST | `/api/tenant/v1/product-reviews/{id}/reject` | Reject review | Yes |

#### Refunds
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/refunds` | List refunds | Yes |
| POST | `/api/tenant/v1/refunds` | Create refund | Yes |
| GET | `/api/tenant/v1/refunds/{id}` | Get refund | Yes |
| PUT | `/api/tenant/v1/refunds/{id}` | Update refund | Yes |
| DELETE | `/api/tenant/v1/refunds/{id}` | Delete refund | Yes |
| POST | `/api/tenant/v1/refunds/{id}/approve` | Approve refund | Yes |
| POST | `/api/tenant/v1/refunds/{id}/reject` | Reject refund | Yes |

#### Taxes
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/taxes` | List taxes | Yes |
| POST | `/api/tenant/v1/taxes` | Create tax | Yes |
| GET | `/api/tenant/v1/taxes/{id}` | Get tax | Yes |
| PUT | `/api/tenant/v1/taxes/{id}` | Update tax | Yes |
| DELETE | `/api/tenant/v1/taxes/{id}` | Delete tax | Yes |

#### Newsletter
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/newsletters` | List newsletters | Yes |
| POST | `/api/tenant/v1/newsletters` | Create newsletter | Yes |
| GET | `/api/tenant/v1/newsletters/{id}` | Get newsletter | Yes |
| PUT | `/api/tenant/v1/newsletters/{id}` | Update newsletter | Yes |
| DELETE | `/api/tenant/v1/newsletters/{id}` | Delete newsletter | Yes |
| POST | `/api/tenant/v1/newsletters/{id}/subscribe` | Subscribe | Yes |
| POST | `/api/tenant/v1/newsletters/{id}/unsubscribe` | Unsubscribe | Yes |

#### Badges
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/badges` | List badges | Yes |
| POST | `/api/tenant/v1/badges` | Create badge | Yes |
| GET | `/api/tenant/v1/badges/{id}` | Get badge | Yes |
| PUT | `/api/tenant/v1/badges/{id}` | Update badge | Yes |
| DELETE | `/api/tenant/v1/badges/{id}` | Delete badge | Yes |

#### Campaigns
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/campaigns` | List campaigns | Yes |
| POST | `/api/tenant/v1/campaigns` | Create campaign | Yes |
| GET | `/api/tenant/v1/campaigns/{id}` | Get campaign | Yes |
| PUT | `/api/tenant/v1/campaigns/{id}` | Update campaign | Yes |
| DELETE | `/api/tenant/v1/campaigns/{id}` | Delete campaign | Yes |
| POST | `/api/tenant/v1/campaigns/{id}/activate` | Activate campaign | Yes |
| POST | `/api/tenant/v1/campaigns/{id}/deactivate` | Deactivate campaign | Yes |

#### Digital Products
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/digital-products` | List digital products | Yes |
| POST | `/api/tenant/v1/digital-products` | Create digital product | Yes |
| GET | `/api/tenant/v1/digital-products/{id}` | Get digital product | Yes |
| PUT | `/api/tenant/v1/digital-products/{id}` | Update digital product | Yes |
| DELETE | `/api/tenant/v1/digital-products/{id}` | Delete digital product | Yes |
| POST | `/api/tenant/v1/digital-products/{id}/activate` | Activate product | Yes |
| POST | `/api/tenant/v1/digital-products/{id}/deactivate` | Deactivate product | Yes |

#### Countries & States
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/countries` | List countries | Yes |
| GET | `/api/tenant/v1/countries/{id}` | Get country | Yes |
| GET | `/api/tenant/v1/states` | List states | Yes |
| GET | `/api/tenant/v1/states/{id}` | Get state | Yes |

#### Services
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/services` | List services | Yes |
| POST | `/api/tenant/v1/services` | Create service | Yes |
| GET | `/api/tenant/v1/services/{id}` | Get service | Yes |
| PUT | `/api/tenant/v1/services/{id}` | Update service | Yes |
| DELETE | `/api/tenant/v1/services/{id}` | Delete service | Yes |

#### Service Categories
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/service-categories` | List categories | Yes |
| POST | `/api/tenant/v1/service-categories` | Create category | Yes |
| GET | `/api/tenant/v1/service-categories/{id}` | Get category | Yes |
| PUT | `/api/tenant/v1/service-categories/{id}` | Update category | Yes |
| DELETE | `/api/tenant/v1/service-categories/{id}` | Delete category | Yes |
| GET | `/api/tenant/v1/service-categories/{id}/services` | Get category services | Yes |

#### Sales Reports
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/sales-reports` | Get sales report | Yes |
| GET | `/api/tenant/v1/sales-reports/today` | Today's sales report | Yes |
| GET | `/api/tenant/v1/sales-reports/weekly` | Weekly sales report | Yes |
| GET | `/api/tenant/v1/sales-reports/monthly` | Monthly sales report | Yes |
| GET | `/api/tenant/v1/sales-reports/yearly` | Yearly sales report | Yes |

#### Site Analytics
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/site-analytics` | Get site analytics | Yes |
| GET | `/api/tenant/v1/site-analytics/visitors` | Get visitor statistics | Yes |
| GET | `/api/tenant/v1/site-analytics/orders` | Get order statistics | Yes |

#### Attributes Module

##### Product Attributes
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/product-attributes` | List attributes | Yes |
| POST | `/api/tenant/v1/product-attributes` | Create attribute | Yes |
| GET | `/api/tenant/v1/product-attributes/{id}` | Get attribute | Yes |
| PUT | `/api/tenant/v1/product-attributes/{id}` | Update attribute | Yes |
| DELETE | `/api/tenant/v1/product-attributes/{id}` | Delete attribute | Yes |

##### Brands
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/brands` | List brands | Yes |
| POST | `/api/tenant/v1/brands` | Create brand | Yes |
| GET | `/api/tenant/v1/brands/{id}` | Get brand | Yes |
| PUT | `/api/tenant/v1/brands/{id}` | Update brand | Yes |
| DELETE | `/api/tenant/v1/brands/{id}` | Delete brand | Yes |

##### Colors
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/colors` | List colors | Yes |
| POST | `/api/tenant/v1/colors` | Create color | Yes |
| GET | `/api/tenant/v1/colors/{id}` | Get color | Yes |
| PUT | `/api/tenant/v1/colors/{id}` | Update color | Yes |
| DELETE | `/api/tenant/v1/colors/{id}` | Delete color | Yes |

##### Sizes
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/sizes` | List sizes | Yes |
| POST | `/api/tenant/v1/sizes` | Create size | Yes |
| GET | `/api/tenant/v1/sizes/{id}` | Get size | Yes |
| PUT | `/api/tenant/v1/sizes/{id}` | Update size | Yes |
| DELETE | `/api/tenant/v1/sizes/{id}` | Delete size | Yes |

##### Tags
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/tags` | List tags | Yes |
| POST | `/api/tenant/v1/tags` | Create tag | Yes |
| GET | `/api/tenant/v1/tags/{id}` | Get tag | Yes |
| PUT | `/api/tenant/v1/tags/{id}` | Update tag | Yes |
| DELETE | `/api/tenant/v1/tags/{id}` | Delete tag | Yes |

##### Units
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/units` | List units | Yes |
| POST | `/api/tenant/v1/units` | Create unit | Yes |
| GET | `/api/tenant/v1/units/{id}` | Get unit | Yes |
| PUT | `/api/tenant/v1/units/{id}` | Update unit | Yes |
| DELETE | `/api/tenant/v1/units/{id}` | Delete unit | Yes |

##### Sub Categories
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/sub-categories` | List sub categories | Yes |
| POST | `/api/tenant/v1/sub-categories` | Create sub category | Yes |
| GET | `/api/tenant/v1/sub-categories/{id}` | Get sub category | Yes |
| PUT | `/api/tenant/v1/sub-categories/{id}` | Update sub category | Yes |
| DELETE | `/api/tenant/v1/sub-categories/{id}` | Delete sub category | Yes |

##### Child Categories
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/child-categories` | List child categories | Yes |
| POST | `/api/tenant/v1/child-categories` | Create child category | Yes |
| GET | `/api/tenant/v1/child-categories/{id}` | Get child category | Yes |
| PUT | `/api/tenant/v1/child-categories/{id}` | Update child category | Yes |
| DELETE | `/api/tenant/v1/child-categories/{id}` | Delete child category | Yes |

##### Delivery Options
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/delivery-options` | List delivery options | Yes |
| POST | `/api/tenant/v1/delivery-options` | Create delivery option | Yes |
| GET | `/api/tenant/v1/delivery-options/{id}` | Get delivery option | Yes |
| PUT | `/api/tenant/v1/delivery-options/{id}` | Update delivery option | Yes |
| DELETE | `/api/tenant/v1/delivery-options/{id}` | Delete delivery option | Yes |

##### Cities
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/tenant/v1/cities` | List cities | Yes |
| POST | `/api/tenant/v1/cities` | Create city | Yes |
| GET | `/api/tenant/v1/cities/{id}` | Get city | Yes |
| PUT | `/api/tenant/v1/cities/{id}` | Update city | Yes |
| DELETE | `/api/tenant/v1/cities/{id}` | Delete city | Yes |

---

**Last Updated:** 2025-11-03  
**Total Endpoints:** 235+ endpoints  
**Total Controllers:** 53 controllers (41 Tenant + 11 Central + 1 Base)

