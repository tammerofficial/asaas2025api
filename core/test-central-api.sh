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

echo -e "${BLUE}=== Central API Testing Script ===${NC}\n"

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
    echo -e "${YELLOW}Response:${NC}"
    echo "$LOGIN_RESPONSE" | jq '.'
    exit 1
fi

echo -e "${GREEN}✅ Login successful!${NC}\n"
echo -e "${BLUE}Token: ${TOKEN:0:50}...${NC}\n"

# Step 2: Get Current Admin (Me)
echo -e "${YELLOW}Step 2: Get Current Admin...${NC}"
curl -s -X GET "${API_BASE}/auth/me" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 3: Get Dashboard Statistics
echo -e "${YELLOW}Step 3: Get Dashboard Statistics...${NC}"
curl -s -X GET "${API_BASE}/dashboard" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 4: List Tenants
echo -e "${YELLOW}Step 4: List All Tenants...${NC}"
TENANTS_RESPONSE=$(curl -s -X GET "${API_BASE}/tenants?page=1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "$TENANTS_RESPONSE" | jq '.'

# Extract tenant count
TENANT_COUNT=$(echo "$TENANTS_RESPONSE" | jq -r '.meta.total // .data | length // 0')
echo -e "${GREEN}✅ Found ${TENANT_COUNT} tenant(s)${NC}\n"

# Step 5: Get Detailed Statistics
echo -e "${YELLOW}Step 5: Get Detailed Statistics...${NC}"
curl -s -X GET "${API_BASE}/dashboard/stats" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 6: Get Recent Orders
echo -e "${YELLOW}Step 6: Get Recent Orders...${NC}"
curl -s -X GET "${API_BASE}/dashboard/recent-orders" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 7: Get Chart Data
echo -e "${YELLOW}Step 7: Get Chart Data...${NC}"
curl -s -X GET "${API_BASE}/dashboard/chart-data" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 8: List Payment Logs
echo -e "${YELLOW}Step 8: List Payment Logs...${NC}"
curl -s -X GET "${API_BASE}/payments?page=1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 9: List Orders
echo -e "${YELLOW}Step 9: List Orders...${NC}"
curl -s -X GET "${API_BASE}/orders?page=1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

# Step 10: List Price Plans
echo -e "${YELLOW}Step 10: List Price Plans...${NC}"
curl -s -X GET "${API_BASE}/plans?page=1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq '.'
echo -e "\n"

echo -e "${GREEN}=== Testing Complete ===${NC}"

