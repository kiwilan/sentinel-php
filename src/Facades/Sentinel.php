<?php

namespace Kiwilan\Sentinel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\Sentinel\Sentinel
 */
class Sentinel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kiwilan\Sentinel\Sentinel::class;
    }
}
