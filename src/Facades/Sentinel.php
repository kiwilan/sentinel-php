<?php

namespace Kiwilan\Sentinel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|false register(\Throwable $e, bool $throwErrors = false) Register exception in Sentinel, return `false` if Sentinel is disabled. If you want to throw Sentinel errors for debug, set `$throwErrors` to `true`.
 *
 * @see \Kiwilan\Sentinel\Sentinel
 */
class Sentinel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sentinel';
    }
}
