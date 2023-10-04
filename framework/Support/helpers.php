<?php

/**
 * Helper Function: env
 *
 * Retrieve the value of an environment variable.
 *
 * @param string $key     The name of the environment variable.
 * @param mixed  $default The default value to return if the environment variable is not set.
 *
 * @return mixed The value of the environment variable or the default value if not set.
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

/**
 * Helper Function: app
 *
 * Get the application instance (Singleton).
 *
 * @return Framework\Http\Application The application instance.
 */
if (!function_exists('app')) {
    function app() {
        return Framework\Http\Application::getInstance();
    }
}

/**
 * Helper Function: router
 *
 * Get the router instance from the application.
 *
 * @return Framework\Http\Router The router instance.
 */
if (!function_exists('router')) {
    function router() {
        return app()->getRouter();
    }
}

/**
 * Helper Function: kernel
 *
 * Get the kernel instance from the application.
 *
 * @return Framework\Http\Kernel The kernel instance.
 */
if (!function_exists('kernel')) {
    function kernel() {
        return app()->getKernel();
    }
}

/**
 * Helper Function: request
 *
 * Get the current HTTP request instance from the application.
 *
 * @return Symfony\Component\HttpFoundation\Request The current HTTP request instance.
 */
if (!function_exists('request')) {
    function request() {
        return kernel()->getRequest();
    }
}

/**
 * Helper Function: base_path
 *
 * Get the current HTTP request instance from the application.
 *
 * @return Symfony\Component\HttpFoundation\Request The current HTTP request instance.
 */
if (!function_exists('base_path')) {
    function base_path($path = '') {
        return app()->getBasePath($path);
    }
}

/**
 * Helper Function: load
 *
 * Load a PHP file if it exists.
 *
 * @param string $filename The name of the PHP file to load.
 *
 * @throws Exception If the file does not exist.
 */
if (!function_exists('load')) {
    function load($filename) {
        if (!file_exists($filename))
            throw new Exception($filename.' does not exist.');
        else
            require_once($filename);
    }
}

/**
 * Helper Function: logger
 *
 * Get the exception handler logger instance from the application.
 *
 * @return Logger The exception handler logger instance.
 */
if (!function_exists('logger')) {
    function logger() {
        return app()->getExceptionHandler(true);
    }
}
