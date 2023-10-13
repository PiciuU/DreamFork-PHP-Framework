<?php

namespace Framework\Exceptions\Filesystem;

class ResourceAlreadyExists extends \RuntimeException {
    public function __construct($message = "Resource already exists.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}