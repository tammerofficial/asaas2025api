#!/bin/bash

# API Speed Testing Script - Vue.js Dashboard V1
# Tests all API endpoints and measures response time

BASE_URL="https://asaas.local/admin-home/v1/api"
RESULTS_FILE="api-performance-results-$(date +%Y%m%d-%H%M%S).json"
TIMEOUT=15

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# Initialize results
results=()
total=0
success=0
failed=0
auth_required=0
response_times=()

echo -e "${CYAN}=== API Performance Testing ===${NC}\n"
echo "Base URL: $BASE_URL"
echo "Results will be saved to: $RESULTS_FILE"
echo ""

# Function to test endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local name=$3
    local data=$4
    
    local full_url="${BASE_URL}${endpoint}"
    total=$((total + 1))
    
    echo -ne "${BLUE}Testing [${total}]: ${method} ${endpoint}${NC} ... "
    
    # Measure time using curl
    local start_time=$(date +%s.%N)
    
    # Build curl command
    local curl_opts=(
        -s -w "\nHTTP_CODE:%{http_code}|TIME:%{time_total}|%{time_namelookup}|%{time_connect}|%{time_starttransfer}"
        -X "${method}"
        -H "Accept: application/json"
        -H "Content-Type: application/json"
        --max-time "${TIMEOUT}"
        --connect-timeout 5
        -L  # Follow redirects
        --silent
        --show-error
    )
    
    # Add data for POST/PUT
    if [ "$method" = "POST" ] || [ "$method" = "PUT" ]; then
        if [ -z "$data" ]; then
            data="{}"
        fi
        curl_opts+=(-d "${data}")
    fi
    
    # Skip SSL verification for local testing
    curl_opts+=(-k)
    
    # Execute curl
    local response=$(curl "${curl_opts[@]}" "${full_url}" 2>&1)
    local end_time=$(date +%s.%N)
    
    # Parse response - curl outputs timing info on last line
    local last_line=$(echo "$response" | tail -1)
    
    # Extract HTTP code
    local http_code=$(echo "$last_line" | sed -n 's/.*HTTP_CODE:\([0-9]*\).*/\1/p' || echo "000")
    if [ -z "$http_code" ] || [ "$http_code" = "000" ]; then
        # Try alternative format
        http_code=$(echo "$last_line" | cut -d'|' -f1 2>/dev/null | sed 's/[^0-9]//g' || echo "000")
    fi
    
    # Extract timing info
    local time_total=$(echo "$last_line" | sed -n 's/.*TIME:\([0-9.]*\).*/\1/p' || echo "0")
    if [ -z "$time_total" ] || [ "$time_total" = "0" ]; then
        time_total=$(echo "$last_line" | cut -d'|' -f2 2>/dev/null || echo "0")
    fi
    
    local time_namelookup=$(echo "$last_line" | cut -d'|' -f3 2>/dev/null || echo "0")
    local time_connect=$(echo "$last_line" | cut -d'|' -f4 2>/dev/null || echo "0")
    local time_starttransfer=$(echo "$last_line" | cut -d'|' -f5 2>/dev/null || echo "0")
    
    # Remove timing info from response body
    local response_body=$(echo "$response" | sed '$d' | grep -v "HTTP_CODE" | grep -v "TIME:")
    
    # Calculate actual response time
    local actual_time=$(echo "$end_time - $start_time" | bc)
    
    # Status assessment
    local status="failed"
    local color=$RED
    local icon="✗"
    
    if [ -z "$http_code" ] || [ "$http_code" = "000" ]; then
        status="timeout"
        color=$RED
        icon="⏱"
        failed=$((failed + 1))
    elif [ "$http_code" -ge 200 ] && [ "$http_code" -lt 300 ]; then
        status="success"
        color=$GREEN
        icon="✓"
        success=$((success + 1))
    elif [ "$http_code" -eq 401 ] || [ "$http_code" -eq 403 ]; then
        status="auth_required"
        color=$YELLOW
        icon="⚠"
        auth_required=$((auth_required + 1))
    else
        status="failed"
        color=$RED
        icon="✗"
        failed=$((failed + 1))
    fi
    
    # Store response time
    if [ ! -z "$time_total" ] && [ "$time_total" != "0.000" ]; then
        response_times+=("$time_total")
    fi
    
    # Display result
    echo -e "${color}${icon} ${http_code:-"N/A"} - ${time_total:-"0.000"}s${NC}"
    
    # Store result
    results+=("{\"method\":\"${method}\",\"endpoint\":\"${endpoint}\",\"name\":\"${name}\",\"status_code\":${http_code:-0},\"response_time\":${time_total:-0},\"time_namelookup\":${time_namelookup:-0},\"time_connect\":${time_connect:-0},\"time_starttransfer\":${time_starttransfer:-0},\"status\":\"${status}\",\"url\":\"${full_url}\"}")
}

# Test all endpoints
echo -e "${CYAN}=== GET Endpoints ===${NC}\n"

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

# Test POST endpoints
echo -e "\n${CYAN}=== POST Endpoints ===${NC}\n"

test_endpoint "POST" "/tenants" "Create Tenant" '{"name":"test","email":"test@test.com"}'
test_endpoint "POST" "/blogs" "Create Blog" '{"title":"Test","slug":"test"}'
test_endpoint "POST" "/pages" "Create Page" '{"title":"Test","slug":"test"}'
test_endpoint "POST" "/packages" "Create Package" '{"title":"Test"}'
test_endpoint "POST" "/coupons" "Create Coupon" '{"code":"TEST","discount":10}'
test_endpoint "POST" "/admins" "Create Admin" '{"name":"Test","email":"test@test.com"}'
test_endpoint "POST" "/support/tickets" "Create Ticket" '{"subject":"Test"}'

# Test PUT endpoints
echo -e "\n${CYAN}=== PUT Endpoints ===${NC}\n"

test_endpoint "PUT" "/tenants/1" "Update Tenant" '{"name":"Updated"}'
test_endpoint "PUT" "/blogs/1" "Update Blog" '{"title":"Updated"}'
test_endpoint "PUT" "/pages/1" "Update Page" '{"title":"Updated"}'
test_endpoint "PUT" "/packages/1" "Update Package" '{"title":"Updated"}'
test_endpoint "PUT" "/coupons/1" "Update Coupon" '{"code":"UPDATED"}'
test_endpoint "PUT" "/admins/1" "Update Admin" '{"name":"Updated"}'
test_endpoint "PUT" "/support/tickets/1" "Update Ticket" '{"status":"resolved"}'
test_endpoint "PUT" "/settings" "Update Settings" '{"site_title":"Updated"}'

# Test DELETE endpoints
echo -e "\n${CYAN}=== DELETE Endpoints ===${NC}\n"

test_endpoint "DELETE" "/tenants/999" "Delete Tenant"
test_endpoint "DELETE" "/blogs/999" "Delete Blog"
test_endpoint "DELETE" "/pages/999" "Delete Page"
test_endpoint "DELETE" "/packages/999" "Delete Package"
test_endpoint "DELETE" "/coupons/999" "Delete Coupon"
test_endpoint "DELETE" "/admins/999" "Delete Admin"
test_endpoint "DELETE" "/support/tickets/999" "Delete Ticket"

# Calculate statistics
echo -e "\n${CYAN}=== Calculating Statistics ===${NC}\n"

# Calculate average, min, max
avg_time=0
min_time=999999
max_time=0
slowest_endpoint=""
fastest_endpoint=""

if [ ${#response_times[@]} -gt 0 ]; then
    sum=0
    count=0
    for time in "${response_times[@]}"; do
        sum=$(echo "$sum + $time" | bc)
        count=$((count + 1))
        
        # Compare as floats
        if (( $(echo "$time < $min_time" | bc -l) )); then
            min_time=$time
        fi
        if (( $(echo "$time > $max_time" | bc -l) )); then
            max_time=$time
        fi
    done
    avg_time=$(echo "scale=3; $sum / $count" | bc)
fi

# Find slowest and fastest endpoints
for result in "${results[@]}"; do
    endpoint=$(echo "$result" | sed -n 's/.*"endpoint":"\([^"]*\)".*/\1/p')
    time=$(echo "$result" | sed -n 's/.*"response_time":\([0-9.]*\).*/\1/p')
    name=$(echo "$result" | sed -n 's/.*"name":"\([^"]*\)".*/\1/p')
    
    if [ ! -z "$time" ] && [ "$time" != "0" ]; then
        if (( $(echo "$time >= $max_time" | bc -l) )); then
            slowest_endpoint="$endpoint ($name) - ${time}s"
        fi
        if (( $(echo "$time <= $min_time" | bc -l) )); then
            fastest_endpoint="$endpoint ($name) - ${time}s"
        fi
    fi
done

# Generate JSON report
echo "{" > "$RESULTS_FILE"
echo "  \"test_date\": \"$(date +%Y-%m-%d\ %H:%M:%S)\"," >> "$RESULTS_FILE"
echo "  \"base_url\": \"$BASE_URL\"," >> "$RESULTS_FILE"
echo "  \"statistics\": {" >> "$RESULTS_FILE"
echo "    \"total\": $total," >> "$RESULTS_FILE"
echo "    \"success\": $success," >> "$RESULTS_FILE"
echo "    \"failed\": $failed," >> "$RESULTS_FILE"
echo "    \"auth_required\": $auth_required," >> "$RESULTS_FILE"
echo "    \"average_response_time\": $avg_time," >> "$RESULTS_FILE"
echo "    \"min_response_time\": $min_time," >> "$RESULTS_FILE"
echo "    \"max_response_time\": $max_time" >> "$RESULTS_FILE"
echo "  }," >> "$RESULTS_FILE"
echo "  \"endpoints\": [" >> "$RESULTS_FILE"

# Add endpoints
for i in "${!results[@]}"; do
    echo "    ${results[$i]}" >> "$RESULTS_FILE"
    if [ $i -lt $((${#results[@]} - 1)) ]; then
        echo "," >> "$RESULTS_FILE"
    fi
done

echo "  ]" >> "$RESULTS_FILE"
echo "}" >> "$RESULTS_FILE"

# Display summary
echo -e "${GREEN}=== Performance Summary ===${NC}\n"
echo "Total Endpoints Tested: $total"
echo "Successful: ${GREEN}$success${NC}"
echo "Failed: ${RED}$failed${NC}"
echo "Auth Required: ${YELLOW}$auth_required${NC}"
echo ""
echo "Average Response Time: ${CYAN}${avg_time}s${NC}"
echo "Fastest Response Time: ${GREEN}${min_time}s${NC}"
echo "Slowest Response Time: ${RED}${max_time}s${NC}"
echo ""

if [ ! -z "$fastest_endpoint" ]; then
    echo "Fastest Endpoint: ${GREEN}$fastest_endpoint${NC}"
fi
if [ ! -z "$slowest_endpoint" ]; then
    echo "Slowest Endpoint: ${RED}$slowest_endpoint${NC}"
fi

echo -e "\n${GREEN}Results saved to: $RESULTS_FILE${NC}\n"

