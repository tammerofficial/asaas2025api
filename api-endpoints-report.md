# API Endpoints Testing Report
Generated: Mon Nov  3 19:18:00 +03 2025

## Summary
- Total Tests: 23
- Successful: 0
- Failed: 0
- Warnings: 23

## Test Results

### Central API Endpoints


#### Central API - Detailed Statistics

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/stats
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Login

- **Method**: POST
- **URL**: https://asaas.local/api/central/v1/auth/login
- **HTTP Code**: 422
- **Content-Type**: application/json
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 422, but JSON OK)

**Response**:
```json
{"success":false,"message":"Validation failed","errors":{"email":["\u0627\u0644\u0628\u0631\u064a\u062f \u0627\u0644\u0625\u0644\u0643\u062a\u0631\u0648\u0646\u064a \u063a\u064a\u0631 \u0645\u0648\u062c\u0648\u062f"]}}
```

**Issues**:
- ✅ Response is valid JSON with correct Content-Type


#### Central API - Recent Orders

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/recent-orders
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Get Current Admin (Me)

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/auth/me
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Chart Data

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/chart-data
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Logout

- **Method**: POST
- **URL**: https://asaas.local/api/central/v1/auth/logout
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Tenants

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/tenants?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Re-login

- **Method**: POST
- **URL**: https://asaas.local/api/central/v1/auth/login
- **HTTP Code**: 422
- **Content-Type**: application/json
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 422, but JSON OK)

**Response**:
```json
{"success":false,"message":"Validation failed","errors":{"email":["\u0627\u0644\u0628\u0631\u064a\u062f \u0627\u0644\u0625\u0644\u0643\u062a\u0631\u0648\u0646\u064a \u063a\u064a\u0631 \u0645\u0648\u062c\u0648\u062f"]}}
```

**Issues**:
- ✅ Response is valid JSON with correct Content-Type


#### Central API - List Price Plans

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/plans?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Refresh Token

- **Method**: POST
- **URL**: https://asaas.local/api/central/v1/auth/refresh
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Orders

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/orders?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Dashboard Statistics

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Payment Logs

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/payments?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Detailed Statistics

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/stats
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Admins

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/admins?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


### Tenant API Endpoints


#### Central API - Recent Orders

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/recent-orders
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Login

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/login
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - Chart Data

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/dashboard/chart-data
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Get Current Admin (Me)

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/me
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Tenants

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/tenants?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Logout

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/logout
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Price Plans

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/plans?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Re-login

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/login
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Orders

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/orders?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Refresh Token

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/refresh
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Payment Logs

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/payments?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Dashboard Statistics

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Central API - List Admins

- **Method**: GET
- **URL**: https://asaas.local/api/central/v1/admins?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


### Tenant API Endpoints


#### Tenant API - Detailed Statistics

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/stats
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Login

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/login
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Recent Orders

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/recent-orders
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Get Current Admin (Me)

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/me
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Chart Data

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/chart-data
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Logout

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/logout
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Products

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/products?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Re-login

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/login
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Orders

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/orders?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Refresh Token

- **Method**: POST
- **URL**: https://tenant1.asaas.local/api/tenant/v1/auth/refresh
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Customers

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/customers?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Dashboard Statistics

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Categories

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/categories?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Detailed Statistics

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/stats
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Recent Orders

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/recent-orders
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - Chart Data

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/dashboard/chart-data
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Products

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/products?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Orders

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/orders?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Customers

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/customers?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'


#### Tenant API - List Categories

- **Method**: GET
- **URL**: https://tenant1.asaas.local/api/tenant/v1/categories?page=1
- **HTTP Code**: 000
- **Content-Type**: 
- **Is Valid JSON**: true
- **Status**: ❌ FAILED (HTTP 000, but JSON OK)

**Response**:
```json

```

**Issues**:
- ❌ Content-Type is not 'application/json'

