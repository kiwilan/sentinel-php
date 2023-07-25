<?php

namespace Kiwilan\Sentinel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|false register(\Throwable $e) Register exception in Sentinel, `false` if Sentinel is disabled
 * @method static ?string token() Get Sentinel application token
 * @method static ?string host() Get Sentinel host
 * @method static bool enabled() Know if Sentinel is enabled
 * @method static int status() Get response status
 * @method static array payload() Get response payload
 * @method static string message() Get response message
 * @method static ?LogHandler error() Get LogHandler instance
 * @method static ?string user() Get authenticated user
 * @method static array toArray() Get Sentinel instance as array
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
