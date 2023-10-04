<?php

namespace Framework\Exceptions\Database;

class ConnectionError extends \RuntimeException {
    public function __construct($message = "Database connection error", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}