#!/bin/bash

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="${BASE_URL:-http://asaas.local}"
TENANT_DOMAIN="${TENANT_DOMAIN:-tenant1}"
TENANT_BASE_URL="${TENANT_BASE_URL:-http://${TENANT_DOMAIN}.asaas.local}"

CENTRAL_API="${BASE_URL}/api/central/v1"
TENANT_API="${TENANT_BASE_URL}/api/tenant/v1"

# Credentials
ADMIN_EMAIL="${ADMIN_EMAIL:-alalawi310@gmail.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-12345678}"

echo "=========================================="
echo "🔐 Get API Tokens"
echo "=========================================="
echo ""
echo "Email: $ADMIN_EMAIL"
echo "Central API: $CENTRAL_API"
echo "Tenant API: $TENANT_API"
echo ""

# Get Central Token
echo "=== Central API Login ==="
echo -n "Getting Central token... "

CENTRAL_RESPONSE=$(curl -s -X POST "$CENTRAL_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"$ADMIN_EMAIL\",\"password\":\"$ADMIN_PASSWORD\"}")

CENTRAL_HTTP_CODE=$(echo "$CENTRAL_RESPONSE" | grep -o '"success":[^,]*' | cut -d: -f2 | tr -d ' ')

if [ "$CENTRAL_HTTP_CODE" = "true" ]; then
    CENTRAL_TOKEN=$(echo "$CENTRAL_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    if [ -n "$CENTRAL_TOKEN" ]; then
        echo -e "${GREEN}✅ Success${NC}"
        echo -e "${GREEN}Central Token:${NC} $CENTRAL_TOKEN"
        echo ""
        
        # Update Postman Collection
        echo "Updating Postman Collection..."
        python3 << EOF
import json

# Read collection
with open('docs/API_POSTMAN_COLLECTION.json', 'r', encoding='utf-8') as f:
    collection = json.load(f)

# Update central_token variable
for var in collection.get('variable', []):
    if var['key'] == 'central_token':
        var['value'] = '$CENTRAL_TOKEN'
        break
else:
    collection.setdefault('variable', []).append({
        'key': 'central_token',
        'value': '$CENTRAL_TOKEN',
        'type': 'string'
    })

# Save collection
with open('docs/API_POSTMAN_COLLECTION.json', 'w', encoding='utf-8') as f:
    json.dump(collection, f, indent=2, ensure_ascii=False)

print("✅ Central token saved to Postman Collection")
EOF
        
    else
        echo -e "${RED}❌ Failed${NC}"
        echo "Response: $CENTRAL_RESPONSE"
    fi
else
    echo -e "${RED}❌ Failed${NC}"
    echo "Response: $CENTRAL_RESPONSE"
fi

echo ""

# Get Tenant Token
echo "=== Tenant API Login ==="
echo -n "Getting Tenant token... "

TENANT_RESPONSE=$(curl -s -X POST "$TENANT_API/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"$ADMIN_EMAIL\",\"password\":\"$ADMIN_PASSWORD\"}")

TENANT_HTTP_CODE=$(echo "$TENANT_RESPONSE" | grep -o '"success":[^,]*' | cut -d: -f2 | tr -d ' ')

if [ "$TENANT_HTTP_CODE" = "true" ]; then
    TENANT_TOKEN=$(echo "$TENANT_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    if [ -n "$TENANT_TOKEN" ]; then
        echo -e "${GREEN}✅ Success${NC}"
        echo -e "${GREEN}Tenant Token:${NC} $TENANT_TOKEN"
        echo ""
        
        # Update Postman Collection
        echo "Updating Postman Collection..."
        python3 << EOF
import json

# Read collection
with open('docs/API_POSTMAN_COLLECTION.json', 'r', encoding='utf-8') as f:
    collection = json.load(f)

# Update tenant_token variable
for var in collection.get('variable', []):
    if var['key'] == 'tenant_token':
        var['value'] = '$TENANT_TOKEN'
        break
else:
    collection.setdefault('variable', []).append({
        'key': 'tenant_token',
        'value': '$TENANT_TOKEN',
        'type': 'string'
    })

# Save collection
with open('docs/API_POSTMAN_COLLECTION.json', 'w', encoding='utf-8') as f:
    json.dump(collection, f, indent=2, ensure_ascii=False)

print("✅ Tenant token saved to Postman Collection")
EOF
        
    else
        echo -e "${RED}❌ Failed${NC}"
        echo "Response: $TENANT_RESPONSE"
    fi
else
    echo -e "${RED}❌ Failed${NC}"
    echo "Response: $TENANT_RESPONSE"
fi

echo ""
echo "=========================================="
echo "✅ Tokens saved to Postman Collection"
echo "=========================================="
echo ""
echo "You can now use Postman Collection with auto-saved tokens!"
echo ""

# Export tokens for use in shell
if [ -n "$CENTRAL_TOKEN" ]; then
    export CENTRAL_TOKEN
    echo "Central Token exported: $CENTRAL_TOKEN"
fi

if [ -n "$TENANT_TOKEN" ]; then
    export TENANT_TOKEN
    echo "Tenant Token exported: $TENANT_TOKEN"
fi

echo ""
echo "To use tokens in shell:"
echo "  export CENTRAL_TOKEN=\"$CENTRAL_TOKEN\""
echo "  export TENANT_TOKEN=\"$TENANT_TOKEN\""
