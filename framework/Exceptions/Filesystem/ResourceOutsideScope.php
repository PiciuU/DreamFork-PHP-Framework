<?php

namespace Framework\Exceptions\Filesystem;

class ResourceOutsideScope extends \RuntimeException {
    public function __construct($message = "Resource is outside scope.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}