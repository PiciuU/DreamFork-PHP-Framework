<?php

namespace Framework\Exceptions;

use Framework\Log\Logger;
use Framework\Log\LogLevel;
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
    private $levels = [
        'Framework\Exceptions\Database\ConnectionError' => LogLevel::CRITICAL
    ];

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
    public function reportable($e) {
        $this->report($e);
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
     * Report or log the given exception.
     *
     * @param Throwable $e The exception to report or log.
     */
    protected function reportThrowable(Throwable $e) {
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

        // Add rendering engine for exceptions
    }

    /**
     * Build the context for the given exception.
     *
     * @param Throwable $e The exception for which to build context.
     * @return array The context information.
     */
    protected function buildExceptionContext(Throwable $e) {
        return array_merge(
            $this->exceptionContext($e),
            $this->context(),
            ['exception' => $e]
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