#!/bin/bash

# Fast API Token Generator (using IP instead of domain)
# This avoids DNS lookup delay

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://127.0.0.1"  # Use IP directly - faster!
API_PREFIX="/api"

# Default credentials (can be overridden via environment variables)
ADMIN_EMAIL="${ADMIN_EMAIL:-alalawi310@gmail.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-12345678}"

# CURL options (allow insecure SSL for local development)
CURL_OPTIONS="-k -s"

echo -e "${YELLOW}🚀 Fast API Token Generator (using IP)${NC}"
echo ""

# Function to get Central Admin token
get_central_token() {
    echo -e "${YELLOW}📝 Getting Central Admin token...${NC}"
    
    RESPONSE=$(curl $CURL_OPTIONS -X POST \
        "${BASE_URL}${API_PREFIX}/central/v1/auth/login" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d "{
            \"email\": \"${ADMIN_EMAIL}\",
            \"password\": \"${ADMIN_PASSWORD}\"
        }")
    
    if echo "$RESPONSE" | grep -q '"success":true'; then
        TOKEN=$(echo "$RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
        echo -e "${GREEN}✅ Central Token obtained!${NC}"
        echo -e "${GREEN}Token: ${TOKEN}${NC}"
        echo ""
        echo "$TOKEN" > /tmp/central_token.txt
        return 0
    else
        echo -e "${RED}❌ Central Login failed!${NC}"
        echo "Response: $RESPONSE"
        return 1
    fi
}

# Function to get Tenant Admin token
get_tenant_token() {
    TENANT_DOMAIN="${TENANT_DOMAIN:-tenant1}"
    echo -e "${YELLOW}📝 Getting Tenant Admin token...${NC}"
    echo "Tenant: $TENANT_DOMAIN"
    
    RESPONSE=$(curl $CURL_OPTIONS -X POST \
        "${BASE_URL}${API_PREFIX}/tenant/v1/auth/login" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -H "X-Tenant: ${TENANT_DOMAIN}" \
        -d "{
            \"email\": \"${ADMIN_EMAIL}\",
            \"password\": \"${ADMIN_PASSWORD}\"
        }")
    
    if echo "$RESPONSE" | grep -q '"success":true'; then
        TOKEN=$(echo "$RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
        echo -e "${GREEN}✅ Tenant Token obtained!${NC}"
        echo -e "${GREEN}Token: ${TOKEN}${NC}"
        echo ""
        echo "$TOKEN" > /tmp/tenant_token.txt
        return 0
    else
        echo -e "${RED}❌ Tenant Login failed!${NC}"
        echo "Response: $RESPONSE"
        return 1
    fi
}

# Main execution
get_central_token

# Uncomment to get tenant token
# get_tenant_token

echo ""
echo -e "${YELLOW}📋 Usage in Postman:${NC}"
echo "1. Copy the token above"
echo "2. In Postman, set Authorization header:"
echo "   Bearer <token>"
echo ""
echo -e "${YELLOW}💡 Tip: Use ${BASE_URL} in Postman for faster requests (no DNS lookup)${NC}"
echo ""

