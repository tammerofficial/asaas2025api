# cURL Examples for Central API

## Quick Test Commands

### 1. Login to Central API

```bash
curl -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

**Save the token from response for next commands**

### 2. Check Current Admin (Me)

```bash
# Replace {TOKEN} with the token from login
curl -X GET http://asaas.local/api/central/v1/auth/me \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

### 3. List All Tenants

```bash
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

### 4. Get Single Tenant Details

```bash
# Replace {ID} with tenant ID
curl -X GET "http://asaas.local/api/central/v1/tenants/{ID}" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

### 5. Get Dashboard Statistics

```bash
curl -X GET http://asaas.local/api/central/v1/dashboard \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

## Using the Test Scripts

### Option 1: Use the comprehensive test script

```bash
cd core
./test-central-api.sh
```

**Note**: Edit the script first to update admin credentials:
```bash
ADMIN_EMAIL="your-admin@email.com"
ADMIN_PASSWORD="your-password"
```

### Option 2: Use the tenants-specific script

```bash
cd core
./check-tenants.sh your-admin@email.com your-password
```

Or with default credentials:
```bash
cd core
./check-tenants.sh
```

## One-Liner to Check Tenants

```bash
# Step 1: Login and save token
TOKEN=$(curl -s -X POST http://asaas.local/api/central/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' | \
  grep -o '"token":"[^"]*' | cut -d'"' -f4)

# Step 2: List tenants
curl -X GET "http://asaas.local/api/central/v1/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
```

## Pretty Print with jq

If you have `jq` installed, add `| jq '.'` at the end:

```bash
curl -X GET "http://asaas.local/api/central/v1/tenants" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json" | jq '.'
```

## Troubleshooting

### If you get 401 Unauthorized:
- Check that your token is valid
- Make sure you're using `Bearer` in Authorization header
- Try logging in again to get a fresh token

### If you get 403 Forbidden:
- Check that your admin user has the required permissions
- Verify you're accessing from the correct domain (central domain)

### If you get Connection Refused:
- Make sure the server is running
- Check that `asaas.local` is in your hosts file
- Verify the base URL is correct

### If response is not JSON:
- Make sure you're sending `Accept: application/json` header
- Check that the API route is correct
- Verify middleware is not blocking the request

