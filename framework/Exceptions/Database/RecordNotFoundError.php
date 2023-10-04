<?php

namespace Framework\Exceptions\Database;

class RecordNotFoundError extends \RuntimeException {
    public function __construct($message = "Record not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}