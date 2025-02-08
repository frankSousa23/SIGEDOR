<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default session "driver" that will be used on
    | your system. By default, we will use the lightweight native PHP session
    | handler backed by the file system. Of course, other great drivers are
    | available for you to use.
    |
    | Supported: "file", "cookie", "database", "apc",
    |            "memcached", "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If you want them
    | to be permanently active, set the value to 'null'. This should
    | be measured in minutes.
    |
    */

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => true,

    /*
    |--------------------------------------------------------------------------
    | Session Files Location
    |--------------------------------------------------------------------------
    |
    | When using the "file" session driver, we need a location where the
    | session files may be stored. A default has been created for you
    | which works great on most systems.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you should configure the
    | database connection that should be used to store your sessions. Of
    | course, a database table will need to be created first using the
    | `php artisan session:table` command.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the table
    | that should be used to store your sessions.
    |
    */

    'table' => 'sessions',

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify whether the session cookies should
    | be encrypted. If encryption is enabled, all session data will be
    | encrypted by Laravel.
    |
    */

    'encrypt' => true,

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the cookie that will be used to identify
    | a session instance. The name should be unique for each application.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The session cookie path determines the path for which the cookie will
    | be regarded as valid. Typically, this will be the root path of
    | your application but you are free to change this when necessary.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Here you may change the domain of the cookie that will be used to
    | identify a session instance. This is useful for applications that
    | are running on subdomains.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Session Secure Cookie
    |--------------------------------------------------------------------------
    |
    | When set to true, the session cookie will only be sent back to the
    | server if the browser has a HTTPS connection. This setting should
    | always be set to true in production environments.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Session HTTP Only
    |--------------------------------------------------------------------------
    |
    | When set to true, the session cookie will be only accessible via
    | the HTTP protocol and not JavaScript.
    |
    */

    'http_only' => true,

    /*
    |--------------------------------------------------------------------------
    | Configuración de Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Esta opción determina cuán estrictas serán las cookies de la sesión al
    | enviarse junto con las peticiones, útil para ambientes de desarrollo.
    |
    */
    'same_site' => 'lax',

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will tie the cookie to the top-level site for
    | a cross-site context. Partitioned cookies are accepted by the browser
    | when flagged "secure" and the Same-Site attribute is set to "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | When using one of the framework's cache driven session backends, you may
    | define the cache store which should be used to store the session data
    | between requests. This must match one of your defined cache stores.
    |
    | Affects: "apc", "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Some session drivers must manually sweep their storage location to get
    | rid of old sessions from storage. Here are the chances that it will
    | happen on a given request. By default, the odds are 2 out of 100.
    |
    */

    'lottery' => [2, 100],

];
