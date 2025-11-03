#!/bin/bash

# Comprehensive API Endpoints Testing Script
# Tests all Central and Tenant API endpoints and ensures JSON responses

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
BASE_URL="${BASE_URL:-https://asaas.local}"
CENTRAL_API="${BASE_URL}/api/central/v1"
TENANT_API="${BASE_URL}/api/tenant/v1"

# Credentials
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@example.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-password}"
TENANT_EMAIL="${TENANT_EMAIL:-alalawi310@gmail.com}"
TENANT_PASSWORD="${TENANT_PASSWORD:-11221122}"
TENANT_DOMAIN="${TENANT_DOMAIN:-tenant1.asaas.local}"

REPORT_FILE="api-endpoints-report.md"

TOTAL=0
SUCCESS=0
FAILED=0
WARNINGS=0
CENTRAL_TOKEN=""
TENANT_TOKEN=""

echo -e "${BLUE}=== Comprehensive API Endpoints Testing ===${NC}\n"
echo "Report will be saved to: ${REPORT_FILE}"
echo ""

# Initialize report
cat > "${REPORT_FILE}" << EOF
# API Endpoints Testing Report
Generated: $(date)

## Summary
- Total Tests: 0
- Successful: 0
- Failed: 0
- Warnings: 0

## Test Results

### Central API Endpoints

EOF

test_endpoint() {
    local METHOD=$1
    local URL=$2
    local HEADERS=$3
    local DATA=$4
    local DESCRIPTION=$5
    local SECTION=$6
    
    TOTAL=$((TOTAL + 1))
    
    echo -e "${YELLOW}[${TOTAL}] Testing: ${DESCRIPTION}${NC}"
    echo "  ${METHOD} ${URL}"
    
    # Build curl command
    CURL_CMD="curl -s -k -w '\nHTTP_CODE:%{http_code}\nCONTENT_TYPE:%{content_type}' -X ${METHOD}"
    
    if [ ! -z "$HEADERS" ]; then
        CURL_CMD="${CURL_CMD} ${HEADERS}"
    fi
    
    if [ ! -z "$DATA" ]; then
        CURL_CMD="${CURL_CMD} -d '${DATA}'"
    fi
    
    CURL_CMD="${CURL_CMD} '${URL}'"
    
    RESPONSE=$(eval $CURL_CMD 2>&1)
    HTTP_CODE=$(echo "$RESPONSE" | grep 'HTTP_CODE:' | cut -d':' -f2)
    CONTENT_TYPE=$(echo "$RESPONSE" | grep 'CONTENT_TYPE:' | cut -d':' -f2 | cut -d';' -f1)
    BODY=$(echo "$RESPONSE" | sed '/HTTP_CODE:/d' | sed '/CONTENT_TYPE:/d')
    
    # Check if response is valid JSON
    IS_JSON=false
    if command -v jq &> /dev/null; then
        if echo "$BODY" | jq . > /dev/null 2>&1; then
            IS_JSON=true
        fi
    else
        # Simple JSON check
        if echo "$BODY" | grep -q '^{' || echo "$BODY" | grep -q '^\['; then
            IS_JSON=true
        fi
    fi
    
    # Check Content-Type header
    HAS_JSON_CONTENT_TYPE=false
    if echo "$CONTENT_TYPE" | grep -qi "application/json"; then
        HAS_JSON_CONTENT_TYPE=true
    fi
    
    # Determine status
    STATUS=""
    if [ "$HTTP_CODE" -ge 200 ] && [ "$HTTP_CODE" -lt 300 ]; then
        if [ "$IS_JSON" = true ] && [ "$HAS_JSON_CONTENT_TYPE" = true ]; then
            STATUS="✅ SUCCESS"
            SUCCESS=$((SUCCESS + 1))
        elif [ "$IS_JSON" = true ]; then
            STATUS="⚠️  WARNING - JSON but wrong Content-Type"
            WARNINGS=$((WARNINGS + 1))
        elif [ "$HAS_JSON_CONTENT_TYPE" = true ]; then
            STATUS="⚠️  WARNING - Content-Type OK but not valid JSON"
            WARNINGS=$((WARNINGS + 1))
        else
            STATUS="❌ FAILED - Not JSON"
            FAILED=$((FAILED + 1))
        fi
    elif [ "$HTTP_CODE" -eq 401 ] || [ "$HTTP_CODE" -eq 403 ]; then
        if [ "$IS_JSON" = true ]; then
            STATUS="🔒 AUTH REQUIRED (JSON OK)"
            WARNINGS=$((WARNINGS + 1))
        else
            STATUS="❌ FAILED - Auth error but not JSON"
            FAILED=$((FAILED + 1))
        fi
    elif [ "$HTTP_CODE" -eq 404 ]; then
        if [ "$IS_JSON" = true ]; then
            STATUS="❌ NOT FOUND (JSON OK)"
            WARNINGS=$((WARNINGS + 1))
        else
            STATUS="❌ FAILED - 404 but not JSON"
            FAILED=$((FAILED + 1))
        fi
    else
        if [ "$IS_JSON" = true ]; then
            STATUS="❌ FAILED (HTTP ${HTTP_CODE}, but JSON OK)"
            WARNINGS=$((WARNINGS + 1))
        else
            STATUS="❌ FAILED"
            FAILED=$((FAILED + 1))
        fi
    fi
    
    echo -e "  Status: ${STATUS} (HTTP ${HTTP_CODE})"
    echo -e "  Content-Type: ${CONTENT_TYPE}"
    echo -e "  Is JSON: ${IS_JSON}"
    
    # Extract token if login
    if [[ "$DESCRIPTION" == *"Login"* ]] && [ "$HTTP_CODE" -eq 200 ] && [ "$IS_JSON" = true ]; then
        TOKEN=$(echo "$BODY" | grep -o '"token":"[^"]*' | sed 's/"token":"//' || echo "")
        if [ ! -z "$TOKEN" ] && [ "$TOKEN" != "null" ]; then
            echo -e "  ${GREEN}✅ Token extracted${NC}"
            echo "$TOKEN"
            return
        fi
    fi
    
    # Add to report
    cat >> "${REPORT_FILE}" << EOF

#### ${DESCRIPTION}

- **Method**: ${METHOD}
- **URL**: ${URL}
- **HTTP Code**: ${HTTP_CODE}
- **Content-Type**: ${CONTENT_TYPE}
- **Is Valid JSON**: ${IS_JSON}
- **Status**: ${STATUS}

**Response**:
\`\`\`json
$(echo "$BODY" | head -c 1000)
\`\`\`

**Issues**:
EOF

    if [ "$IS_JSON" = false ]; then
        echo "- ❌ Response is not valid JSON" >> "${REPORT_FILE}"
    fi
    
    if [ "$HAS_JSON_CONTENT_TYPE" = false ]; then
        echo "- ❌ Content-Type is not 'application/json'" >> "${REPORT_FILE}"
    fi
    
    if [ "$IS_JSON" = true ] && [ "$HAS_JSON_CONTENT_TYPE" = true ]; then
        echo "- ✅ Response is valid JSON with correct Content-Type" >> "${REPORT_FILE}"
    fi
    
    echo "" >> "${REPORT_FILE}"
    
    echo ""
}

# Test Central API
echo -e "${BLUE}=== Testing Central API Endpoints ===${NC}\n"

# Login
CENTRAL_TOKEN=$(test_endpoint "POST" "${CENTRAL_API}/auth/login" \
    "-H 'Content-Type: application/json' -H 'Accept: application/json'" \
    "{\"email\":\"${ADMIN_EMAIL}\",\"password\":\"${ADMIN_PASSWORD}\"}" \
    "Central API - Login" "Central")

if [ ! -z "$CENTRAL_TOKEN" ]; then
    echo -e "${GREEN}✅ Central Login successful! Token: ${CENTRAL_TOKEN:0:30}...${NC}\n"
fi

# Protected endpoints
if [ ! -z "$CENTRAL_TOKEN" ]; then
    AUTH_HEADER="-H 'Authorization: Bearer ${CENTRAL_TOKEN}' -H 'Accept: application/json'"
    
    test_endpoint "GET" "${CENTRAL_API}/auth/me" \
        "${AUTH_HEADER}" "" \
        "Central API - Get Current Admin (Me)" "Central"
    
    test_endpoint "POST" "${CENTRAL_API}/auth/logout" \
        "${AUTH_HEADER}" "" \
        "Central API - Logout" "Central"
    
    # Re-login after logout
    CENTRAL_TOKEN=$(test_endpoint "POST" "${CENTRAL_API}/auth/login" \
        "-H 'Content-Type: application/json' -H 'Accept: application/json'" \
        "{\"email\":\"${ADMIN_EMAIL}\",\"password\":\"${ADMIN_PASSWORD}\"}" \
        "Central API - Re-login" "Central")
    
    if [ ! -z "$CENTRAL_TOKEN" ]; then
        AUTH_HEADER="-H 'Authorization: Bearer ${CENTRAL_TOKEN}' -H 'Accept: application/json'"
        
        test_endpoint "POST" "${CENTRAL_API}/auth/refresh" \
            "${AUTH_HEADER}" "" \
            "Central API - Refresh Token" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/dashboard" \
            "${AUTH_HEADER}" "" \
            "Central API - Dashboard Statistics" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/dashboard/stats" \
            "${AUTH_HEADER}" "" \
            "Central API - Detailed Statistics" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/dashboard/recent-orders" \
            "${AUTH_HEADER}" "" \
            "Central API - Recent Orders" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/dashboard/chart-data" \
            "${AUTH_HEADER}" "" \
            "Central API - Chart Data" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/tenants?page=1" \
            "${AUTH_HEADER}" "" \
            "Central API - List Tenants" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/plans?page=1" \
            "${AUTH_HEADER}" "" \
            "Central API - List Price Plans" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/orders?page=1" \
            "${AUTH_HEADER}" "" \
            "Central API - List Orders" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/payments?page=1" \
            "${AUTH_HEADER}" "" \
            "Central API - List Payment Logs" "Central"
        
        test_endpoint "GET" "${CENTRAL_API}/admins?page=1" \
            "${AUTH_HEADER}" "" \
            "Central API - List Admins" "Central"
    fi
fi

# Test Tenant API
echo -e "${BLUE}=== Testing Tenant API Endpoints ===${NC}\n"

TENANT_URL="https://${TENANT_DOMAIN}/api/tenant/v1"

cat >> "${REPORT_FILE}" << EOF

### Tenant API Endpoints

EOF

# Tenant Login
TENANT_TOKEN=$(test_endpoint "POST" "${TENANT_URL}/auth/login" \
    "-H 'Content-Type: application/json' -H 'Accept: application/json'" \
    "{\"email\":\"${TENANT_EMAIL}\",\"password\":\"${TENANT_PASSWORD}\"}" \
    "Tenant API - Login" "Tenant")

if [ ! -z "$TENANT_TOKEN" ]; then
    echo -e "${GREEN}✅ Tenant Login successful! Token: ${TENANT_TOKEN:0:30}...${NC}\n"
    
    AUTH_HEADER="-H 'Authorization: Bearer ${TENANT_TOKEN}' -H 'Accept: application/json'"
    
    test_endpoint "GET" "${TENANT_URL}/auth/me" \
        "${AUTH_HEADER}" "" \
        "Tenant API - Get Current Admin (Me)" "Tenant"
    
    test_endpoint "POST" "${TENANT_URL}/auth/logout" \
        "${AUTH_HEADER}" "" \
        "Tenant API - Logout" "Tenant"
    
    # Re-login
    TENANT_TOKEN=$(test_endpoint "POST" "${TENANT_URL}/auth/login" \
        "-H 'Content-Type: application/json' -H 'Accept: application/json'" \
        "{\"email\":\"${TENANT_EMAIL}\",\"password\":\"${TENANT_PASSWORD}\"}" \
        "Tenant API - Re-login" "Tenant")
    
    if [ ! -z "$TENANT_TOKEN" ]; then
        AUTH_HEADER="-H 'Authorization: Bearer ${TENANT_TOKEN}' -H 'Accept: application/json'"
        
        test_endpoint "POST" "${TENANT_URL}/auth/refresh" \
            "${AUTH_HEADER}" "" \
            "Tenant API - Refresh Token" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/dashboard" \
            "${AUTH_HEADER}" "" \
            "Tenant API - Dashboard Statistics" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/dashboard/stats" \
            "${AUTH_HEADER}" "" \
            "Tenant API - Detailed Statistics" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/dashboard/recent-orders" \
            "${AUTH_HEADER}" "" \
            "Tenant API - Recent Orders" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/dashboard/chart-data" \
            "${AUTH_HEADER}" "" \
            "Tenant API - Chart Data" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/products?page=1" \
            "${AUTH_HEADER}" "" \
            "Tenant API - List Products" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/orders?page=1" \
            "${AUTH_HEADER}" "" \
            "Tenant API - List Orders" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/customers?page=1" \
            "${AUTH_HEADER}" "" \
            "Tenant API - List Customers" "Tenant"
        
        test_endpoint "GET" "${TENANT_URL}/categories?page=1" \
            "${AUTH_HEADER}" "" \
            "Tenant API - List Categories" "Tenant"
    fi
fi

# Update summary
sed -i.bak "s/- Total Tests: 0/- Total Tests: ${TOTAL}/" "${REPORT_FILE}"
sed -i.bak "s/- Successful: 0/- Successful: ${SUCCESS}/" "${REPORT_FILE}"
sed -i.bak "s/- Failed: 0/- Failed: ${FAILED}/" "${REPORT_FILE}"
sed -i.bak "s/- Warnings: 0/- Warnings: ${WARNINGS}/" "${REPORT_FILE}"
rm -f "${REPORT_FILE}.bak"

echo -e "\n${BLUE}=== Summary ===${NC}"
echo -e "Total Tests: ${TOTAL}"
echo -e "${GREEN}Successful: ${SUCCESS}${NC}"
echo -e "${RED}Failed: ${FAILED}${NC}"
echo -e "${YELLOW}Warnings: ${WARNINGS}${NC}"
echo -e "\n${BLUE}Report saved to: ${REPORT_FILE}${NC}"
