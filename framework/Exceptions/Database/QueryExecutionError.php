<?php

namespace Framework\Exceptions\Database;

class QueryExecutionError extends \RuntimeException {
    public function __construct($message = "Query execution error", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}