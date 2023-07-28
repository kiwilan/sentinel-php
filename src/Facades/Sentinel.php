<?php

namespace Kiwilan\Sentinel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self make() Get Sentinel instance.
 * @method static array|false register(\Throwable $e, bool $throwErrors = false) Register exception in Sentinel, return `false` if Sentinel is disabled. If you want to throw Sentinel errors for debug, set `$throwErrors` to `true`.
 * @method static ?string token() Get Sentinel application token.
 * @method static ?string host() Get Sentinel host.
 * @method static bool enabled() Know if Sentinel is enabled.
 * @method static bool throwErrors() Know if Sentinel is throwing errors.
 * @method static int status() Get response status.
 * @method static array payload() Get response payload.
 * @method static array response() Get response.
 * @method static array body() Get response body.
 * @method static string message() Get response message.
 * @method static ?string json() Get response json.
 * @method static ?LogHandler error() Get LogHandler instance.
 * @method static ?string user() Get authenticated user.
 * @method static array toArray() Get Sentinel instance as array.
 *
 * @see \Kiwilan\Sentinel\Sentinel::class
 */
class Sentinel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sentinel';
    }
}
