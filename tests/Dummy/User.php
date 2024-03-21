<?php

namespace Iffifan\WinnieClient\Tests\Dummy;

use Iffifan\WinnieClient\Models\Traits\HasWinnieUser;
use Iffifan\WinnieClient\Models\User as WinnieUser;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasWinnieUser;

    public static function updateOrCreate(array $criteria, array $data): User
    {
        return new User();
    }
}
