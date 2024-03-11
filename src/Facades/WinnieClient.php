<?php

namespace Iffifan\WinnieClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response get(string $url, $query = [])
 * @method static \Iffifan\WinnieClient\WinnieClient withToken(string $token)
 *
 * @see \Iffifan\WinnieClient\WinnieClient
 */
class WinnieClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Iffifan\WinnieClient\WinnieClient::class;
    }

}
