<?php

namespace Framework\Http\Middleware;

use Fruitcake\Cors\CorsService;
use Closure;

/**
 * Class HandleCors
 *
 * Middleware to handle Cross-Origin Resource Sharing (CORS) requests.
 * This class manages the CORS settings and handles preflight and actual requests accordingly.
 *
 * @package Framework\Http\Middleware
 */
class HandleCors
{
    /**
     * The CORS service instance.
     *
     * @var CorsService
     */
    protected $cors;

    /**
     * HandleCors constructor.
     *
     * Initializes the HandleCors middleware and sets up the CORS service.
     */
    public function __construct()
    {
        $this->cors = new CorsService();
    }

    /**
     * Handle an incoming request.
     *
     * This method processes the incoming request, checking if it matches the CORS paths configuration.
     * If it's a preflight request, it handles it accordingly; otherwise, it proceeds with the actual request.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \Closure $next The next middleware to handle the request.
     * @return mixed The HTTP response after handling CORS.
     */
    public function handle($request, Closure $next)
    {
        if (!$this->hasMatchingPath($request)) return $next($request);

        $this->cors->setOptions(config('cors') ?? []);

        if ($this->cors->isPreflightRequest($request)) {
            $response = $this->cors->handlePreflightRequest($request);

            $this->cors->varyHeader($response, 'Access-Control-Request-Method');

            return $response;
        }

        $response = $next($request);

        if ($request->getMethod() === 'OPTIONS') {
            $this->cors->varyHeader($response, 'Access-Control-Request-Method');
        }

        return $this->cors->addActualRequestHeaders($response, $request);
    }

    /**
     * Check if the request matches any configured CORS paths.
     *
     * This method verifies if the incoming request URL matches any of the paths defined in the CORS configuration.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return bool True if the request matches a CORS path, false otherwise.
     */
    protected function hasMatchingPath($request)
    {
        $paths = config('cors.paths') ?? [];

        foreach($paths as $path) {
            if ($path !== '/') $path = trim($path, '/');

            if ($request->fullUrlIs($path) || $request->is($path)) return true;
        }

        return false;
    }
}