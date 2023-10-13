<?php

use Framework\Http\Container;

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
    function app($abstract = null, $parameters = []) {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
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
        return app('router');
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
        return app('kernel');
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
        return app()->basePath($path);
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
        return app('handler');
    }
}

/**
 * Helper Function: config
 *
 * Get or set configuration values.
 *
 * @param string|array|null $key     The configuration key or an array of key-value pairs to set.
 * @param mixed            $default The default value to return if the key is not found (for get operations).
 *
 * @return mixed The configuration value or the config repository instance (for set operations).
 */
if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

/**
 * Get the path to the storage folder.
 *
 * @param  string  $path
 * @return string
 */
if (!function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return app()->storagePath($path);
    }
}

/**
 * Throw the given exception if the given condition is true.
 *
 * @template TException of \Throwable
 *
 * @param  mixed  $condition
 * @param  TException|class-string<TException>|string  $exception
 * @param  mixed  ...$parameters
 * @return mixed
 *
 * @throws TException
 */
if (!function_exists('throw_if')) {
    function throw_if($condition, $exception = 'RuntimeException', ...$parameters)
    {
        if ($condition) {
            if (is_string($exception) && class_exists($exception)) {
                $exception = new $exception(...$parameters);
            }

            throw is_string($exception) ? new RuntimeException($exception) : $exception;
        }

        return $condition;
    }
}