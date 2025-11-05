<?php

/**
 * API Performance Testing Script
 * Tests all Vue.js Dashboard V1 API endpoints and measures response time
 */

require __DIR__ . '/core/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Configuration
$baseUrl = 'https://asaas.local/admin-home/v1/api';
$resultsFile = 'api-performance-results.json';
$timeout = 10;

// Colors for CLI output
$colors = [
    'green' => "\033[0;32m",
    'red' => "\033[0;31m",
    'yellow' => "\033[1;33m",
    'blue' => "\033[0;34m",
    'cyan' => "\033[0;36m",
    'reset' => "\033[0m",
];

$results = [
    'test_date' => date('Y-m-d H:i:s'),
    'base_url' => $baseUrl,
    'endpoints' => [],
];

$stats = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'auth_required' => 0,
    'response_times' => [],
];

/**
 * Test an endpoint
 */
function testEndpoint($method, $endpoint, $name, $data = null, $baseUrl, &$results, &$stats, $timeout, $colors) {
    $fullUrl = $baseUrl . $endpoint;
    
    echo $colors['blue'] . "Testing: {$method} {$endpoint}" . $colors['reset'] . "\n";
    
    $startTime = microtime(true);
    
    try {
        $client = Http::timeout($timeout);
        
        // Add authentication if available
        // Note: You may need to login first to get session/token
        // For now, we'll test without auth (may get 401/403)
        
        switch ($method) {
            case 'GET':
                $response = $client->get($fullUrl);
                break;
            case 'POST':
                $response = $client->post($fullUrl, $data ?? []);
                break;
            case 'PUT':
                $response = $client->put($fullUrl, $data ?? []);
                break;
            case 'DELETE':
                $response = $client->delete($fullUrl);
                break;
            default:
                $response = $client->get($fullUrl);
        }
        
        $endTime = microtime(true);
        $responseTime = round($endTime - $startTime, 3);
        
        $statusCode = $response->status();
        $success = $statusCode >= 200 && $statusCode < 300;
        $authRequired = $statusCode == 401 || $statusCode == 403;
        
        if ($success) {
            echo $colors['green'] . "✓ Success ({$statusCode}) - {$responseTime}s" . $colors['reset'] . "\n";
            $stats['success']++;
        } elseif ($authRequired) {
            echo $colors['yellow'] . "⚠ Auth required ({$statusCode}) - {$responseTime}s" . $colors['reset'] . "\n";
            $stats['auth_required']++;
        } else {
            echo $colors['red'] . "✗ Failed ({$statusCode}) - {$responseTime}s" . $colors['reset'] . "\n";
            $stats['failed']++;
        }
        
        $stats['response_times'][] = $responseTime;
        
        $results[] = [
            'method' => $method,
            'endpoint' => $endpoint,
            'name' => $name,
            'status_code' => $statusCode,
            'response_time' => $responseTime,
            'success' => $success,
            'auth_required' => $authRequired,
            'url' => $fullUrl,
        ];
        
    } catch (\Exception $e) {
        $endTime = microtime(true);
        $responseTime = round($endTime - $startTime, 3);
        
        echo $colors['red'] . "✗ Error: " . $e->getMessage() . " - {$responseTime}s" . $colors['reset'] . "\n";
        
        $stats['failed']++;
        $stats['response_times'][] = $responseTime;
        
        $results[] = [
            'method' => $method,
            'endpoint' => $endpoint,
            'name' => $name,
            'status_code' => 0,
            'response_time' => $responseTime,
            'success' => false,
            'error' => $e->getMessage(),
            'url' => $fullUrl,
        ];
    }
    
    $stats['total']++;
}

// Test all GET endpoints
echo "\n" . $colors['cyan'] . "=== Testing GET Endpoints ===" . $colors['reset'] . "\n\n";

// Dashboard
testEndpoint('GET', '/dashboard/stats', 'Dashboard Stats', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/dashboard/recent-orders', 'Recent Orders', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/dashboard/chart-data', 'Chart Data', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Tenants
testEndpoint('GET', '/tenants', 'List Tenants', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/tenants/1', 'Get Tenant', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Packages
testEndpoint('GET', '/packages', 'List Packages', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/packages/1', 'Get Package', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Orders
testEndpoint('GET', '/orders', 'List Orders', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/orders/1', 'Get Order', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Payments
testEndpoint('GET', '/payments', 'List Payments', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/payments/1', 'Get Payment', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Admins
testEndpoint('GET', '/admins', 'List Admins', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/admins/1', 'Get Admin', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Blog
testEndpoint('GET', '/blogs', 'List Blogs', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/blogs/1', 'Get Blog', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/blog/categories', 'Blog Categories', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/blog/tags', 'Blog Tags', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/blog/comments', 'Blog Comments', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Pages
testEndpoint('GET', '/pages', 'List Pages', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/pages/1', 'Get Page', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Coupons
testEndpoint('GET', '/coupons', 'List Coupons', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/coupons/1', 'Get Coupon', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Subscriptions
testEndpoint('GET', '/subscriptions/subscribers', 'Subscribers', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/subscriptions/stores', 'Stores', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/subscriptions/payment-histories', 'Payment Histories', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/subscriptions/custom-domains', 'Custom Domains', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Support
testEndpoint('GET', '/support/tickets', 'List Tickets', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/support/tickets/1', 'Get Ticket', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/support/departments', 'Support Departments', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Users
testEndpoint('GET', '/users', 'List Users', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/users/roles', 'User Roles', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/users/permissions', 'User Permissions', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/users/activity-logs', 'Activity Logs', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Appearances
testEndpoint('GET', '/appearances/themes', 'Themes', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/appearances/menus', 'Menus', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('GET', '/appearances/widgets', 'Widgets', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Settings
testEndpoint('GET', '/settings', 'Get Settings', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// System
testEndpoint('GET', '/system/languages', 'Languages', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Media
testEndpoint('GET', '/media', 'Media Library', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Test POST endpoints
echo "\n" . $colors['cyan'] . "=== Testing POST Endpoints ===" . $colors['reset'] . "\n\n";

testEndpoint('POST', '/tenants', 'Create Tenant', ['name' => 'test', 'email' => 'test@test.com'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/blogs', 'Create Blog', ['title' => 'Test', 'slug' => 'test'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/pages', 'Create Page', ['title' => 'Test', 'slug' => 'test'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/packages', 'Create Package', ['title' => 'Test'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/coupons', 'Create Coupon', ['code' => 'TEST', 'discount' => 10], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/admins', 'Create Admin', ['name' => 'Test', 'email' => 'test@test.com'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('POST', '/support/tickets', 'Create Ticket', ['subject' => 'Test'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Test PUT endpoints
echo "\n" . $colors['cyan'] . "=== Testing PUT Endpoints ===" . $colors['reset'] . "\n\n";

testEndpoint('PUT', '/tenants/1', 'Update Tenant', ['name' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/blogs/1', 'Update Blog', ['title' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/pages/1', 'Update Page', ['title' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/packages/1', 'Update Package', ['title' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/coupons/1', 'Update Coupon', ['code' => 'UPDATED'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/admins/1', 'Update Admin', ['name' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/support/tickets/1', 'Update Ticket', ['status' => 'resolved'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('PUT', '/settings', 'Update Settings', ['site_title' => 'Updated'], $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Test DELETE endpoints
echo "\n" . $colors['cyan'] . "=== Testing DELETE Endpoints ===" . $colors['reset'] . "\n\n";

testEndpoint('DELETE', '/tenants/999', 'Delete Tenant', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/blogs/999', 'Delete Blog', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/pages/999', 'Delete Page', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/packages/999', 'Delete Package', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/coupons/999', 'Delete Coupon', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/admins/999', 'Delete Admin', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);
testEndpoint('DELETE', '/support/tickets/999', 'Delete Ticket', null, $baseUrl, $results['endpoints'], $stats, $timeout, $colors);

// Calculate statistics
$avgTime = count($stats['response_times']) > 0 ? round(array_sum($stats['response_times']) / count($stats['response_times']), 3) : 0;
$maxTime = count($stats['response_times']) > 0 ? max($stats['response_times']) : 0;
$minTime = count($stats['response_times']) > 0 ? min($stats['response_times']) : 0;

// Find slowest and fastest endpoints
$slowestEndpoint = null;
$fastestEndpoint = null;
$slowestTime = 0;
$fastestTime = PHP_FLOAT_MAX;

foreach ($results['endpoints'] as $endpoint) {
    if ($endpoint['response_time'] > $slowestTime) {
        $slowestTime = $endpoint['response_time'];
        $slowestEndpoint = $endpoint;
    }
    if ($endpoint['response_time'] < $fastestTime && $endpoint['response_time'] > 0) {
        $fastestTime = $endpoint['response_time'];
        $fastestEndpoint = $endpoint;
    }
}

// Add statistics to results
$results['statistics'] = [
    'total' => $stats['total'],
    'success' => $stats['success'],
    'failed' => $stats['failed'],
    'auth_required' => $stats['auth_required'],
    'average_response_time' => $avgTime,
    'min_response_time' => $minTime,
    'max_response_time' => $maxTime,
    'slowest_endpoint' => $slowestEndpoint ? [
        'endpoint' => $slowestEndpoint['endpoint'],
        'response_time' => $slowestEndpoint['response_time'],
        'name' => $slowestEndpoint['name'],
    ] : null,
    'fastest_endpoint' => $fastestEndpoint ? [
        'endpoint' => $fastestEndpoint['endpoint'],
        'response_time' => $fastestEndpoint['response_time'],
        'name' => $fastestEndpoint['name'],
    ] : null,
];

// Save results
file_put_contents($resultsFile, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

// Display summary
echo "\n" . $colors['green'] . "=== Performance Summary ===" . $colors['reset'] . "\n\n";
echo "Total Endpoints Tested: " . $stats['total'] . "\n";
echo "Successful: " . $colors['green'] . $stats['success'] . $colors['reset'] . "\n";
echo "Failed: " . $colors['red'] . $stats['failed'] . $colors['reset'] . "\n";
echo "Auth Required: " . $colors['yellow'] . $stats['auth_required'] . $colors['reset'] . "\n";
echo "Average Response Time: " . $colors['cyan'] . $avgTime . "s" . $colors['reset'] . "\n";
echo "Fastest Response Time: " . $colors['green'] . $minTime . "s" . $colors['reset'] . "\n";
echo "Slowest Response Time: " . $colors['red'] . $maxTime . "s" . $colors['reset'] . "\n";

if ($slowestEndpoint) {
    echo "\nSlowest Endpoint: " . $colors['red'] . $slowestEndpoint['endpoint'] . $colors['reset'] . " (" . $slowestEndpoint['response_time'] . "s) - " . $slowestEndpoint['name'] . "\n";
}

if ($fastestEndpoint) {
    echo "Fastest Endpoint: " . $colors['green'] . $fastestEndpoint['endpoint'] . $colors['reset'] . " (" . $fastestEndpoint['response_time'] . "s) - " . $fastestEndpoint['name'] . "\n";
}

echo "\n" . $colors['green'] . "Results saved to: " . $resultsFile . $colors['reset'] . "\n\n";

