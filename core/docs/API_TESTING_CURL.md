# 🧪 API Testing with cURL

This document provides cURL examples for testing all API endpoints.

---

## Setup

### 1. Set Base URLs
```bash
# Central API
export CENTRAL_API="http://asaas.local/api/central/v1"

# Tenant API (replace tenant1 with your tenant domain)
export TENANT_API="http://tenant1.asaas.local/api/tenant/v1"
```

### 2. Store Token
After login, save the token:
```bash
export TOKEN="your-token-here"
```

---

## Central API Tests

### Authentication

#### 1. Central Admin Login
```bash
curl -X POST "$CENTRAL_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

#### 2. Get Current Admin
```bash
curl -X GET "$CENTRAL_API/auth/me" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 3. Logout
```bash
curl -X POST "$CENTRAL_API/auth/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 4. Refresh Token
```bash
curl -X POST "$CENTRAL_API/auth/refresh" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Dashboard

#### 5. Get Dashboard Statistics
```bash
curl -X GET "$CENTRAL_API/dashboard" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 6. Get Detailed Stats
```bash
curl -X GET "$CENTRAL_API/dashboard/stats" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 7. Get Recent Orders
```bash
curl -X GET "$CENTRAL_API/dashboard/recent-orders" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 8. Get Chart Data
```bash
curl -X GET "$CENTRAL_API/dashboard/chart-data" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Tenants Management

#### 9. List Tenants
```bash
curl -X GET "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 10. List Tenants with Pagination
```bash
curl -X GET "$CENTRAL_API/tenants?page=1&per_page=10" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 11. Get Tenant
```bash
curl -X GET "$CENTRAL_API/tenants/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 12. Create Tenant
```bash
curl -X POST "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Tenant",
    "user_id": 1,
    "domain": "test-tenant",
    "data": {},
    "expire_date": "2025-12-31"
  }'
```

#### 13. Update Tenant
```bash
curl -X PUT "$CENTRAL_API/tenants/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Updated Tenant Name",
    "data": {"key": "value"}
  }'
```

#### 14. Delete Tenant
```bash
curl -X DELETE "$CENTRAL_API/tenants/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 15. Activate Tenant
```bash
curl -X POST "$CENTRAL_API/tenants/1/activate" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 16. Deactivate Tenant
```bash
curl -X POST "$CENTRAL_API/tenants/1/deactivate" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Price Plans Management

#### 17. List Plans
```bash
curl -X GET "$CENTRAL_API/plans" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 18. Get Plan
```bash
curl -X GET "$CENTRAL_API/plans/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 19. Create Plan
```bash
curl -X POST "$CENTRAL_API/plans" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Premium Plan",
    "price": 99.99,
    "type": 0,
    "status": 1,
    "features": ["feature1", "feature2"],
    "free_trial": 7
  }'
```

#### 20. Update Plan
```bash
curl -X PUT "$CENTRAL_API/plans/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Updated Plan Name",
    "price": 149.99
  }'
```

#### 21. Delete Plan
```bash
curl -X DELETE "$CENTRAL_API/plans/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Orders Management

#### 22. List Orders
```bash
curl -X GET "$CENTRAL_API/orders" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 23. Get Order
```bash
curl -X GET "$CENTRAL_API/orders/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 24. Get Order Payment Logs
```bash
curl -X GET "$CENTRAL_API/orders/1/payment-logs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Payments Management

#### 25. List Payments
```bash
curl -X GET "$CENTRAL_API/payments" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 26. Get Payment
```bash
curl -X GET "$CENTRAL_API/payments/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 27. Update Payment
```bash
curl -X PUT "$CENTRAL_API/payments/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "payment_status": "success",
    "transaction_id": "txn_123456"
  }'
```

### Admins Management

#### 28. List Admins
```bash
curl -X GET "$CENTRAL_API/admins" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 29. Create Admin
```bash
curl -X POST "$CENTRAL_API/admins" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "New Admin",
    "email": "newadmin@example.com",
    "password": "password123"
  }'
```

#### 30. Activate Admin
```bash
curl -X POST "$CENTRAL_API/admins/1/activate" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

---

## Tenant API Tests

**Note:** All Tenant API requests must be made to tenant domain.

### Authentication

#### 31. Tenant Admin Login
```bash
curl -X POST "$TENANT_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@tenant.com",
    "password": "password"
  }'
```

#### 32. Get Current Tenant Admin
```bash
curl -X GET "$TENANT_API/auth/me" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 33. Tenant Logout
```bash
curl -X POST "$TENANT_API/auth/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 34. Tenant Refresh Token
```bash
curl -X POST "$TENANT_API/auth/refresh" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Dashboard

#### 35. Get Tenant Dashboard
```bash
curl -X GET "$TENANT_API/dashboard" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 36. Get Tenant Stats
```bash
curl -X GET "$TENANT_API/dashboard/stats" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Products Management

#### 37. List Products
```bash
curl -X GET "$TENANT_API/products" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 38. Get Product
```bash
curl -X GET "$TENANT_API/products/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 39. Create Product
```bash
curl -X POST "$TENANT_API/products" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "New Product",
    "price": 99.99,
    "sale_price": 79.99,
    "description": "Product description"
  }'
```

#### 40. Update Product
```bash
curl -X PUT "$TENANT_API/products/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Updated Product Name",
    "price": 109.99
  }'
```

#### 41. Activate Product
```bash
curl -X POST "$TENANT_API/products/1/activate" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 42. Deactivate Product
```bash
curl -X POST "$TENANT_API/products/1/deactivate" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Orders Management

#### 43. List Orders
```bash
curl -X GET "$TENANT_API/orders" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 44. Get Order
```bash
curl -X GET "$TENANT_API/orders/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 45. Update Order Status
```bash
curl -X POST "$TENANT_API/orders/1/update-status" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "complete",
    "payment_status": "success"
  }'
```

#### 46. Cancel Order
```bash
curl -X POST "$TENANT_API/orders/1/cancel" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Customers Management

#### 47. List Customers
```bash
curl -X GET "$TENANT_API/customers" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 48. Get Customer
```bash
curl -X GET "$TENANT_API/customers/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 49. Get Customer Orders
```bash
curl -X GET "$TENANT_API/customers/1/orders" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 50. Get Customer Statistics
```bash
curl -X GET "$TENANT_API/customers/1/stats" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Categories Management

#### 51. List Categories
```bash
curl -X GET "$TENANT_API/categories" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 52. Get Category
```bash
curl -X GET "$TENANT_API/categories/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 53. Create Category
```bash
curl -X POST "$TENANT_API/categories" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Electronics",
    "slug": "electronics",
    "description": "Electronic products",
    "image_id": 1,
    "status_id": 1
  }'
```

#### 54. Update Category
```bash
curl -X PUT "$TENANT_API/categories/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Updated Category Name",
    "description": "Updated description"
  }'
```

#### 55. Delete Category
```bash
curl -X DELETE "$TENANT_API/categories/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 56. Get Category Products
```bash
curl -X GET "$TENANT_API/categories/1/products" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

---

## Testing Error Scenarios

### Test Unauthenticated Request
```bash
curl -X GET "$CENTRAL_API/dashboard" \
  -H "Accept: application/json"
# Expected: 401 Unauthorized
```

### Test Invalid Token
```bash
curl -X GET "$CENTRAL_API/dashboard" \
  -H "Authorization: Bearer invalid-token" \
  -H "Accept: application/json"
# Expected: 401 Unauthorized
```

### Test Validation Error
```bash
curl -X POST "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{}'
# Expected: 422 Validation Error
```

### Test Rate Limiting
```bash
# Run multiple requests quickly
for i in {1..100}; do
  curl -X GET "$CENTRAL_API/dashboard" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Accept: application/json"
done
# Expected: 429 Too Many Requests after limit
```

### Test Tenant Context Isolation
```bash
# Try accessing tenant API from central domain
curl -X GET "http://asaas.local/api/tenant/v1/products" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
# Expected: 403 Forbidden
```

---

## Script for Complete Test Flow

Create `test-api.sh`:

```bash
#!/bin/bash

CENTRAL_API="http://asaas.local/api/central/v1"
TENANT_API="http://tenant1.asaas.local/api/tenant/v1"

echo "Testing Central API Login..."
TOKEN=$(curl -s -X POST "$CENTRAL_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.data.token')

if [ "$TOKEN" != "null" ] && [ -n "$TOKEN" ]; then
  echo "✅ Login successful. Token: ${TOKEN:0:20}..."
  export TOKEN
else
  echo "❌ Login failed"
  exit 1
fi

echo "Testing Dashboard..."
curl -s -X GET "$CENTRAL_API/dashboard" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq '.success'

echo "Testing Tenants List..."
curl -s -X GET "$CENTRAL_API/tenants" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq '.success'

echo "✅ All tests completed!"
```

Make it executable:
```bash
chmod +x test-api.sh
./test-api.sh
```

---

**Last Updated:** $(date)

