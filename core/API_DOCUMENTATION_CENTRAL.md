# Central API Documentation

## Base URL
```
http://asaas.local/api/central/v1
```

## Authentication

### Login
**POST** `/auth/login`

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
      "name": "Admin Name",
      "email": "admin@example.com",
      "username": "admin",
      "roles": ["admin"]
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

---

### Get Current User (Me)
**GET** `/auth/me`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Admin retrieved successfully",
  "data": {
    "id": 1,
    "name": "Admin Name",
    "email": "admin@example.com",
    "username": "admin",
    "roles": ["admin"],
    "permissions": ["users.view", "users.create"]
  }
}
```

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/auth/me \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Logout
**POST** `/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/auth/logout \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Refresh Token
**POST** `/auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "token": "2|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/auth/refresh \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Dashboard

### Get Dashboard Statistics
**GET** `/dashboard`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Dashboard statistics retrieved successfully",
  "data": {
    "total_tenants": 10,
    "active_tenants": 8,
    "total_users": 150,
    "total_orders": 45,
    "total_revenue": 50000.00,
    "pending_orders": 5,
    "completed_orders": 40,
    "monthly_revenue": 5000.00
  }
}
```

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/dashboard \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Detailed Statistics
**GET** `/dashboard/stats`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/dashboard/stats \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Recent Orders
**GET** `/dashboard/recent-orders`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/dashboard/recent-orders \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Chart Data
**GET** `/dashboard/chart-data`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/dashboard/chart-data \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Tenants Management

### List Tenants
**GET** `/tenants`

**Query Parameters:**
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 20)

**cURL Example:**
```bash
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Create Tenant
**POST** `/tenants`

**Request Body:**
```json
{
  "user_id": 1,
  "domain": "tenant1",
  "name": "Tenant Name",
  "expire_date": "2025-12-31"
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/tenants \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": 1,
    "domain": "tenant1",
    "name": "Tenant Name"
  }'
```

---

### Get Tenant
**GET** `/tenants/{id}`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/tenants/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Update Tenant
**PUT** `/tenants/{id}`

**Request Body:**
```json
{
  "name": "Updated Tenant Name",
  "domain": "updated-tenant",
  "expire_date": "2026-12-31"
}
```

**cURL Example:**
```bash
curl -X PUT http://asaas.local/api/central/v1/tenants/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Updated Tenant Name",
    "domain": "updated-tenant"
  }'
```

---

### Delete Tenant
**DELETE** `/tenants/{id}`

**cURL Example:**
```bash
curl -X DELETE http://asaas.local/api/central/v1/tenants/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Activate Tenant
**POST** `/tenants/{id}/activate`

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/tenants/1/activate \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Deactivate Tenant
**POST** `/tenants/{id}/deactivate`

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/tenants/1/deactivate \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Price Plans Management

### List Price Plans
**GET** `/plans`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/plans \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Create Price Plan
**POST** `/plans`

**Request Body:**
```json
{
  "title": "Basic Plan",
  "type": 0,
  "status": 1,
  "price": 29.99,
  "features": "Feature list",
  "free_trial": 7,
  "package_badge": "Popular",
  "package_description": "Plan description"
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/plans \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Basic Plan",
    "type": 0,
    "status": 1,
    "price": 29.99
  }'
```

---

### Get Price Plan
**GET** `/plans/{id}`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/plans/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Update Price Plan
**PUT** `/plans/{id}`

**cURL Example:**
```bash
curl -X PUT http://asaas.local/api/central/v1/plans/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Updated Plan",
    "price": 39.99
  }'
```

---

### Delete Price Plan
**DELETE** `/plans/{id}`

**cURL Example:**
```bash
curl -X DELETE http://asaas.local/api/central/v1/plans/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Orders Management

### List Orders
**GET** `/orders`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/orders \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Order
**GET** `/orders/{id}`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/orders/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Order Payment Logs
**GET** `/orders/{id}/payment-logs`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/orders/1/payment-logs \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Payments Management

### List Payment Logs
**GET** `/payments`

**Query Parameters:**
- `payment_status` (optional): Filter by payment status (pending, complete, failed)
- `tenant_id` (optional): Filter by tenant ID
- `from_date` (optional): Filter from date (Y-m-d)
- `to_date` (optional): Filter to date (Y-m-d)
- `page` (optional): Page number

**cURL Example:**
```bash
curl -X GET "http://asaas.local/api/central/v1/payments?payment_status=complete" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Get Payment Log
**GET** `/payments/{id}`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/payments/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Update Payment Log
**PUT** `/payments/{id}`

**Request Body:**
```json
{
  "payment_status": "complete",
  "transaction_id": "TXN123456"
}
```

**cURL Example:**
```bash
curl -X PUT http://asaas.local/api/central/v1/payments/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "payment_status": "complete",
    "transaction_id": "TXN123456"
  }'
```

---

## Admin Users Management

### List Admins
**GET** `/admins`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/admins \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Create Admin
**POST** `/admins`

**Request Body:**
```json
{
  "name": "Admin Name",
  "email": "admin@example.com",
  "password": "password123",
  "username": "admin_user",
  "roles": [1, 2]
}
```

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/admins \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Admin Name",
    "email": "admin@example.com",
    "password": "password123",
    "username": "admin_user"
  }'
```

---

### Get Admin
**GET** `/admins/{id}`

**cURL Example:**
```bash
curl -X GET http://asaas.local/api/central/v1/admins/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Update Admin
**PUT** `/admins/{id}`

**cURL Example:**
```bash
curl -X PUT http://asaas.local/api/central/v1/admins/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Updated Admin Name"
  }'
```

---

### Delete Admin
**DELETE** `/admins/{id}`

**cURL Example:**
```bash
curl -X DELETE http://asaas.local/api/central/v1/admins/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Activate Admin
**POST** `/admins/{id}/activate`

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/admins/1/activate \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

### Deactivate Admin
**POST** `/admins/{id}/deactivate`

**cURL Example:**
```bash
curl -X POST http://asaas.local/api/central/v1/admins/1/deactivate \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Error Responses

All error responses follow this format:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error message for field"]
  }
}
```

### Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

