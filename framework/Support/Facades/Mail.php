<?php

namespace Framework\Support\Facades;

class Mail extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mail';
    }
}