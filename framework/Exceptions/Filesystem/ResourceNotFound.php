<?php

namespace Framework\Exceptions\Filesystem;

class ResourceNotFound extends \RuntimeException {
    public function __construct($message = "Resource not found.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}