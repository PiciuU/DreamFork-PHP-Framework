<?php

namespace Framework\Exceptions;

use Framework\Log\Logger;
use Framework\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ExceptionHandler
 *
 * The ExceptionHandler class is responsible for handling exceptions in the application.
 * It reports exceptions based on their types and log levels, and provides context information.
 *
 * @package Framework\Exceptions
 */
class ExceptionHandler extends Logger
{
    /**
     * Mapping of exception types to log levels.
     *
     * @var array
     */
    private $levels = [];

    /**
     * List of exceptions that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Create a new ExceptionHandler instance.
     */
    public function __construct() {
        set_exception_handler([$this, 'register']);
    }

    /**
     * Report the given exception.
     *
     * @param Throwable $e The exception to report.
     */
    public function reportable($e)
    {
        $this->report($e);
    }

    /**
     * Log a Throwable exception.
     *
     * @param Throwable $e The exception to log.
     */
    public function loggable($e)
    {
        $this->logThrowable($e);
    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $e The exception to report or log.
     */
    public function report(Throwable $e) {
        if ($this->shouldntReport($e)) return;

        $this->reportThrowable($e);
    }

    /**
     * Determine if the exception should not be reported.
     *
     * @param Throwable $e The exception to check.
     * @return bool True if the exception should not be reported, otherwise false.
     */
    protected function shouldntReport(Throwable $e) {
        $isExceptionInDontReport = false;

        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                $isExceptionInDontReport = true;
                break;
            }
        }

        if ($isExceptionInDontReport) {
            return true;
        }

        return false;
    }

    /**
     * Report and log a Throwable exception.
     *
     * This method is responsible for handling the given Throwable exception.
     * It logs it using logThrowable method, sends an HTTP response with
     * the appropriate status code and headers, and renders the exception for display.
     *
     * @param Throwable $e The exception to report or log.
     */
    protected function reportThrowable(Throwable $e)
    {
        $this->logThrowable($e);

        $response = new Response(
            null,
            $this->getStatusCode($e),
            $this->getHeaders($e)
        );

        $response->send();

        $this->renderException($this->buildExceptionContext($e));

        kernel()->terminate(request(), $response);
    }

    /**
     * Log a Throwable exception.
     *
     * This method is responsible for determining the log level for the given Throwable exception,
     * building the context for logging, and then logging the exception message with the associated
     * log level. If the log level method does not exist in the class, it falls back to the generic
     * log method.
     *
     * @param Throwable $e The exception to log.
     */
    protected function logThrowable(Throwable $e)
    {
        $level = null;

        foreach ($this->levels as $type => $logLevel) {
            if ($e instanceof $type) {
                $level = $logLevel;
                break;
            }
        }

        if (is_null($level)) {
            $level = LogLevel::ERROR;
        }

        $context = $this->buildExceptionContext($e);

        method_exists($this, $level)
        ? $this->{$level}($e->getMessage(), $context)
        : $this->log($level, $e->getMessage(), $context);
    }

    /**
     * Render an exception.
     *
     * @param array $context The exception to render.
     */
    protected function renderException(array $context)
    {
        try {
            return config('app.debug')
                        ? $this->renderExceptionWithDebugRenderer($context)
                        : $this->renderExceptionWithSimpleRenderer($context['exception']);
        } catch (Throwable $e) {
            return $this->renderExceptionWithSimpleRenderer($e);
        }
    }

    /**
     * Render an exception using the debug renderer.
     *
     * @param array $context The exception to render.
     */
    protected function renderExceptionWithDebugRenderer(array $context)
    {
        return app(ExceptionRenderer::class)->renderDebug($context);
    }

    /**
     * Render an exception using a simple renderer.
     *
     * @param Throwable $e The exception to render.
     */
    protected function renderExceptionWithSimpleRenderer(Throwable $e)
    {
        return app(ExceptionRenderer::class)->render($this->getStatusCode($e));
    }

    /**
     * Get the HTTP status code for an exception.
     *
     * @param Throwable $e The exception to get the status code for.
     * @return int The HTTP status code.
     */
    protected function getStatusCode(Throwable $e) {
        return method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
    }

    /**
     * Get the HTTP headers for an exception.
     *
     * @param Throwable $e The exception to get the headers for.
     * @return array The HTTP headers.
     */
    protected function getHeaders(Throwable $e) {
        return method_exists($e, 'getHeaders') ? $e->getHeaders() : [];
    }

    /**
     * Build the context for the given exception.
     *
     * @param Throwable $e The exception for which to build context.
     * @return array The context information.
     */
    protected function buildExceptionContext(Throwable $e) {
        return array_merge(
            ['exception' => $e],
            ['exception_context' => $this->exceptionContext($e)],
            ['additional_context' => $this->context()]
        );
    }

    /**
     * Get the context for the given exception.
     *
     * @param Throwable $e The exception for which to get context.
     * @return array The context information.
     */
    protected function exceptionContext(Throwable $e) {
        if (method_exists($e, 'context')) {
            return $e->context();
        }

        return [];
    }

    /**
     * Get the additional context information.
     *
     * @return array The additional context information.
     */
    protected function context()
    {
        try {
            return array_filter([
                'userId' => Auth::id(),
            ]);
        } catch (Throwable) {
            return [];
        }
    }
}