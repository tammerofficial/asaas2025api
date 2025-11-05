#!/bin/bash

# API Performance Testing Script
# Tests all Vue.js Dashboard V1 API endpoints

BASE_URL="https://asaas.local/admin-home/v1/api"
RESULTS_FILE="api-performance-results.json"
TIMEOUT=10

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Initialize results array
echo "{" > "$RESULTS_FILE"
echo "  \"test_date\": \"$(date +%Y-%m-%d\ %H:%M:%S)\"," >> "$RESULTS_FILE"
echo "  \"base_url\": \"$BASE_URL\"," >> "$RESULTS_FILE"
echo "  \"endpoints\": [" >> "$RESULTS_FILE"

# Function to test endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local name=$3
    local data=$4
    
    local full_url="${BASE_URL}${endpoint}"
    local response_time=0
    local status_code=0
    local success=false
    
    echo -e "${BLUE}Testing: ${method} ${endpoint}${NC}"
    
    # Get CSRF token first (if needed)
    local csrf_token=$(curl -s -c /tmp/cookies.txt "${BASE_URL%/*}/v1" | grep -oP 'csrf-token" content="\K[^"]+' || echo "")
    
    # Build curl command
    local curl_cmd="curl -s -w '%{http_code}|%{time_total}' -X ${method}"
    
    # Add headers
    curl_cmd="${curl_cmd} -H 'Accept: application/json'"
    curl_cmd="${curl_cmd} -H 'Content-Type: application/json'"
    
    if [ ! -z "$csrf_token" ]; then
        curl_cmd="${curl_cmd} -H 'X-CSRF-TOKEN: ${csrf_token}'"
    fi
    
    # Add cookies if available
    if [ -f /tmp/cookies.txt ]; then
        curl_cmd="${curl_cmd} -b /tmp/cookies.txt"
    fi
    
    # Add data for POST/PUT
    if [ "$method" = "POST" ] || [ "$method" = "PUT" ]; then
        if [ -z "$data" ]; then
            data="{}"
        fi
        curl_cmd="${curl_cmd} -d '${data}'"
    fi
    
    curl_cmd="${curl_cmd} --max-time ${TIMEOUT} '${full_url}'"
    
    # Execute and capture result
    local result=$(eval $curl_cmd)
    local http_code=$(echo "$result" | tail -1 | cut -d'|' -f1)
    local time_total=$(echo "$result" | tail -1 | cut -d'|' -f2)
    local response_body=$(echo "$result" | sed '$d')
    
    # Check if successful
    if [ "$http_code" -ge 200 ] && [ "$http_code" -lt 300 ]; then
        success=true
        echo -e "${GREEN}✓ Success (${http_code}) - ${time_total}s${NC}"
    elif [ "$http_code" -eq 401 ] || [ "$http_code" -eq 403 ]; then
        echo -e "${YELLOW}⚠ Auth required (${http_code}) - ${time_total}s${NC}"
    else
        echo -e "${RED}✗ Failed (${http_code}) - ${time_total}s${NC}"
    fi
    
    # Add to results
    echo "    {" >> "$RESULTS_FILE"
    echo "      \"method\": \"${method}\"," >> "$RESULTS_FILE"
    echo "      \"endpoint\": \"${endpoint}\"," >> "$RESULTS_FILE"
    echo "      \"name\": \"${name}\"," >> "$RESULTS_FILE"
    echo "      \"status_code\": ${http_code}," >> "$RESULTS_FILE"
    echo "      \"response_time\": ${time_total}," >> "$RESULTS_FILE"
    echo "      \"success\": ${success}," >> "$RESULTS_FILE"
    echo "      \"url\": \"${full_url}\"" >> "$RESULTS_FILE"
    echo "    }," >> "$RESULTS_FILE"
    
    return 0
}

# Test all GET endpoints
echo -e "\n${BLUE}=== Testing GET Endpoints ===${NC}\n"

# Dashboard
test_endpoint "GET" "/dashboard/stats" "Dashboard Stats"
test_endpoint "GET" "/dashboard/recent-orders" "Recent Orders"
test_endpoint "GET" "/dashboard/chart-data" "Chart Data"

# Tenants
test_endpoint "GET" "/tenants" "List Tenants"
test_endpoint "GET" "/tenants/1" "Get Tenant"

# Packages
test_endpoint "GET" "/packages" "List Packages"
test_endpoint "GET" "/packages/1" "Get Package"

# Orders
test_endpoint "GET" "/orders" "List Orders"
test_endpoint "GET" "/orders/1" "Get Order"

# Payments
test_endpoint "GET" "/payments" "List Payments"
test_endpoint "GET" "/payments/1" "Get Payment"

# Admins
test_endpoint "GET" "/admins" "List Admins"
test_endpoint "GET" "/admins/1" "Get Admin"

# Blog
test_endpoint "GET" "/blogs" "List Blogs"
test_endpoint "GET" "/blogs/1" "Get Blog"
test_endpoint "GET" "/blog/categories" "Blog Categories"
test_endpoint "GET" "/blog/tags" "Blog Tags"
test_endpoint "GET" "/blog/comments" "Blog Comments"

# Pages
test_endpoint "GET" "/pages" "List Pages"
test_endpoint "GET" "/pages/1" "Get Page"

# Coupons
test_endpoint "GET" "/coupons" "List Coupons"
test_endpoint "GET" "/coupons/1" "Get Coupon"

# Subscriptions
test_endpoint "GET" "/subscriptions/subscribers" "Subscribers"
test_endpoint "GET" "/subscriptions/stores" "Stores"
test_endpoint "GET" "/subscriptions/payment-histories" "Payment Histories"
test_endpoint "GET" "/subscriptions/custom-domains" "Custom Domains"

# Support
test_endpoint "GET" "/support/tickets" "List Tickets"
test_endpoint "GET" "/support/tickets/1" "Get Ticket"
test_endpoint "GET" "/support/departments" "Support Departments"

# Users
test_endpoint "GET" "/users" "List Users"
test_endpoint "GET" "/users/roles" "User Roles"
test_endpoint "GET" "/users/permissions" "User Permissions"
test_endpoint "GET" "/users/activity-logs" "Activity Logs"

# Appearances
test_endpoint "GET" "/appearances/themes" "Themes"
test_endpoint "GET" "/appearances/menus" "Menus"
test_endpoint "GET" "/appearances/widgets" "Widgets"

# Settings
test_endpoint "GET" "/settings" "Get Settings"

# System
test_endpoint "GET" "/system/languages" "Languages"

# Media
test_endpoint "GET" "/media" "Media Library"

# Test POST endpoints (with minimal data)
echo -e "\n${BLUE}=== Testing POST Endpoints ===${NC}\n"

test_endpoint "POST" "/tenants" "Create Tenant" '{"name":"test","email":"test@test.com"}'
test_endpoint "POST" "/blogs" "Create Blog" '{"title":"Test","slug":"test"}'
test_endpoint "POST" "/pages" "Create Page" '{"title":"Test","slug":"test"}'
test_endpoint "POST" "/packages" "Create Package" '{"title":"Test"}'
test_endpoint "POST" "/coupons" "Create Coupon" '{"code":"TEST","discount":10}'
test_endpoint "POST" "/admins" "Create Admin" '{"name":"Test","email":"test@test.com"}'
test_endpoint "POST" "/support/tickets" "Create Ticket" '{"subject":"Test"}'

# Test PUT endpoints
echo -e "\n${BLUE}=== Testing PUT Endpoints ===${NC}\n"

test_endpoint "PUT" "/tenants/1" "Update Tenant" '{"name":"Updated"}'
test_endpoint "PUT" "/blogs/1" "Update Blog" '{"title":"Updated"}'
test_endpoint "PUT" "/pages/1" "Update Page" '{"title":"Updated"}'
test_endpoint "PUT" "/packages/1" "Update Package" '{"title":"Updated"}'
test_endpoint "PUT" "/coupons/1" "Update Coupon" '{"code":"UPDATED"}'
test_endpoint "PUT" "/admins/1" "Update Admin" '{"name":"Updated"}'
test_endpoint "PUT" "/support/tickets/1" "Update Ticket" '{"status":"resolved"}'
test_endpoint "PUT" "/settings" "Update Settings" '{"site_title":"Updated"}'

# Test DELETE endpoints
echo -e "\n${BLUE}=== Testing DELETE Endpoints ===${NC}\n"

test_endpoint "DELETE" "/tenants/999" "Delete Tenant"
test_endpoint "DELETE" "/blogs/999" "Delete Blog"
test_endpoint "DELETE" "/pages/999" "Delete Page"
test_endpoint "DELETE" "/packages/999" "Delete Package"
test_endpoint "DELETE" "/coupons/999" "Delete Coupon"
test_endpoint "DELETE" "/admins/999" "Delete Admin"
test_endpoint "DELETE" "/support/tickets/999" "Delete Ticket"

# Close JSON array
sed -i '' '$ s/,$//' "$RESULTS_FILE"
echo "  ]" >> "$RESULTS_FILE"
echo "}" >> "$RESULTS_FILE"

# Generate summary
echo -e "\n${GREEN}=== Performance Summary ===${NC}\n"

# Count results
total=$(grep -c '"endpoint"' "$RESULTS_FILE" || echo "0")
success=$(grep -c '"success": true' "$RESULTS_FILE" || echo "0")
failed=$(grep -c '"success": false' "$RESULTS_FILE" || echo "0")

echo "Total Endpoints Tested: $total"
echo "Successful: $success"
echo "Failed: $failed"

# Calculate average response time
avg_time=$(awk -F'"response_time": ' '/"response_time":/ {sum+=$2; count++} END {if(count>0) print sum/count; else print "0"}' "$RESULTS_FILE")
echo "Average Response Time: ${avg_time}s"

# Find slowest endpoint
slowest=$(awk -F'"response_time": ' '/"response_time":/ {if($2 > max || max=="") {max=$2; line=$0}} END {print line}' "$RESULTS_FILE" | grep -oP '"endpoint": "\K[^"]+' || echo "N/A")
slowest_time=$(awk -F'"response_time": ' '/"response_time":/ {if($2 > max || max=="") max=$2} END {print max}' "$RESULTS_FILE" || echo "0")
echo "Slowest Endpoint: ${slowest} (${slowest_time}s)"

# Find fastest endpoint
fastest=$(awk -F'"response_time": ' '/"response_time":/ {if($2 < min || min=="") {min=$2; line=$0}} END {print line}' "$RESULTS_FILE" | grep -oP '"endpoint": "\K[^"]+' || echo "N/A")
fastest_time=$(awk -F'"response_time": ' '/"response_time":/ {if($2 < min || min=="") min=$2} END {print min}' "$RESULTS_FILE" || echo "0")
echo "Fastest Endpoint: ${fastest} (${fastest_time}s)"

echo -e "\n${GREEN}Results saved to: ${RESULTS_FILE}${NC}\n"

# Cleanup
rm -f /tmp/cookies.txt

