<?php

namespace Framework\Services\Auth\Middleware;

use Framework\Support\Facades\Auth;
use Framework\Services\Auth\Exceptions\AuthenticationException;

use Closure;

/**
 * Class Authenticate
 *
 * The Authenticate class is a middleware responsible for authenticating incoming requests.
 * It checks if the user is authenticated using the specified guard and throws an AuthenticationException if authentication fails.
 *
 * @package Framework\Services\Auth\Middleware
 */
class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure  $next The next middleware in the pipeline.
     * @return mixed Returns the result of the next middleware or throws an AuthenticationException.
     */
    public static function handle(Closure $next)
    {
        return Auth::guard('api')->check();
    }
}