#!/bin/bash

# Script to check tenants via Central API
# Usage: ./check-tenants.sh [admin_email] [admin_password]

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
BASE_URL="${BASE_URL:-http://asaas.local}"
API_BASE="${BASE_URL}/api/central/v1"

# Get credentials from arguments or use defaults
ADMIN_EMAIL="${1:-admin@example.com}"
ADMIN_PASSWORD="${2:-password}"

echo -e "${BLUE}=== Central API - Check Tenants ===${NC}\n"
echo -e "Base URL: ${BASE_URL}"
echo -e "API Base: ${API_BASE}\n"

# Step 1: Login
echo -e "${YELLOW}[1] Logging in...${NC}"
LOGIN_RESPONSE=$(curl -s -X POST "${API_BASE}/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"${ADMIN_EMAIL}\",\"password\":\"${ADMIN_PASSWORD}\"}")

# Check if login was successful
if echo "$LOGIN_RESPONSE" | grep -q '"success":true'; then
    TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    
    if [ -z "$TOKEN" ]; then
        TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o 'token":"[^"]*' | cut -d'"' -f2)
    fi
    
    if [ ! -z "$TOKEN" ] && [ "$TOKEN" != "null" ]; then
        echo -e "${GREEN}✅ Login successful!${NC}"
        echo -e "${BLUE}Token: ${TOKEN:0:50}...${NC}\n"
    else
        echo -e "${RED}❌ Failed to extract token${NC}"
        echo "$LOGIN_RESPONSE"
        exit 1
    fi
else
    echo -e "${RED}❌ Login failed!${NC}"
    echo "$LOGIN_RESPONSE"
    exit 1
fi

# Step 2: Get Tenants List
echo -e "${YELLOW}[2] Fetching tenants list...${NC}"
TENANTS_RESPONSE=$(curl -s -X GET "${API_BASE}/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

# Check response
if echo "$TENANTS_RESPONSE" | grep -q '"success":true'; then
    echo -e "${GREEN}✅ Tenants retrieved successfully!${NC}\n"
    
    # Try to parse with jq if available
    if command -v jq &> /dev/null; then
        echo -e "${BLUE}=== Tenants Summary ===${NC}"
        echo "$TENANTS_RESPONSE" | jq -r '.meta.total // .data | length // 0' | xargs -I {} echo "Total Tenants: {}"
        
        echo -e "\n${BLUE}=== Tenants Details ===${NC}"
        echo "$TENANTS_RESPONSE" | jq '.data[] | {id, name, domain: .domain, is_active, user: .user.email, expire_date}' 2>/dev/null || echo "$TENANTS_RESPONSE" | jq '.data[]' 2>/dev/null
        
        # Full response
        echo -e "\n${BLUE}=== Full Response ===${NC}"
        echo "$TENANTS_RESPONSE" | jq '.'
    else
        echo "$TENANTS_RESPONSE"
    fi
else
    echo -e "${RED}❌ Failed to retrieve tenants${NC}"
    echo "$TENANTS_RESPONSE"
    exit 1
fi

echo -e "\n${GREEN}=== Done ===${NC}"

