<?php

namespace Framework\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;

/**
 * Class Kernel
 *
 * The kernel of the framework, responsible for handling incoming HTTP requests and managing application flow.
 * This class acts as the central coordinator, processing requests and sending responses.
 *
 * @package Framework\Http
 */
class Kernel
{
    /**
     * The timestamp when the current request started.
     *
     * @var Carbon\Carbon|null
     */
    private $requestStartedAt;

    /**
     * The current HTTP request instance.
     *
     * @var Symfony\Component\HttpFoundation\Request|null
     */
    private $request;

    /**
     * Kernel constructor.
     *
     * Initializes the Kernel instance and sets up the router.
     */
    public function __construct() {
        app()->make(Router::class);
    }

    /**
     * Get the current HTTP request instance.
     *
     * @return Symfony\Component\HttpFoundation\Request|null The current HTTP request instance.
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Handle the incoming HTTP request.
     *
     * @param Symfony\Component\HttpFoundation\Request $request The incoming HTTP request.
     * @return Symfony\Component\HttpFoundation\Response The HTTP response.
     */
    public function handle(Request $request)
    {
        $this->requestStartedAt = Carbon::now();
        $this->request = $request;

        try {
            $response = $this->dispatchToRouter($this->request);
        }
        catch (Throwable $e) {
            echo "<br>CRITICAL ERROR: $e<br>";
        }

        return $response;
    }

    /**
     * Dispatch the HTTP request to the router for processing.
     *
     * @param Symfony\Component\HttpFoundation\Request $request The incoming HTTP request.
     * @return mixed The response returned by the router.
     */
    protected function dispatchToRouter($request) {
        return router()->dispatch($request);
    }

    /**
     * Terminate the request and send the HTTP response.
     *
     * @param Symfony\Component\HttpFoundation\Request $request The incoming HTTP request.
     * @param Symfony\Component\HttpFoundation\Response $response The HTTP response.
     */
    public function terminate(Request $request, Response $response)
    {
        $requestEndedAt = Carbon::now();

        $executionTimeInMilliseconds = $this->requestStartedAt->diffInMilliseconds($requestEndedAt);

        $response->setContent(json_encode([
            'execution_time' => $executionTimeInMilliseconds." ms"
        ]))->send();

        exit;
    }
}