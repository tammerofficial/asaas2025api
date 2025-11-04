<?php return array (
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'activitylog' => 
  array (
    'enabled' => true,
    'delete_records_older_than_days' => 365,
    'default_log_name' => 'default',
    'default_auth_driver' => NULL,
    'subject_returns_soft_deleted_models' => false,
    'activity_model' => 'Spatie\\Activitylog\\Models\\Activity',
    'table_name' => 'tenant_activity_log',
    'database_connection' => NULL,
  ),
  'analytics' => 
  array (
    'enabled' => true,
    'prefix' => 'analytics',
    'domain' => NULL,
    'middleware' => 
    array (
      0 => 'web',
    ),
    'exclude' => 
    array (
      0 => '/analytics',
      1 => '/analytics/*',
      2 => '/user-home',
      3 => '/user-home/*',
      4 => '/package-orders',
      5 => '/custom-domain',
      6 => '/landlord/wallet*',
      7 => '/user/*',
      8 => '/request-refund/*',
      9 => '/admin-home',
      10 => '/admin-home/*',
      11 => '/favicon.ico',
      12 => '/shop/order-success',
      13 => '/shop/order-success/*',
      14 => '/shop/order-cancel',
      15 => '/shop/order-cancel/*',
      16 => '/shop/*-ipn',
      17 => '/shop/checkout/shipping-method-data',
      18 => '/shop/assets/*',
      19 => '/assets/*',
      20 => '/order-success/*',
      21 => '/order-cancel/*',
      22 => '/unique-checker',
      23 => '/stripe-ipn',
      24 => '/mollie-ipn',
      25 => '/flutterwave/ipn',
      26 => '/paystack-ipn',
      27 => '/midtrans-ipn',
      28 => '/instamojo-ipn',
      29 => '/paypal-ipn',
      30 => '/marcadopago-ipn',
      31 => '/squareup-ipn',
      32 => '/telescope',
      33 => '/telescope/*',
      34 => '/login/reset-password/*',
      35 => '/token-login',
      36 => '/admin',
      37 => '/apply-coupon',
    ),
    'ignoreRobots' => false,
    'ignoredIPs' => 
    array (
    ),
    'mask' => 
    array (
    ),
    'ignoreMethods' => 
    array (
      0 => 'OPTIONS',
      1 => 'POST',
    ),
    'ignoredColumns' => 
    array (
    ),
    'session' => 
    array (
      'provider' => 'AndreasElia\\Analytics\\RequestSessionProvider',
    ),
  ),
  'app' => 
  array (
    'name' => 'NazMart - Multi-Tenancy eCommerce Sass',
    'env' => 'production',
    'debug' => true,
    'url' => 'https://asaas.local',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'Asia/Dhaka',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:b1pTbW1EQkdPRGtKNTVVa3BDTmhCMnlPM2dKMG1zNDE=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'App\\Providers\\TenantStorageServiceProvider',
      23 => 'Intervention\\Image\\ImageServiceProvider',
      24 => 'Artesaos\\SEOTools\\Providers\\SEOToolsServiceProvider',
      25 => 'Paytabscom\\Laravel_paytabs\\PaypageServiceProvider',
      26 => 'Milon\\Barcode\\BarcodeServiceProvider',
      27 => 'App\\Providers\\AppServiceProvider',
      28 => 'App\\Providers\\AuthServiceProvider',
      29 => 'App\\Providers\\EventServiceProvider',
      30 => 'App\\Providers\\RouteServiceProvider',
      31 => 'App\\Providers\\TenancyServiceProvider',
      32 => 'App\\Providers\\MacroServiceProvider',
      33 => 'App\\Providers\\BladeDirectiveServiceProvider',
      34 => 'Plugins\\PageBuilder\\PageBuilderServiceProvider',
      35 => 'Plugins\\WidgetBuilder\\WidgetBuilderServiceProvider',
      36 => 'Plugins\\MenuBuilder\\MenuBuilderServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'SEOMeta' => 'Artesaos\\SEOTools\\Facades\\SEOMeta',
      'OpenGraph' => 'Artesaos\\SEOTools\\Facades\\OpenGraph',
      'Twitter' => 'Artesaos\\SEOTools\\Facades\\TwitterCard',
      'JsonLd' => 'Artesaos\\SEOTools\\Facades\\JsonLd',
      'JsonLdMulti' => 'Artesaos\\SEOTools\\Facades\\JsonLdMulti',
      'SEO' => 'Artesaos\\SEOTools\\Facades\\SEOTools',
      'Invoice' => 'LaravelDaily\\Invoices\\Facades\\Invoice',
      'DNS1D' => 'Milon\\Barcode\\Facades\\DNS1DFacade',
      'DNS2D' => 'Milon\\Barcode\\Facades\\DNS2DFacade',
      'LaravelPwa' => 'Ladumor\\LaravelPwa\\LaravelPwa',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'admin' => 
      array (
        'driver' => 'session',
        'provider' => 'admins',
      ),
      'tenant_user' => 
      array (
        'driver' => 'session',
        'provider' => 'tenant_users',
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
      'admins' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\Admin',
      ),
      'tenant_users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\TenantUser',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'brand-colors' => 
  array (
    'primary' => 
    array (
      'base' => '#7f1625',
      'dark' => '#5a0f19',
      'darker' => '#3d0a11',
      'light' => '#a01d2f',
      'lighter' => '#c5253d',
      'pale' => '#e6394f',
      'hover' => '#5a0f19',
    ),
    'rgba' => 
    array (
      'primary_20' => 'rgba(127, 22, 37, 0.2)',
      'primary_30' => 'rgba(127, 22, 37, 0.3)',
      'primary_50' => 'rgba(127, 22, 37, 0.5)',
      'primary_70' => 'rgba(127, 22, 37, 0.7)',
      'primary_90' => 'rgba(127, 22, 37, 0.9)',
    ),
    'old_colors' => 
    array (
      'primary_old' => '#b66dff',
      'primary_old_rgba' => 'rgba(182, 109, 255, 0.5)',
      'hover_old' => '#c183ff',
      'border_hover_old' => '#bd7cff',
    ),
    'success' => 
    array (
      'base' => '#28a745',
      'light' => '#34ce57',
      'dark' => '#1e7e34',
    ),
    'warning' => 
    array (
      'base' => '#ffc107',
      'light' => '#ffd454',
      'dark' => '#d39e00',
    ),
    'danger' => 
    array (
      'base' => '#dc3545',
      'light' => '#e4606d',
      'dark' => '#bd2130',
    ),
    'info' => 
    array (
      'base' => '#17a2b8',
      'light' => '#3fbfd2',
      'dark' => '#117a8b',
    ),
    'neutral' => 
    array (
      'white' => '#ffffff',
      'gray_100' => '#f8f9fa',
      'gray_200' => '#e9ecef',
      'gray_300' => '#dee2e6',
      'gray_400' => '#ced4da',
      'gray_500' => '#adb5bd',
      'gray_600' => '#6c757d',
      'gray_700' => '#495057',
      'gray_800' => '#343a40',
      'gray_900' => '#212529',
      'black' => '#000000',
    ),
    'ui' => 
    array (
      'background' => '#ffffff',
      'background_dark' => '#f8f9fa',
      'border' => '#dee2e6',
      'border_light' => '#e9ecef',
      'text_primary' => '#212529',
      'text_secondary' => '#6c757d',
      'text_muted' => '#888888',
      'link' => '#7f1625',
      'link_hover' => '#5a0f19',
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'useTLS' => true,
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'array',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'apc' => 
      array (
        'driver' => 'apc',
      ),
    ),
    'prefix' => 'cache',
  ),
  'cache-tenancy' => 
  array (
    'prefix_strategy' => 
    array (
      'central' => 'central',
      'tenant' => 'tenant_{tenant_id}',
    ),
    'redis_databases' => 
    array (
      'central' => 0,
      'tenant_start' => 1,
      'tenant_end' => 14,
      'queue' => 15,
    ),
    'ttl' => 
    array (
      'default' => 3600,
      'views' => 86400,
      'queries' => 1800,
      'settings' => 43200,
      'translations' => 604800,
    ),
    'enable_tags' => true,
    'tags' => 
    array (
      'products' => 'tenant:{tenant_id}:products',
      'orders' => 'tenant:{tenant_id}:orders',
      'customers' => 'tenant:{tenant_id}:customers',
      'settings' => 'tenant:{tenant_id}:settings',
      'users' => 'tenant:{tenant_id}:users',
      'categories' => 'tenant:{tenant_id}:categories',
    ),
    'auto_clear_on_switch' => true,
    'warming' => 
    array (
      'enabled' => true,
      'keys' => 
      array (
        0 => 'settings',
        1 => 'permissions',
        2 => 'roles',
        3 => 'menus',
      ),
    ),
    'monitoring' => 
    array (
      'enabled' => false,
      'log_channel' => 'cache',
    ),
    'isolation_tests' => 
    array (
      'enabled' => false,
      'test_keys' => 
      array (
        0 => 'test_isolation_key',
        1 => 'test_tenant_data',
        2 => 'test_cache_tags',
      ),
    ),
  ),
  'cart' => 
  array (
    'calculator' => 'Gloudemans\\Shoppingcart\\Calculation\\DefaultCalculator',
    'tax' => 0,
    'database' => 
    array (
      'connection' => 'tenant',
      'table' => 'shoppingcart',
    ),
    'destroy_on_logout' => false,
    'format' => 
    array (
      'decimals' => 2,
      'decimal_point' => '.',
      'thousand_separator' => '',
    ),
  ),
  'chunk-upload' => 
  array (
    'storage' => 
    array (
      'chunks' => 'chunks',
      'disk' => 'local',
    ),
    'clear' => 
    array (
      'timestamp' => '-3 HOURS',
      'schedule' => 
      array (
        'enabled' => true,
        'cron' => '25 * * * *',
      ),
    ),
    'chunk' => 
    array (
      'name' => 
      array (
        'use' => 
        array (
          'session' => true,
          'browser' => false,
        ),
      ),
    ),
    'handlers' => 
    array (
      'custom' => 
      array (
      ),
      'override' => 
      array (
      ),
    ),
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'tammerasaas',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'tammerasaas',
        'username' => 'root',
        'password' => '11221122',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => 'InnoDB',
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'tammerasaas',
        'username' => 'root',
        'password' => '11221122',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'tammerasaas',
        'username' => 'root',
        'password' => '11221122',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'tammerasaas',
        'username' => 'root',
        'password' => '11221122',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => '',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
        'read_timeout' => 60,
        'context' => 
        array (
        ),
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
        'read_timeout' => 60,
        'context' => 
        array (
        ),
      ),
      'session' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '2',
        'read_timeout' => 60,
        'context' => 
        array (
        ),
      ),
      'queue' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '15',
        'read_timeout' => 60,
        'context' => 
        array (
        ),
      ),
    ),
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/fonts',
      'font_cache' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/fonts',
      'temp_dir' => '/var/folders/jy/zd3dvl8531x53gscggczcp0m0000gn/T',
      'chroot' => '/Users/alialalawi/Sites/localhost/asaas/core',
      'allowed_protocols' => 
      array (
        'data://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'file://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'http://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'https://' => 
        array (
          'rules' => 
          array (
          ),
        ),
      ),
      'artifactPathValidation' => NULL,
      'log_output_file' => NULL,
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_paper_orientation' => 'portrait',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => false,
      'allowed_remote_hosts' => NULL,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
    'orientation' => 'portrait',
    'defines' => 
    array (
      'font_dir' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/fonts',
      'font_cache' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/fonts',
      'temp_dir' => '/var/folders/jy/zd3dvl8531x53gscggczcp0m0000gn/T',
      'chroot' => '/Users/alialalawi/Sites/localhost/asaas/core',
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_font' => 'DejaVu Sans',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => true,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => false,
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/app/public',
        'url' => 'https://asaas.local/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
      ),
      'root_url' => 
      array (
        'driver' => 'local',
        'root' => '/Users/alialalawi/Sites/localhost/asaas/core/public/../../',
        'url' => 'https://asaas.local/assets',
        'visibility' => 'root_url',
      ),
      'wasabi' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => '',
        'bucket' => '',
        'endpoint' => '',
      ),
      'LandlordMediaUploader' => 
      array (
        'driver' => 'local',
        'root' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/../../assets/landlord/uploads/media-uploader/',
        'throw' => false,
      ),
      'TenantMediaUploader' => 
      array (
        'driver' => 'local',
        'root' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/../../assets/tenant/uploads/media-uploader/',
        'throw' => false,
      ),
      'cloudFlareR2' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => '',
        'visibility' => 'private',
        'endpoint' => '',
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      '/Users/alialalawi/Sites/localhost/asaas/core/public/storage' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/app/public',
    ),
    'cloud' => 's3',
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
    'rehash_on_login' => true,
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'installer' => 
  array (
    'app_name' => 'Nazmart',
    'super_admin_role_id' => 1,
    'admin_model' => 'App\\Models\\Admin',
    'admin_table' => 'admins',
    'multi_tenant' => true,
    'author' => 'xgenious',
    'product_key' => '5debb671064213be9c0db593d094da0df3c4e2dd',
    'php_version' => '8.1',
    'extensions' => 
    array (
      0 => 'BCMath',
      1 => 'Ctype',
      2 => 'JSON',
      3 => 'Mbstring',
      4 => 'OpenSSL',
      5 => 'PDO',
      6 => 'pdo_mysql',
      7 => 'Tokenizer',
      8 => 'XML',
      9 => 'cURL',
      10 => 'fileinfo',
    ),
    'website' => 'https://xgenious.com',
    'email' => 'support@xgenious.com',
    'env_example_path' => '/Users/alialalawi/Sites/localhost/asaas/core/public/env-sample.txt',
    'broadcast_driver' => 'log',
    'cache_driver' => 'array',
    'queue_connection' => 'database',
    'mail_port' => '587',
    'mail_encryption' => 'tls',
    'model_has_roles' => true,
    'bundle_pack' => true,
    'bundle_pack_key' => '6adc506fc0e376bfd2d148663cda94c0cfa8a215',
  ),
  'invoices' => 
  array (
    'date' => 
    array (
      'format' => 'Y-m-d',
      'pay_until_days' => 7,
    ),
    'serial_number' => 
    array (
      'series' => 'AA',
      'sequence' => 1,
      'sequence_padding' => 5,
      'delimiter' => '.',
      'format' => '{SERIES}{DELIMITER}{SEQUENCE}',
    ),
    'currency' => 
    array (
      'code' => 'eur',
      'fraction' => 'ct.',
      'symbol' => '€',
      'decimals' => 2,
      'decimal_point' => '.',
      'thousands_separator' => ',',
      'format' => '{VALUE} {SYMBOL}',
    ),
    'paper' => 
    array (
      'size' => 'a4',
      'orientation' => 'portrait',
    ),
    'disk' => 'local',
    'seller' => 
    array (
      'class' => 'LaravelDaily\\Invoices\\Classes\\Seller',
      'attributes' => 
      array (
        'name' => 'Towne, Smith and Ebert',
        'address' => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
        'code' => '41-1985581',
        'vat' => '123456789',
        'phone' => '760-355-3930',
        'custom_fields' => 
        array (
          'SWIFT' => 'BANK101',
        ),
      ),
    ),
    'dompdf_options' => 
    array (
      'enable_php' => true,
      'logOutputFile' => '/dev/null',
    ),
  ),
  'laravel-translatable-string-exporter' => 
  array (
    'directories' => 
    array (
      0 => 'resources/views',
      1 => 'plugins/PageBuilder/Addons',
      2 => 'plugins/WidgetBuilder/Widgets',
    ),
    'patterns' => 
    array (
      0 => '*.php',
      1 => '*.js',
    ),
    'allow-newlines' => false,
    'functions' => 
    array (
      0 => '__',
      1 => '_t',
      2 => '@lang',
    ),
    'sort-keys' => true,
    'add-persistent-strings-to-translations' => false,
    'exclude-translation-keys' => false,
    'put-untranslated-strings-at-the-top' => false,
  ),
  'logging' => 
  array (
    'default' => 'daily',
    'deprecations' => NULL,
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => 'YOUR_SMTP_HOST_NAME',
        'port' => '587',
        'encryption' => 'tls',
        'username' => 'YOUR_SMTP_USERNAME',
        'password' => 'YOUR_SMTP_USERNAME_PASSWORD',
        'timeout' => NULL,
        'auth_mode' => NULL,
        'verify_peer' => false,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -t -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
    ),
    'from' => 
    array (
      'address' => NULL,
      'name' => 'NazMart - Multi-Tenancy eCommerce Sass',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/Users/alialalawi/Sites/localhost/asaas/core/resources/views/vendor/mail',
      ),
    ),
  ),
  'modules' => 
  array (
    'namespace' => 'Modules',
    'stubs' => 
    array (
      'enabled' => false,
      'path' => '/Users/alialalawi/Sites/localhost/asaas/core/vendor/nwidart/laravel-modules/src/Commands/stubs',
      'files' => 
      array (
        'routes/web' => 'Routes/web.php',
        'routes/api' => 'Routes/api.php',
        'views/index' => 'Resources/views/index.blade.php',
        'views/master' => 'Resources/views/layouts/master.blade.php',
        'scaffold/config' => 'Config/config.php',
        'composer' => 'composer.json',
        'assets/js/app' => 'Resources/assets/js/app.js',
        'assets/sass/app' => 'Resources/assets/sass/app.scss',
        'webpack' => 'webpack.mix.js',
        'package' => 'package.json',
      ),
      'replacements' => 
      array (
        'routes/web' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
        ),
        'routes/api' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'webpack' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'json' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'MODULE_NAMESPACE',
          3 => 'PROVIDER_NAMESPACE',
        ),
        'views/index' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'views/master' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
        ),
        'scaffold/config' => 
        array (
          0 => 'STUDLY_NAME',
        ),
        'composer' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'VENDOR',
          3 => 'AUTHOR_NAME',
          4 => 'AUTHOR_EMAIL',
          5 => 'MODULE_NAMESPACE',
          6 => 'PROVIDER_NAMESPACE',
        ),
      ),
      'gitkeep' => true,
    ),
    'paths' => 
    array (
      'modules' => '/Users/alialalawi/Sites/localhost/asaas/core/Modules',
      'assets' => '/Users/alialalawi/Sites/localhost/asaas/core/public/modules',
      'migration' => '/Users/alialalawi/Sites/localhost/asaas/core/database/migrations',
      'generator' => 
      array (
        'config' => 
        array (
          'path' => 'Config',
          'generate' => true,
        ),
        'command' => 
        array (
          'path' => 'Console',
          'generate' => true,
        ),
        'migration' => 
        array (
          'path' => 'Database/Migrations',
          'generate' => true,
        ),
        'seeder' => 
        array (
          'path' => 'Database/Seeders',
          'generate' => true,
        ),
        'factory' => 
        array (
          'path' => 'Database/factories',
          'generate' => true,
        ),
        'model' => 
        array (
          'path' => 'Entities',
          'generate' => true,
        ),
        'routes' => 
        array (
          'path' => 'Routes',
          'generate' => true,
        ),
        'controller' => 
        array (
          'path' => 'Http/Controllers',
          'generate' => true,
        ),
        'filter' => 
        array (
          'path' => 'Http/Middleware',
          'generate' => true,
        ),
        'request' => 
        array (
          'path' => 'Http/Requests',
          'generate' => true,
        ),
        'provider' => 
        array (
          'path' => 'Providers',
          'generate' => true,
        ),
        'assets' => 
        array (
          'path' => 'Resources/assets',
          'generate' => true,
        ),
        'lang' => 
        array (
          'path' => 'Resources/lang',
          'generate' => true,
        ),
        'views' => 
        array (
          'path' => 'Resources/views',
          'generate' => true,
        ),
        'test' => 
        array (
          'path' => 'Tests/Unit',
          'generate' => true,
        ),
        'test-feature' => 
        array (
          'path' => 'Tests/Feature',
          'generate' => true,
        ),
        'repository' => 
        array (
          'path' => 'Repositories',
          'generate' => false,
        ),
        'event' => 
        array (
          'path' => 'Events',
          'generate' => false,
        ),
        'listener' => 
        array (
          'path' => 'Listeners',
          'generate' => false,
        ),
        'policies' => 
        array (
          'path' => 'Policies',
          'generate' => false,
        ),
        'rules' => 
        array (
          'path' => 'Rules',
          'generate' => false,
        ),
        'jobs' => 
        array (
          'path' => 'Jobs',
          'generate' => false,
        ),
        'emails' => 
        array (
          'path' => 'Emails',
          'generate' => false,
        ),
        'notifications' => 
        array (
          'path' => 'Notifications',
          'generate' => false,
        ),
        'resource' => 
        array (
          'path' => 'Transformers',
          'generate' => false,
        ),
        'component-view' => 
        array (
          'path' => 'Resources/views/components',
          'generate' => false,
        ),
        'component-class' => 
        array (
          'path' => 'View/Components',
          'generate' => false,
        ),
      ),
    ),
    'commands' => 
    array (
      0 => 'Nwidart\\Modules\\Commands\\CommandMakeCommand',
      1 => 'Nwidart\\Modules\\Commands\\ComponentClassMakeCommand',
      2 => 'Nwidart\\Modules\\Commands\\ComponentViewMakeCommand',
      3 => 'Nwidart\\Modules\\Commands\\ControllerMakeCommand',
      4 => 'Nwidart\\Modules\\Commands\\DisableCommand',
      5 => 'Nwidart\\Modules\\Commands\\DumpCommand',
      6 => 'Nwidart\\Modules\\Commands\\EnableCommand',
      7 => 'Nwidart\\Modules\\Commands\\EventMakeCommand',
      8 => 'Nwidart\\Modules\\Commands\\JobMakeCommand',
      9 => 'Nwidart\\Modules\\Commands\\ListenerMakeCommand',
      10 => 'Nwidart\\Modules\\Commands\\MailMakeCommand',
      11 => 'Nwidart\\Modules\\Commands\\MiddlewareMakeCommand',
      12 => 'Nwidart\\Modules\\Commands\\NotificationMakeCommand',
      13 => 'Nwidart\\Modules\\Commands\\ProviderMakeCommand',
      14 => 'Nwidart\\Modules\\Commands\\RouteProviderMakeCommand',
      15 => 'Nwidart\\Modules\\Commands\\InstallCommand',
      16 => 'Nwidart\\Modules\\Commands\\ListCommand',
      17 => 'Nwidart\\Modules\\Commands\\ModuleDeleteCommand',
      18 => 'Nwidart\\Modules\\Commands\\ModuleMakeCommand',
      19 => 'Nwidart\\Modules\\Commands\\FactoryMakeCommand',
      20 => 'Nwidart\\Modules\\Commands\\PolicyMakeCommand',
      21 => 'Nwidart\\Modules\\Commands\\RequestMakeCommand',
      22 => 'Nwidart\\Modules\\Commands\\RuleMakeCommand',
      23 => 'Nwidart\\Modules\\Commands\\MigrateCommand',
      24 => 'Nwidart\\Modules\\Commands\\MigrateRefreshCommand',
      25 => 'Nwidart\\Modules\\Commands\\MigrateResetCommand',
      26 => 'Nwidart\\Modules\\Commands\\MigrateRollbackCommand',
      27 => 'Nwidart\\Modules\\Commands\\MigrateStatusCommand',
      28 => 'Nwidart\\Modules\\Commands\\MigrationMakeCommand',
      29 => 'Nwidart\\Modules\\Commands\\ModelMakeCommand',
      30 => 'Nwidart\\Modules\\Commands\\PublishCommand',
      31 => 'Nwidart\\Modules\\Commands\\PublishConfigurationCommand',
      32 => 'Nwidart\\Modules\\Commands\\PublishMigrationCommand',
      33 => 'Nwidart\\Modules\\Commands\\PublishTranslationCommand',
      34 => 'Nwidart\\Modules\\Commands\\SeedCommand',
      35 => 'Nwidart\\Modules\\Commands\\SeedMakeCommand',
      36 => 'Nwidart\\Modules\\Commands\\SetupCommand',
      37 => 'Nwidart\\Modules\\Commands\\UnUseCommand',
      38 => 'Nwidart\\Modules\\Commands\\UpdateCommand',
      39 => 'Nwidart\\Modules\\Commands\\UseCommand',
      40 => 'Nwidart\\Modules\\Commands\\ResourceMakeCommand',
      41 => 'Nwidart\\Modules\\Commands\\TestMakeCommand',
      42 => 'Nwidart\\Modules\\Commands\\LaravelModulesV6Migrator',
      43 => 'Nwidart\\Modules\\Commands\\ComponentClassMakeCommand',
      44 => 'Nwidart\\Modules\\Commands\\ComponentViewMakeCommand',
    ),
    'scan' => 
    array (
      'enabled' => false,
      'paths' => 
      array (
        0 => '/Users/alialalawi/Sites/localhost/asaas/core/vendor/*/*',
      ),
    ),
    'composer' => 
    array (
      'vendor' => 'nwidart',
      'author' => 
      array (
        'name' => 'Nicolas Widart',
        'email' => 'n.widart@gmail.com',
      ),
      'composer-output' => false,
    ),
    'cache' => 
    array (
      'enabled' => false,
      'key' => 'laravel-modules',
      'lifetime' => 60,
    ),
    'register' => 
    array (
      'translations' => true,
      'files' => 'register',
    ),
    'activators' => 
    array (
      'file' => 
      array (
        'class' => 'Nwidart\\Modules\\Activators\\FileActivator',
        'statuses-file' => '/Users/alialalawi/Sites/localhost/asaas/core/modules_statuses.json',
        'cache-key' => 'activator.installed',
        'cache-lifetime' => 604800,
      ),
    ),
    'activator' => 'file',
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'role_pivot_key' => NULL,
      'permission_pivot_key' => NULL,
      'model_morph_key' => 'model_id',
      'team_foreign_key' => 'team_id',
    ),
    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => 'Spatie\\Permission\\DefaultTeamResolver',
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '24 hours',
      )),
      'key' => 'spatie.permission.cache',
      'store' => 'default',
    ),
  ),
  'purifier' => 
  array (
    'encoding' => 'UTF-8',
    'finalize' => true,
    'ignoreNonStrings' => false,
    'cachePath' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/app/purifier',
    'cacheFileMode' => 493,
    'settings' => 
    array (
      'default' => 
      array (
        'HTML.Doctype' => 'HTML 4.01 Transitional',
        'HTML.Allowed' => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
        'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty' => true,
      ),
      'test' => 
      array (
        'Attr.EnableID' => 'true',
      ),
      'youtube' => 
      array (
        'HTML.SafeIframe' => 'true',
        'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
      ),
      'custom_definition' => 
      array (
        'id' => 'html5-definitions',
        'rev' => 1,
        'debug' => false,
        'elements' => 
        array (
          0 => 
          array (
            0 => 'section',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          1 => 
          array (
            0 => 'nav',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          2 => 
          array (
            0 => 'article',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          3 => 
          array (
            0 => 'aside',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          4 => 
          array (
            0 => 'header',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          5 => 
          array (
            0 => 'footer',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          6 => 
          array (
            0 => 'address',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          7 => 
          array (
            0 => 'hgroup',
            1 => 'Block',
            2 => 'Required: h1 | h2 | h3 | h4 | h5 | h6',
            3 => 'Common',
          ),
          8 => 
          array (
            0 => 'figure',
            1 => 'Block',
            2 => 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow',
            3 => 'Common',
          ),
          9 => 
          array (
            0 => 'figcaption',
            1 => 'Inline',
            2 => 'Flow',
            3 => 'Common',
          ),
          10 => 
          array (
            0 => 'video',
            1 => 'Block',
            2 => 'Optional: (source, Flow) | (Flow, source) | Flow',
            3 => 'Common',
            4 => 
            array (
              'src' => 'URI',
              'type' => 'Text',
              'width' => 'Length',
              'height' => 'Length',
              'poster' => 'URI',
              'preload' => 'Enum#auto,metadata,none',
              'controls' => 'Bool',
            ),
          ),
          11 => 
          array (
            0 => 'source',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'src' => 'URI',
              'type' => 'Text',
            ),
          ),
          12 => 
          array (
            0 => 's',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          13 => 
          array (
            0 => 'var',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          14 => 
          array (
            0 => 'sub',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          15 => 
          array (
            0 => 'sup',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          16 => 
          array (
            0 => 'mark',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          17 => 
          array (
            0 => 'wbr',
            1 => 'Inline',
            2 => 'Empty',
            3 => 'Core',
          ),
          18 => 
          array (
            0 => 'ins',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'cite' => 'URI',
              'datetime' => 'CDATA',
            ),
          ),
          19 => 
          array (
            0 => 'del',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'cite' => 'URI',
              'datetime' => 'CDATA',
            ),
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            0 => 'iframe',
            1 => 'allowfullscreen',
            2 => 'Bool',
          ),
          1 => 
          array (
            0 => 'table',
            1 => 'height',
            2 => 'Text',
          ),
          2 => 
          array (
            0 => 'td',
            1 => 'border',
            2 => 'Text',
          ),
          3 => 
          array (
            0 => 'th',
            1 => 'border',
            2 => 'Text',
          ),
          4 => 
          array (
            0 => 'tr',
            1 => 'width',
            2 => 'Text',
          ),
          5 => 
          array (
            0 => 'tr',
            1 => 'height',
            2 => 'Text',
          ),
          6 => 
          array (
            0 => 'tr',
            1 => 'border',
            2 => 'Text',
          ),
        ),
      ),
      'custom_attributes' => 
      array (
        0 => 
        array (
          0 => 'a',
          1 => 'target',
          2 => 'Enum#_blank,_self,_target,_top',
        ),
      ),
      'custom_elements' => 
      array (
        0 => 
        array (
          0 => 'u',
          1 => 'Inline',
          2 => 'Inline',
          3 => 'Common',
        ),
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'database',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
        'connection' => 'mysql',
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
      'tenant_file_sync' => 
      array (
        'driver' => 'database',
        'table' => 'file_sync_jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
        'connection' => 'mysql',
      ),
    ),
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
      'connection' => 'mysql',
    ),
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => '',
    ),
    'guard' => 
    array (
      0 => 'web',
      1 => 'admin',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'authenticate_session' => 'Laravel\\Sanctum\\Http\\Middleware\\AuthenticateSession',
      'encrypt_cookies' => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
      'validate_csrf_token' => 'Illuminate\\Foundation\\Http\\Middleware\\ValidateCsrfToken',
    ),
  ),
  'seotools' => 
  array (
    'inertia' => false,
    'meta' => 
    array (
      'defaults' => 
      array (
        'title' => false,
        'titleBefore' => false,
        'description' => false,
        'separator' => ' - ',
        'keywords' => 
        array (
        ),
        'canonical' => false,
        'robots' => false,
      ),
      'webmaster_tags' => 
      array (
        'google' => NULL,
        'bing' => NULL,
        'alexa' => NULL,
        'pinterest' => NULL,
        'yandex' => NULL,
        'norton' => NULL,
      ),
      'add_notranslate_class' => false,
    ),
    'opengraph' => 
    array (
      'defaults' => 
      array (
        'title' => false,
        'description' => false,
        'url' => false,
        'type' => false,
        'site_name' => false,
        'images' => 
        array (
        ),
      ),
    ),
    'twitter' => 
    array (
      'defaults' => 
      array (
      ),
    ),
    'json-ld' => 
    array (
      'defaults' => 
      array (
        'title' => 'Over 9000 Thousand!',
        'description' => 'For those who helped create the Genki Dama',
        'url' => false,
        'type' => 'WebPage',
        'images' => 
        array (
        ),
      ),
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'nazmart_multi_tenancy_ecommerce_sass_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => NULL,
    'partitioned' => false,
  ),
  'sitemap' => 
  array (
    'guzzle_options' => 
    array (
      'cookies' => true,
      'connect_timeout' => 10,
      'timeout' => 10,
      'allow_redirects' => false,
    ),
    'execute_javascript' => false,
    'chrome_binary_path' => NULL,
    'crawl_profile' => 'Spatie\\Sitemap\\Crawler\\Profile',
  ),
  'telescope' => 
  array (
    'domain' => NULL,
    'path' => 'telescope',
    'driver' => 'database',
    'storage' => 
    array (
      'database' => 
      array (
        'connection' => 'mysql',
        'chunk' => 1000,
      ),
    ),
    'enabled' => true,
    'middleware' => 
    array (
      0 => 'web',
      1 => 'Laravel\\Telescope\\Http\\Middleware\\Authorize',
    ),
    'only_paths' => 
    array (
    ),
    'ignore_paths' => 
    array (
      0 => 'nova-api*',
    ),
    'ignore_commands' => 
    array (
    ),
    'watchers' => 
    array (
      'Laravel\\Telescope\\Watchers\\BatchWatcher' => true,
      'Laravel\\Telescope\\Watchers\\CacheWatcher' => 
      array (
        'enabled' => true,
        'hidden' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ClientRequestWatcher' => true,
      'Laravel\\Telescope\\Watchers\\CommandWatcher' => 
      array (
        'enabled' => true,
        'ignore' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\DumpWatcher' => 
      array (
        'enabled' => true,
        'always' => false,
      ),
      'Laravel\\Telescope\\Watchers\\EventWatcher' => 
      array (
        'enabled' => true,
        'ignore' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ExceptionWatcher' => true,
      'Laravel\\Telescope\\Watchers\\GateWatcher' => 
      array (
        'enabled' => true,
        'ignore_abilities' => 
        array (
        ),
        'ignore_packages' => true,
        'ignore_paths' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\JobWatcher' => true,
      'Laravel\\Telescope\\Watchers\\LogWatcher' => 
      array (
        'enabled' => true,
        'level' => 'error',
      ),
      'Laravel\\Telescope\\Watchers\\MailWatcher' => true,
      'Laravel\\Telescope\\Watchers\\ModelWatcher' => 
      array (
        'enabled' => true,
        'events' => 
        array (
          0 => 'eloquent.*',
        ),
        'hydrations' => true,
      ),
      'Laravel\\Telescope\\Watchers\\NotificationWatcher' => true,
      'Laravel\\Telescope\\Watchers\\QueryWatcher' => 
      array (
        'enabled' => true,
        'ignore_packages' => true,
        'ignore_paths' => 
        array (
        ),
        'slow' => 100,
      ),
      'Laravel\\Telescope\\Watchers\\RedisWatcher' => true,
      'Laravel\\Telescope\\Watchers\\RequestWatcher' => 
      array (
        'enabled' => true,
        'size_limit' => 64,
        'ignore_http_methods' => 
        array (
        ),
        'ignore_status_codes' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ScheduleWatcher' => true,
      'Laravel\\Telescope\\Watchers\\ViewWatcher' => true,
    ),
  ),
  'tenancy' => 
  array (
    'tenant_model' => 'App\\Models\\Tenant',
    'id_generator' => 'Stancl\\Tenancy\\UUIDGenerator',
    'domain_model' => 'Stancl\\Tenancy\\Database\\Models\\Domain',
    'central_domains' => 
    array (
      0 => 'asaas.local',
    ),
    'bootstrappers' => 
    array (
      0 => 'Stancl\\Tenancy\\Bootstrappers\\DatabaseTenancyBootstrapper',
      1 => 'Stancl\\Tenancy\\Bootstrappers\\CacheTenancyBootstrapper',
      2 => 'Stancl\\Tenancy\\Bootstrappers\\FilesystemTenancyBootstrapper',
      3 => 'Stancl\\Tenancy\\Bootstrappers\\QueueTenancyBootstrapper',
    ),
    'database' => 
    array (
      'central_connection' => 'mysql',
      ' ' => NULL,
      'prefix' => 'tammerasaas_',
      'suffix' => '',
      'managers' => 
      array (
        'sqlite' => 'Stancl\\Tenancy\\TenantDatabaseManagers\\SQLiteDatabaseManager',
        'mysql' => 'Stancl\\Tenancy\\TenantDatabaseManagers\\MySQLDatabaseManager',
        'pgsql' => 'Stancl\\Tenancy\\TenantDatabaseManagers\\PostgreSQLDatabaseManager',
      ),
    ),
    'cache' => 
    array (
      'tag_base' => 'tenant',
    ),
    'filesystem' => 
    array (
      'suffix_base' => 'tenant',
      'disks' => 
      array (
        0 => 'local',
        1 => 'public',
      ),
      'root_override' => 
      array (
        'local' => '%storage_path%/app/',
        'public' => '%storage_path%/app/public/',
      ),
      'suffix_storage_path' => true,
      'asset_helper_tenancy' => true,
    ),
    'redis' => 
    array (
      'prefix_base' => 'tenant_',
      'prefixed_connections' => 
      array (
      ),
    ),
    'features' => 
    array (
      0 => 'Stancl\\Tenancy\\Features\\TenantConfig',
      1 => 'Stancl\\Tenancy\\Features\\CrossDomainRedirect',
    ),
    'routes' => true,
    'migration_parameters' => 
    array (
      '--force' => true,
      '--path' => 
      array (
        0 => '/Users/alialalawi/Sites/localhost/asaas/core/database/migrations/tenant',
        1 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/Badge/Database/Migrations',
        2 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/Attributes/Database/Migrations',
        3 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/Campaign/Database/Migrations',
        4 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/ShippingModule/Database/Migrations',
        5 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/RefundModule/Database/Migrations',
        6 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/Product/Database/Migrations',
        7 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/MobileApp/Database/Migrations',
        8 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/DigitalProduct/Database/Migrations',
        9 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/TaxModule/Database/Migrations',
        10 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/SmsGateway/Database/Migrations',
        11 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/SiteAnalytics/Database/Migrations',
        12 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/ShippingPlugin/Database/Migrations',
        13 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/CouponManage/Database/Migrations',
        14 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/CountryManage/Database/Migrations',
        15 => '/Users/alialalawi/Sites/localhost/asaas/core/Modules/Inventory/Database/Migrations',
      ),
      '--realpath' => true,
    ),
    'seeder_parameters' => 
    array (
      '--class' => 'TenantDatabaseSeeder',
    ),
  ),
  'translatable' => 
  array (
    'fallback_locale' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/Users/alialalawi/Sites/localhost/asaas/core/resources/views',
    ),
    'compiled' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/framework/views',
  ),
  'webhook-server' => 
  array (
    'queue' => 'default',
    'connection' => NULL,
    'http_verb' => 'post',
    'proxy' => NULL,
    'signer' => 'Spatie\\WebhookServer\\Signer\\DefaultSigner',
    'signature_header_name' => 'Signature',
    'headers' => 
    array (
      'Content-Type' => 'application/json',
    ),
    'timeout_in_seconds' => 3,
    'tries' => 3,
    'backoff_strategy' => 'Spatie\\WebhookServer\\BackoffStrategy\\ExponentialBackoffStrategy',
    'webhook_job' => 'Spatie\\WebhookServer\\CallWebhookJob',
    'verify_ssl' => true,
    'throw_exception_on_failure' => false,
    'tags' => 
    array (
    ),
  ),
  'xgapiclient' => 
  array (
    'base_api_url' => 'https://license.xgenious.com/api/',
    'has_token' => '5debb671064213be9c0db593d094da0df3c4e2dd',
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'hide_empty_tabs' => true,
    'except' => 
    array (
      0 => 'telescope*',
      1 => 'horizon*',
    ),
    'storage' => 
    array (
      'enabled' => true,
      'open' => NULL,
      'driver' => 'file',
      'path' => '/Users/alialalawi/Sites/localhost/asaas/core/storage/debugbar',
      'connection' => NULL,
      'provider' => '',
      'hostname' => '127.0.0.1',
      'port' => 2304,
    ),
    'editor' => 'phpstorm',
    'remote_sites_path' => NULL,
    'local_sites_path' => NULL,
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'ajax_handler_auto_show' => true,
    'ajax_handler_enable_tab' => true,
    'defer_datasets' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => false,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => false,
      'auth' => false,
      'gate' => true,
      'session' => false,
      'symfony_request' => true,
      'mail' => true,
      'laravel' => true,
      'events' => false,
      'default_request' => false,
      'logs' => false,
      'files' => false,
      'config' => false,
      'cache' => false,
      'models' => true,
      'livewire' => true,
      'jobs' => false,
      'pennant' => false,
    ),
    'options' => 
    array (
      'time' => 
      array (
        'memory_usage' => false,
      ),
      'messages' => 
      array (
        'trace' => true,
        'capture_dumps' => false,
      ),
      'memory' => 
      array (
        'reset_peak' => false,
        'with_baseline' => false,
        'precision' => 0,
      ),
      'auth' => 
      array (
        'show_name' => true,
        'show_guards' => true,
      ),
      'gate' => 
      array (
        'trace' => false,
      ),
      'db' => 
      array (
        'with_params' => true,
        'exclude_paths' => 
        array (
        ),
        'backtrace' => true,
        'backtrace_exclude_paths' => 
        array (
        ),
        'timeline' => false,
        'duration_background' => true,
        'explain' => 
        array (
          'enabled' => false,
        ),
        'hints' => false,
        'show_copy' => true,
        'slow_threshold' => false,
        'memory_usage' => false,
        'soft_limit' => 100,
        'hard_limit' => 500,
      ),
      'mail' => 
      array (
        'timeline' => true,
        'show_body' => true,
      ),
      'views' => 
      array (
        'timeline' => true,
        'data' => false,
        'group' => 50,
        'inertia_pages' => 'js/Pages',
        'exclude_paths' => 
        array (
          0 => 'vendor/filament',
        ),
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'session' => 
      array (
        'hiddens' => 
        array (
        ),
      ),
      'symfony_request' => 
      array (
        'label' => true,
        'hiddens' => 
        array (
        ),
      ),
      'events' => 
      array (
        'data' => false,
        'excluded' => 
        array (
        ),
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
      'cache' => 
      array (
        'values' => true,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_middleware' => 
    array (
    ),
    'route_domain' => NULL,
    'theme' => 'auto',
    'debug_backtrace_limit' => 50,
  ),
  'mollie' => 
  array (
    'key' => 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
  ),
  'flare' => 
  array (
    'key' => NULL,
    'flare_middleware' => 
    array (
      0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
      1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
      2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
      3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
      4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
      5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => 
      array (
        'maximum_number_of_collected_logs' => 200,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => 
      array (
        'maximum_number_of_collected_queries' => 200,
        'report_query_bindings' => true,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => 
      array (
        'max_chained_job_reporting_depth' => 5,
      ),
      6 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddContext',
      7 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionHandledStatus',
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => 
      array (
        'censor_fields' => 
        array (
          0 => 'password',
          1 => 'password_confirmation',
        ),
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => 
      array (
        'headers' => 
        array (
          0 => 'API-KEY',
          1 => 'Authorization',
          2 => 'Cookie',
          3 => 'Set-Cookie',
          4 => 'X-CSRF-TOKEN',
          5 => 'X-XSRF-TOKEN',
        ),
      ),
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'auto',
    'enable_share_button' => true,
    'register_commands' => false,
    'solution_providers' => 
    array (
      0 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\BadMethodCallSolutionProvider',
      1 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\MergeConflictSolutionProvider',
      2 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\UndefinedPropertySolutionProvider',
      3 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\IncorrectValetDbCredentialsSolutionProvider',
      4 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingAppKeySolutionProvider',
      5 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\DefaultDbNameSolutionProvider',
      6 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\TableNotFoundSolutionProvider',
      7 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingImportSolutionProvider',
      8 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\InvalidRouteActionSolutionProvider',
      9 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\ViewNotFoundSolutionProvider',
      10 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\RunningLaravelDuskInProductionProvider',
      11 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingColumnSolutionProvider',
      12 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownValidationSolutionProvider',
      13 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingMixManifestSolutionProvider',
      14 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingViteManifestSolutionProvider',
      15 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingLivewireComponentSolutionProvider',
      16 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UndefinedViewVariableSolutionProvider',
      17 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\GenericLaravelExceptionSolutionProvider',
      18 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\OpenAiSolutionProvider',
      19 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\SailNetworkSolutionProvider',
      20 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMysql8CollationSolutionProvider',
      21 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMariadbCollationSolutionProvider',
    ),
    'ignored_solution_providers' => 
    array (
    ),
    'enable_runnable_solutions' => NULL,
    'remote_sites_path' => '/Users/alialalawi/Sites/localhost/asaas/core',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
    'settings_file_path' => '',
    'recorders' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\Recorders\\DumpRecorder\\DumpRecorder',
      1 => 'Spatie\\LaravelIgnition\\Recorders\\JobRecorder\\JobRecorder',
      2 => 'Spatie\\LaravelIgnition\\Recorders\\LogRecorder\\LogRecorder',
      3 => 'Spatie\\LaravelIgnition\\Recorders\\QueryRecorder\\QueryRecorder',
    ),
    'open_ai_key' => NULL,
    'with_stack_frame_arguments' => true,
    'argument_reducers' => 
    array (
      0 => 'Spatie\\Backtrace\\Arguments\\Reducers\\BaseTypeArgumentReducer',
      1 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ArrayArgumentReducer',
      2 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StdClassArgumentReducer',
      3 => 'Spatie\\Backtrace\\Arguments\\Reducers\\EnumArgumentReducer',
      4 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ClosureArgumentReducer',
      5 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeArgumentReducer',
      6 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeZoneArgumentReducer',
      7 => 'Spatie\\Backtrace\\Arguments\\Reducers\\SymphonyRequestArgumentReducer',
      8 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\ModelArgumentReducer',
      9 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\CollectionArgumentReducer',
      10 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StringableArgumentReducer',
    ),
  ),
  'paypal' => 
  array (
    'mode' => 'sandbox',
    'sandbox' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'app_id' => 'APP-80W284485P519543T',
    ),
    'live' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'app_id' => '',
    ),
    'payment_action' => 'Sale',
    'currency' => 'USD',
    'notify_url' => '',
    'locale' => 'en_US',
    'validate_ssl' => true,
  ),
  'paymentgateway' => 
  array (
    'stripe' => 
    array (
      'secret_key' => NULL,
      'public_key' => NULL,
    ),
    'paypal' => 
    array (
      'mode' => 'sandbox',
      'sandbox' => 
      array (
        'client_id' => '',
        'client_secret' => '',
        'app_id' => '',
      ),
      'live' => 
      array (
        'client_id' => '',
        'client_secret' => '',
        'app_id' => '',
      ),
      'payment_action' => 'Sale',
      'currency' => 'USD',
      'notify_url' => '',
      'locale' => 'en_US',
      'validate_ssl' => true,
    ),
    'midtrans' => 
    array (
      'merchant_id' => NULL,
      'server_key' => NULL,
      'client_key' => NULL,
      'envaironment' => false,
    ),
    'paytm' => 
    array (
      'env' => 'local',
      'merchant_id' => NULL,
      'merchant_key' => NULL,
      'merchant_website' => NULL,
      'channel' => NULL,
      'industry_type' => NULL,
    ),
    'razorpay' => 
    array (
      'api_key' => NULL,
      'api_secret' => NULL,
    ),
    'mollie' => 
    array (
      'public_key' => NULL,
    ),
    'flutterwave' => 
    array (
      'public_key' => NULL,
      'secret_key' => NULL,
      'secret_hash' => 'abcd',
    ),
    'paystack' => 
    array (
      'public_key' => NULL,
      'secret_key' => NULL,
      'payment_url' => 'https://api.paystack.co',
      'merchant_email' => '',
    ),
    'payfast' => 
    array (
      'merchant_id' => NULL,
      'merchant_key' => NULL,
      'passpharse' => NULL,
      'environment' => true,
      'PF_ITN_URL' => NULL,
    ),
    'cashfree' => 
    array (
      'test_mode' => 'true',
      'app_id' => NULL,
      'secret_key' => NULL,
    ),
    'instamojo' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'test_mode' => true,
    ),
    'mercadopago' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'test_mode' => true,
    ),
    'global_currency' => 'USD',
    'ngn_exchange_rate' => NULL,
    'inr_exchange_rate' => NULL,
    'usd_exchange_rate' => NULL,
    'idr_exchange_rate' => NULL,
    'zar_exchange_rate' => NULL,
    'brl_exchange_rate' => NULL,
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'multi_term' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
      'starts_with' => false,
    ),
    'index_column' => 'DT_RowIndex',
    'engines' => 
    array (
      'eloquent' => 'Yajra\\DataTables\\EloquentDataTable',
      'query' => 'Yajra\\DataTables\\QueryDataTable',
      'collection' => 'Yajra\\DataTables\\CollectionDataTable',
      'resource' => 'Yajra\\DataTables\\ApiResourceDataTable',
    ),
    'builders' => 
    array (
    ),
    'nulls_last_sql' => ':column :direction NULLS LAST',
    'error' => NULL,
    'columns' => 
    array (
      'excess' => 
      array (
        0 => 'rn',
        1 => 'row_num',
      ),
      'escape' => '*',
      'raw' => 
      array (
        0 => 'action',
      ),
      'blacklist' => 
      array (
        0 => 'password',
        1 => 'remember_token',
      ),
      'whitelist' => '*',
    ),
    'json' => 
    array (
      'header' => 
      array (
      ),
      'options' => 0,
    ),
    'callback' => 
    array (
      0 => '$',
      1 => '$.',
      2 => 'function',
    ),
  ),
  'paytabs' => 
  array (
    'profile_id' => NULL,
    'server_key' => NULL,
    'currency' => NULL,
    'region' => NULL,
  ),
  'payfast' => 
  array (
    'testing' => true,
    'merchant' => 
    array (
      'merchant_id' => '10000100',
      'merchant_key' => '46f0cd694581a',
      'return_url' => 'http://your-domain.co.za/success',
      'cancel_url' => 'http://your-domain.co.za/cancel',
      'notify_url' => 'http://your-domain.co.za/itn',
    ),
    'hosts' => 
    array (
      0 => 'www.payfast.co.za',
      1 => 'sandbox.payfast.co.za',
      2 => 'w1w.payfast.co.za',
      3 => 'w2w.payfast.co.za',
    ),
    'passphrase' => NULL,
  ),
  'location' => 
  array (
    'driver' => 'Stevebauman\\Location\\Drivers\\IpApi',
    'fallbacks' => 
    array (
      0 => 'Stevebauman\\Location\\Drivers\\Ip2locationio',
      1 => 'Stevebauman\\Location\\Drivers\\IpInfo',
      2 => 'Stevebauman\\Location\\Drivers\\GeoPlugin',
      3 => 'Stevebauman\\Location\\Drivers\\MaxMind',
    ),
    'position' => 'Stevebauman\\Location\\Position',
    'http' => 
    array (
      'timeout' => 3,
      'connect_timeout' => 3,
    ),
    'testing' => 
    array (
      'ip' => '66.102.0.0',
      'enabled' => true,
    ),
    'maxmind' => 
    array (
      'license_key' => NULL,
      'web' => 
      array (
        'enabled' => false,
        'user_id' => NULL,
        'options' => 
        array (
          'host' => 'geoip.maxmind.com',
        ),
      ),
      'local' => 
      array (
        'type' => 'city',
        'path' => '/Users/alialalawi/Sites/localhost/asaas/core/database/maxmind/GeoLite2-City.mmdb',
        'url' => 'https://download.maxmind.com/app/geoip_download_by_token?edition_id=GeoLite2-City&license_key=&suffix=tar.gz',
      ),
    ),
    'ip_api' => 
    array (
      'token' => NULL,
    ),
    'ipinfo' => 
    array (
      'token' => NULL,
    ),
    'ipdata' => 
    array (
      'token' => NULL,
    ),
    'ip2locationio' => 
    array (
      'token' => NULL,
    ),
    'kloudend' => 
    array (
      'token' => NULL,
    ),
  ),
  'attributes' => 
  array (
    'name' => 'Attributes',
  ),
  'badge' => 
  array (
    'name' => 'Badge',
  ),
  'blog' => 
  array (
    'name' => 'Blog',
  ),
  'campaign' => 
  array (
    'name' => 'Campaign',
  ),
  'cloudstorage' => 
  array (
    'name' => 'CloudStorage',
  ),
  'countrymanage' => 
  array (
    'name' => 'CountryManage',
  ),
  'couponmanage' => 
  array (
    'name' => 'CouponManage',
  ),
  'cpanelautomation' => 
  array (
    'name' => 'CpanelAutomation',
  ),
  'digitalproduct' => 
  array (
    'name' => 'DigitalProduct',
  ),
  'domainreseller' => 
  array (
    'name' => 'DomainReseller',
  ),
  'integrations' => 
  array (
    'name' => 'Integrations',
  ),
  'inventory' => 
  array (
    'name' => 'Inventory',
  ),
  'mobileapp' => 
  array (
    'name' => 'MobileApp',
  ),
  'newsletter' => 
  array (
    'name' => 'NewsLetter',
  ),
  'pluginmanage' => 
  array (
    'name' => 'PluginManage',
  ),
  'pos' => 
  array (
    'name' => 'Pos',
  ),
  'product' => 
  array (
    'name' => 'Product',
  ),
  'refundmodule' => 
  array (
    'name' => 'RefundModule',
  ),
  'salesreport' => 
  array (
    'name' => 'SalesReport',
  ),
  'service' => 
  array (
    'name' => 'Service',
  ),
  'shippingmodule' => 
  array (
    'name' => 'ShippingModule',
  ),
  'shippingplugin' => 
  array (
    'name' => 'ShippingPlugin',
  ),
  'siteanalytics' => 
  array (
    'name' => 'SiteAnalytics',
  ),
  'sitewaypaymentgateway' => 
  array (
    'name' => 'SiteWayPaymentGateway',
  ),
  'smsgateway' => 
  array (
    'name' => 'SmsGateway',
  ),
  'supportticket' => 
  array (
    'name' => 'SupportTicket',
  ),
  'taxmodule' => 
  array (
    'name' => 'TaxModule',
  ),
  'thememanage' => 
  array (
    'name' => 'ThemeManage',
  ),
  'wallet' => 
  array (
    'name' => 'Wallet',
  ),
  'webhook' => 
  array (
    'name' => 'WebHook',
  ),
  'woocommerce' => 
  array (
    'name' => 'WooCommerce',
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
