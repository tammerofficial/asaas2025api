<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Debugbar Settings
     |--------------------------------------------------------------------------
     |
     | Debugbar is enabled by default, when APP_DEBUG is true.
     | You can override the value by setting enable to true or false instead of null.
     |
     */

    'enabled' => env('DEBUGBAR_ENABLED', null),

    /*
     |--------------------------------------------------------------------------
     | Storage settings
     |--------------------------------------------------------------------------
     |
     | DebugBar stores data for session/ajax requests.
     | You can disable this, so the debugbar stores data in headers/session,
     | but this can cause problems with large data collectors.
     | By default, file storage (in the storage folder) is used. Redis can be used.
     |
     | Supported: "file", "redis", "custom"
     |
     */
    'storage' => [
        'enabled'    => true,
        'open'       => env('DEBUGBAR_STORAGE_OPEN', null),
        'driver'     => 'file', // redis, file, custom
        'path'       => storage_path('debugbar'), // For file driver
        'connection' => null,   // Leave null for default connection (Redis/DB)
        'provider'   => '', // Instance of StorageInterface for custom driver
        'hostname'   => '127.0.0.1', // Redis/Database hostname
        'port'       => 2304, // Redis port
    ],

    /*
     |--------------------------------------------------------------------------
     | Vendors
     |--------------------------------------------------------------------------
     |
     | Vendor files are included by default, but can be set to false.
     | This can also be set to 'js' or 'css', to only include javascript or css vendor files.
     | Vendors are minified before being included.
     |
     */
    'include_vendors' => true,

    /*
     |--------------------------------------------------------------------------
     | Capture Ajax Requests
     |--------------------------------------------------------------------------
     |
     | The Debugbar can capture Ajax requests and display them.
     | If you disable this, it will only show the main request.
     |
     */

    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'ajax_handler_auto_show' => true,
    'ajax_handler_enable_tab' => true,

    /*
     |--------------------------------------------------------------------------
     | DataCollectors
     |--------------------------------------------------------------------------
     |
     | Enable/disable DataCollectors
     |
     */

    'collectors' => [
        'phpinfo'         => false,  // Php version
        'messages'         => true,   // Messages
        'time'             => true,   // Time Datalogger
        'memory'           => true,   // Memory usage
        'exceptions'       => true,   // Exception display
        'log'              => true,   // Logs from Monolog (merged in messages)
        'db'               => true,   // Show database (PDO) queries and bindings
        'views'            => true,   // Views with their data
        'route'            => false,  // Current route information
        'auth'             => false,  // Laravel authentication status
        'gate'             => true,   // Laravel Gate checks
        'session'          => false,  // Display session data
        'symfony_request'  => true,   // Only one can be enabled..
        'mail'             => true,   // Catch mail messages
        'laravel'          => true,   // Laravel version and environment
        'events'           => false,  // All events fired
        'default_request'  => false,  // Regular or special Symfony request logger
        'logs'             => false,  // Add the latest log messages
        'files'            => false,  // Show the included files
        'config'           => false,  // Display config settings
        'cache'             => false,  // Display cache events
        'models'            => true,   // Display models
        'livewire'          => true,   // Display Livewire (when available)
        'jobs'              => false,  // Display dispatched jobs
        'pennant'           => false,  // Display Pennant checks (when available)
    ],

    /*
     |--------------------------------------------------------------------------
     | Inject Debugbar in Response
     |--------------------------------------------------------------------------
     |
     | Usually, the debugbar is added just before </body>, by listening to the
     | Response after the App is done. If you disable this, you have to add them
     | in your template yourself. See http://phpdebugbar.com/docs/rendering.html
     |
     */
    'inject' => true,

    /*
     |--------------------------------------------------------------------------
     | DebugBar route prefix
     |--------------------------------------------------------------------------
     |
     | Sometimes you want to set route prefix to be used by DebugBar to load
     | its resources. Usually the need comes from misconfigured web server or
     | from trying to overcome bugs like this: http://trac.nginx.org/nginx/ticket/97
     |
     */
    'route_prefix' => '_debugbar',

    /*
     |--------------------------------------------------------------------------
     | DebugBar route domain
     |--------------------------------------------------------------------------
     |
     | By default DebugBar route served from the same domain that request served.
     | To override default domain, specify it as a non-empty value.
     |
     */
    'route_domain' => null,

    /*
     |--------------------------------------------------------------------------
     | DebugBar route middleware
     |--------------------------------------------------------------------------
     |
     | Optional middleware to execute on DebugBar routes
     |
     */
    'route_middleware' => [],

    /*
     |--------------------------------------------------------------------------
     | DebugBar theme
     |--------------------------------------------------------------------------
     |
     | Switches between light and dark theme. Set to 'auto' to respect system preferences
     |
     | Supported: "light", "dark", "auto"
     |
     */
    'theme' => env('DEBUGBAR_THEME', 'auto'),

    /*
     |--------------------------------------------------------------------------
     | Backtrace stack limit
     |--------------------------------------------------------------------------
     |
     | By default, the DebugBar limits the number of frames returned by the 'debug_backtrace()' function.
     | If you need larger stack traces, you can increase this number.
     | Setting it to 0 will result in no limit.
     |
     */
    'debug_backtrace_limit' => 50,
];

