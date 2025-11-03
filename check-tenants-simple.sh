#!/bin/bash

# Simple script to check tenants via Central API
# Usage: ./check-tenants-simple.sh [admin_email] [admin_password]

BASE_URL="${BASE_URL:-https://asaas.local}"
API_BASE="${BASE_URL}/api/central/v1"

ADMIN_EMAIL="${1:-admin@example.com}"
ADMIN_PASSWORD="${2:-password}"

echo "=== Central API - Check Tenants ==="
echo "API Base: ${API_BASE}"
echo ""

# Login
echo "[1] Logging in..."
LOGIN=$(curl -s -k -X POST "${API_BASE}/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"${ADMIN_EMAIL}\",\"password\":\"${ADMIN_PASSWORD}\"}")

TOKEN=$(echo "$LOGIN" | grep -o '"token":"[^"]*' | sed 's/"token":"//')

if [ -z "$TOKEN" ]; then
    echo "❌ Login failed!"
    echo "$LOGIN"
    exit 1
fi

echo "✅ Login successful!"
echo ""

# Get Tenants
echo "[2] Fetching tenants..."
TENANTS=$(curl -s -k -X GET "${API_BASE}/tenants?page=1&per_page=100" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "$TENANTS"

