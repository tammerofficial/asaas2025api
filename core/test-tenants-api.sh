#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://asaas.local"
API_BASE="${BASE_URL}/api/central/v1"

# Admin credentials (update these)
ADMIN_EMAIL="admin@example.com"
ADMIN_PASSWORD="password"

echo -e "${BLUE}=== Tenants API Testing Script ===${NC}\n"

# Step 1: Login
echo -e "${YELLOW}Step 1: Login...${NC}"
LOGIN_RESPONSE=$(curl -s -X POST "${API_BASE}/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"email\": \"${ADMIN_EMAIL}\",
    \"password\": \"${ADMIN_PASSWORD}\"
  }")

echo "$LOGIN_RESPONSE" | jq '.'

# Extract token
TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token // empty')

if [ -z "$TOKEN" ] || [ "$TOKEN" == "null" ]; then
    echo -e "${RED}❌ Login failed! Please check credentials.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Login successful!${NC}\n"

# Step 2: List Tenants (Full Details)
echo -e "${YELLOW}Step 2: List All Tenants (Full Details)...${NC}"
TENANTS_RESPONSE=$(curl -s -X GET "${API_BASE}/tenants?page=1&per_page=50" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "$TENANTS_RESPONSE" | jq '.'

# Extract tenants data
TENANTS=$(echo "$TENANTS_RESPONSE" | jq -r '.data // []')
TENANT_COUNT=$(echo "$TENANTS_RESPONSE" | jq -r '.meta.total // (.data | length) // 0')

echo -e "\n${GREEN}✅ Found ${TENANT_COUNT} tenant(s)${NC}\n"

# Step 3: Display each tenant details
if [ "$TENANT_COUNT" -gt 0 ]; then
    echo -e "${YELLOW}Step 3: Displaying Tenant Details...${NC}\n"
    
    # Get tenant IDs
    TENANT_IDS=$(echo "$TENANTS" | jq -r '.[].id // empty')
    
    for TENANT_ID in $TENANT_IDS; do
        if [ ! -z "$TENANT_ID" ] && [ "$TENANT_ID" != "null" ]; then
            echo -e "${BLUE}--- Tenant ID: ${TENANT_ID} ---${NC}"
            
            # Get single tenant details
            TENANT_DETAILS=$(curl -s -X GET "${API_BASE}/tenants/${TENANT_ID}" \
              -H "Authorization: Bearer ${TOKEN}" \
              -H "Accept: application/json")
            
            echo "$TENANT_DETAILS" | jq '.data'
            echo ""
        fi
    done
fi

echo -e "${GREEN}=== Tenants Testing Complete ===${NC}"

