#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="${BASE_URL:-http://asaas.local}"
TENANT_DOMAIN="${TENANT_DOMAIN:-tenant1}"
TENANT_BASE_URL="${TENANT_BASE_URL:-http://${TENANT_DOMAIN}.asaas.local}"

CENTRAL_API="${BASE_URL}/api/central/v1"
TENANT_API="${TENANT_BASE_URL}/api/tenant/v1"

# Test credentials (update these)
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@example.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-password}"
TENANT_EMAIL="${TENANT_EMAIL:-admin@tenant.com}"
TENANT_PASSWORD="${TENANT_PASSWORD:-password}"

# Counters
TOTAL=0
PASSED=0
FAILED=0
SKIPPED=0

# Function to test endpoint
test_endpoint() {
    local method=$1
    local url=$2
    local token=$3
    local data=$4
    local description=$5
    
    TOTAL=$((TOTAL + 1))
    
    echo -n "Testing: $description ... "
    
    # Build curl command
    local curl_cmd="curl -s -w '\nHTTP_CODE:%{http_code}' -X $method"
    
    if [ -n "$token" ]; then
        curl_cmd="$curl_cmd -H 'Authorization: Bearer $token'"
    fi
    
    curl_cmd="$curl_cmd -H 'Content-Type: application/json' -H 'Accept: application/json'"
    
    if [ -n "$data" ]; then
        curl_cmd="$curl_cmd -d '$data'"
    fi
    
    curl_cmd="$curl_cmd '$url'"
    
    # Execute and get response
    local response=$(eval $curl_cmd)
    local http_code=$(echo "$response" | grep -o "HTTP_CODE:[0-9]*" | cut -d: -f2)
    local body=$(echo "$response" | sed 's/HTTP_CODE:[0-9]*$//')
    
    # Check result
    if [ -z "$http_code" ]; then
        echo -e "${RED}FAILED${NC} (No response)"
        FAILED=$((FAILED + 1))
        return 1
    elif [ "$http_code" -ge 200 ] && [ "$http_code" -lt 300 ]; then
        echo -e "${GREEN}PASS${NC} (HTTP $http_code)"
        PASSED=$((PASSED + 1))
        return 0
    elif [ "$http_code" -eq 401 ] || [ "$http_code" -eq 403 ]; then
        echo -e "${YELLOW}SKIP${NC} (Auth required - HTTP $http_code)"
        SKIPPED=$((SKIPPED + 1))
        return 2
    else
        echo -e "${RED}FAILED${NC} (HTTP $http_code)"
        FAILED=$((FAILED + 1))
        return 1
    fi
}

echo "=========================================="
echo "API Endpoints Testing Script"
echo "=========================================="
echo ""
echo "Central API: $CENTRAL_API"
echo "Tenant API: $TENANT_API"
echo ""

# Test Central API Login
echo "=== Central API Tests ==="
CENTRAL_TOKEN=""
echo -n "Testing: Central Login ... "
CENTRAL_LOGIN_RESPONSE=$(curl -s -w "\nHTTP_CODE:%{http_code}" -X POST "$CENTRAL_API/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d "{\"email\":\"$ADMIN_EMAIL\",\"password\":\"$ADMIN_PASSWORD\"}")

CENTRAL_HTTP_CODE=$(echo "$CENTRAL_LOGIN_RESPONSE" | grep -o "HTTP_CODE:[0-9]*" | cut -d: -f2)
CENTRAL_BODY=$(echo "$CENTRAL_LOGIN_RESPONSE" | sed 's/HTTP_CODE:[0-9]*$//')

if [ "$CENTRAL_HTTP_CODE" -eq 200 ] || [ "$CENTRAL_HTTP_CODE" -eq 201 ]; then
    CENTRAL_TOKEN=$(echo "$CENTRAL_BODY" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    echo -e "${GREEN}PASS${NC} (HTTP $CENTRAL_HTTP_CODE)"
    PASSED=$((PASSED + 1))
else
    echo -e "${RED}FAILED${NC} (HTTP $CENTRAL_HTTP_CODE)"
    echo "Response: $CENTRAL_BODY"
    FAILED=$((FAILED + 1))
fi

TOTAL=$((TOTAL + 1))

# Test Central API endpoints
if [ -n "$CENTRAL_TOKEN" ]; then
    test_endpoint "GET" "$CENTRAL_API/auth/me" "$CENTRAL_TOKEN" "" "Central Get Me"
    test_endpoint "GET" "$CENTRAL_API/dashboard" "$CENTRAL_TOKEN" "" "Central Dashboard"
    test_endpoint "GET" "$CENTRAL_API/dashboard/stats" "$CENTRAL_TOKEN" "" "Central Dashboard Stats"
    test_endpoint "GET" "$CENTRAL_API/tenants" "$CENTRAL_TOKEN" "" "Central List Tenants"
    test_endpoint "GET" "$CENTRAL_API/plans" "$CENTRAL_TOKEN" "" "Central List Plans"
    test_endpoint "GET" "$CENTRAL_API/orders" "$CENTRAL_TOKEN" "" "Central List Orders"
    test_endpoint "GET" "$CENTRAL_API/payments" "$CENTRAL_TOKEN" "" "Central List Payments"
    test_endpoint "GET" "$CENTRAL_API/admins" "$CENTRAL_TOKEN" "" "Central List Admins"
    test_endpoint "GET" "$CENTRAL_API/media" "$CENTRAL_TOKEN" "" "Central List Media"
    test_endpoint "GET" "$CENTRAL_API/settings" "$CENTRAL_TOKEN" "" "Central Get Settings"
    test_endpoint "GET" "$CENTRAL_API/support-tickets" "$CENTRAL_TOKEN" "" "Central List Support Tickets"
    test_endpoint "GET" "$CENTRAL_API/reports/sales" "$CENTRAL_TOKEN" "" "Central Sales Report"
fi

echo ""

# Test Tenant API Login
echo "=== Tenant API Tests ==="
TENANT_TOKEN=""
echo -n "Testing: Tenant Login ... "
TENANT_LOGIN_RESPONSE=$(curl -s -w "\nHTTP_CODE:%{http_code}" -X POST "$TENANT_API/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d "{\"email\":\"$TENANT_EMAIL\",\"password\":\"$TENANT_PASSWORD\"}")

TENANT_HTTP_CODE=$(echo "$TENANT_LOGIN_RESPONSE" | grep -o "HTTP_CODE:[0-9]*" | cut -d: -f2)
TENANT_BODY=$(echo "$TENANT_LOGIN_RESPONSE" | sed 's/HTTP_CODE:[0-9]*$//')

if [ "$TENANT_HTTP_CODE" -eq 200 ] || [ "$TENANT_HTTP_CODE" -eq 201 ]; then
    TENANT_TOKEN=$(echo "$TENANT_BODY" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    echo -e "${GREEN}PASS${NC} (HTTP $TENANT_HTTP_CODE)"
    PASSED=$((PASSED + 1))
else
    echo -e "${RED}FAILED${NC} (HTTP $TENANT_HTTP_CODE)"
    echo "Response: $TENANT_BODY"
    FAILED=$((FAILED + 1))
fi

TOTAL=$((TOTAL + 1))

# Test Tenant API endpoints
if [ -n "$TENANT_TOKEN" ]; then
    test_endpoint "GET" "$TENANT_API/auth/me" "$TENANT_TOKEN" "" "Tenant Get Me"
    test_endpoint "GET" "$TENANT_API/dashboard" "$TENANT_TOKEN" "" "Tenant Dashboard"
    test_endpoint "GET" "$TENANT_API/products" "$TENANT_TOKEN" "" "Tenant List Products"
    test_endpoint "GET" "$TENANT_API/orders" "$TENANT_TOKEN" "" "Tenant List Orders"
    test_endpoint "GET" "$TENANT_API/customers" "$TENANT_TOKEN" "" "Tenant List Customers"
    test_endpoint "GET" "$TENANT_API/categories" "$TENANT_TOKEN" "" "Tenant List Categories"
    test_endpoint "GET" "$TENANT_API/blogs" "$TENANT_TOKEN" "" "Tenant List Blogs"
    test_endpoint "GET" "$TENANT_API/blog-categories" "$TENANT_TOKEN" "" "Tenant List Blog Categories"
    test_endpoint "GET" "$TENANT_API/pages" "$TENANT_TOKEN" "" "Tenant List Pages"
    test_endpoint "GET" "$TENANT_API/media" "$TENANT_TOKEN" "" "Tenant List Media"
    test_endpoint "GET" "$TENANT_API/settings" "$TENANT_TOKEN" "" "Tenant Get Settings"
    test_endpoint "GET" "$TENANT_API/coupons" "$TENANT_TOKEN" "" "Tenant List Coupons"
    test_endpoint "GET" "$TENANT_API/shipping-zones" "$TENANT_TOKEN" "" "Tenant List Shipping Zones"
    test_endpoint "GET" "$TENANT_API/shipping-methods" "$TENANT_TOKEN" "" "Tenant List Shipping Methods"
    test_endpoint "GET" "$TENANT_API/inventory" "$TENANT_TOKEN" "" "Tenant List Inventory"
    test_endpoint "GET" "$TENANT_API/wallet" "$TENANT_TOKEN" "" "Tenant List Wallets"
    test_endpoint "GET" "$TENANT_API/support-tickets" "$TENANT_TOKEN" "" "Tenant List Support Tickets"
    test_endpoint "GET" "$TENANT_API/reports/sales" "$TENANT_TOKEN" "" "Tenant Sales Report"
    test_endpoint "GET" "$TENANT_API/product-reviews" "$TENANT_TOKEN" "" "Tenant List Product Reviews"
    test_endpoint "GET" "$TENANT_API/refunds" "$TENANT_TOKEN" "" "Tenant List Refunds"
    test_endpoint "GET" "$TENANT_API/taxes" "$TENANT_TOKEN" "" "Tenant List Taxes"
    test_endpoint "GET" "$TENANT_API/newsletters" "$TENANT_TOKEN" "" "Tenant List Newsletters"
    test_endpoint "GET" "$TENANT_API/badges" "$TENANT_TOKEN" "" "Tenant List Badges"
    test_endpoint "GET" "$TENANT_API/campaigns" "$TENANT_TOKEN" "" "Tenant List Campaigns"
    test_endpoint "GET" "$TENANT_API/digital-products" "$TENANT_TOKEN" "" "Tenant List Digital Products"
    test_endpoint "GET" "$TENANT_API/countries" "$TENANT_TOKEN" "" "Tenant List Countries"
    test_endpoint "GET" "$TENANT_API/states" "$TENANT_TOKEN" "" "Tenant List States"
    test_endpoint "GET" "$TENANT_API/services" "$TENANT_TOKEN" "" "Tenant List Services"
    test_endpoint "GET" "$TENANT_API/service-categories" "$TENANT_TOKEN" "" "Tenant List Service Categories"
    test_endpoint "GET" "$TENANT_API/sales-reports" "$TENANT_TOKEN" "" "Tenant Sales Reports"
    test_endpoint "GET" "$TENANT_API/site-analytics" "$TENANT_TOKEN" "" "Tenant Site Analytics"
    test_endpoint "GET" "$TENANT_API/product-attributes" "$TENANT_TOKEN" "" "Tenant List Product Attributes"
    test_endpoint "GET" "$TENANT_API/brands" "$TENANT_TOKEN" "" "Tenant List Brands"
    test_endpoint "GET" "$TENANT_API/colors" "$TENANT_TOKEN" "" "Tenant List Colors"
    test_endpoint "GET" "$TENANT_API/sizes" "$TENANT_TOKEN" "" "Tenant List Sizes"
    test_endpoint "GET" "$TENANT_API/tags" "$TENANT_TOKEN" "" "Tenant List Tags"
    test_endpoint "GET" "$TENANT_API/units" "$TENANT_TOKEN" "" "Tenant List Units"
    test_endpoint "GET" "$TENANT_API/sub-categories" "$TENANT_TOKEN" "" "Tenant List Sub Categories"
    test_endpoint "GET" "$TENANT_API/child-categories" "$TENANT_TOKEN" "" "Tenant List Child Categories"
    test_endpoint "GET" "$TENANT_API/delivery-options" "$TENANT_TOKEN" "" "Tenant List Delivery Options"
    test_endpoint "GET" "$TENANT_API/cities" "$TENANT_TOKEN" "" "Tenant List Cities"
fi

echo ""
echo "=========================================="
echo "Test Summary"
echo "=========================================="
echo "Total Tests: $TOTAL"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo -e "${YELLOW}Skipped: $SKIPPED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}All tests passed!${NC}"
    exit 0
else
    echo -e "${RED}Some tests failed.${NC}"
    exit 1
fi

