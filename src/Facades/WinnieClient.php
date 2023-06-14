<?php

namespace Iffifan\WinnieClient\Facades;

use Illuminate\Support\Facades\Facade;

class WinnieClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Iffifan\WinnieClient\WinnieClient::class;
    }

}
