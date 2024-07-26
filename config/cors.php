<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | This option defines the paths that should be subject to CORS. The wildcard
    | symbol (*) can be used to apply CORS to all paths, or you can specify
    | individual paths or patterns.
    |
    | Examples:
    | - ['*'] - Allow CORS on all paths.
    | - ['api/*'] - Allow CORS on all paths starting with "api/".
    |
    */
    'paths' => ['api/*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | This option defines the HTTP methods that are allowed for CORS requests.
    | The wildcard symbol (*) can be used to allow all methods, or you can
    | specify individual methods.
    |
    | Examples:
    | - ['*'] - Allow all HTTP methods.
    | - ['GET', 'POST'] - Allow only GET and POST methods.
    |
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | This option defines the origins that are allowed to make CORS requests.
    | The wildcard symbol (*) can be used to allow all origins, or you can
    | specify individual origins.
    |
    | Examples:
    | - ['*'] - Allow all origins.
    | - ['https://example.com'] - Allow only https://example.com.
    |
    */
    'allowed_origins' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify patterns that can be used to match
    | against origins for CORS requests. This is useful when you want to allow
    | subdomains or dynamic origins.
    |
    | Examples:
    | - [] - No patterns, allow specific origins only.
    | - ['*.example.com'] - Allow any subdomain of example.com.
    |
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | This option defines the headers that are allowed in CORS requests. The
    | wildcard symbol (*) can be used to allow all headers, or you can specify
    | individual headers.
    |
    | Examples:
    | - ['*'] - Allow all headers.
    | - ['Content-Type', 'X-Requested-With'] - Allow only specified headers.
    |
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | This option defines the headers that are exposed to the client in the
    | response of a CORS request.
    |
    | Examples:
    | - [] - No headers are exposed by default.
    | - ['Authorization', 'X-Custom-Header'] - Expose specified headers.
    |
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | This option defines the maximum age (in seconds) for which the results
    | of a preflight request can be cached by the client. A value of 0 means
    | no caching.
    |
    | Examples:
    | - 0 - No caching of preflight request results.
    | - 3600 - Cache preflight request results for 1 hour.
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | This option indicates whether the response to the request can be exposed
    | when the credentials flag is true. When used in conjunction with credentials,
    | this flag must be enabled.
    |
    | Examples:
    | - false - Do not support credentials.
    | - true - Support credentials.
    |
    */
    'supports_credentials' => false,

];