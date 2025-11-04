<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenant Cache Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration ensures complete cache isolation between tenants
    | preventing any data leaks between different tenant databases.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Prefix Strategy
    |--------------------------------------------------------------------------
    |
    | Each tenant gets a unique cache prefix to ensure isolation:
    | - Central: 'cache:central:'
    | - Tenant: 'cache:tenant_{tenant_id}:'
    |
    */
    'prefix_strategy' => [
        'central' => 'central',
        'tenant' => 'tenant_{tenant_id}',
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Database Allocation
    |--------------------------------------------------------------------------
    |
    | Allocate separate Redis databases for different contexts:
    | - Database 0: Central Application
    | - Database 1-14: Available for Tenants (auto-assigned)
    | - Database 15: Queue & Jobs
    |
    */
    'redis_databases' => [
        'central' => 0,
        'tenant_start' => 1,
        'tenant_end' => 14,
        'queue' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (Time To Live)
    |--------------------------------------------------------------------------
    |
    | Default cache expiration times in seconds
    |
    */
    'ttl' => [
        'default' => 3600, // 1 hour
        'views' => 86400, // 24 hours
        'queries' => 1800, // 30 minutes
        'settings' => 43200, // 12 hours
        'translations' => 604800, // 7 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Tags
    |--------------------------------------------------------------------------
    |
    | Enable cache tagging for easier cache invalidation
    | Note: Only works with Redis and Memcached drivers
    |
    */
    'enable_tags' => true,

    /*
    |--------------------------------------------------------------------------
    | Tenant Cache Tags
    |--------------------------------------------------------------------------
    |
    | Predefined tags for common tenant resources
    |
    */
    'tags' => [
        'products' => 'tenant:{tenant_id}:products',
        'orders' => 'tenant:{tenant_id}:orders',
        'customers' => 'tenant:{tenant_id}:customers',
        'settings' => 'tenant:{tenant_id}:settings',
        'users' => 'tenant:{tenant_id}:users',
        'categories' => 'tenant:{tenant_id}:categories',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Clear Cache on Tenant Switch
    |--------------------------------------------------------------------------
    |
    | Automatically clear cached tenant context when switching tenants
    | This prevents memory leaks in Octane
    |
    */
    'auto_clear_on_switch' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Warming
    |--------------------------------------------------------------------------
    |
    | Pre-cache frequently accessed data for better performance
    |
    */
    'warming' => [
        'enabled' => true,
        'keys' => [
            'settings',
            'permissions',
            'roles',
            'menus',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Monitoring
    |--------------------------------------------------------------------------
    |
    | Enable cache hit/miss monitoring for optimization
    |
    */
    'monitoring' => [
        'enabled' => env('CACHE_MONITORING', false),
        'log_channel' => 'cache',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Isolation Tests
    |--------------------------------------------------------------------------
    |
    | Run these tests to ensure cache isolation
    |
    */
    'isolation_tests' => [
        'enabled' => env('APP_ENV') !== 'production',
        'test_keys' => [
            'test_isolation_key',
            'test_tenant_data',
            'test_cache_tags',
        ],
    ],

];

