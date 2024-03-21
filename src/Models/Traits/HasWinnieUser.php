<?php

namespace Iffifan\WinnieClient\Models\Traits;

use Iffifan\WinnieClient\Models\User as WinnieUser;

trait HasWinnieUser
{
    public ?WinnieUser $winnie = null;

    public function setWinnieUser($winnieUserData): WinnieUser
    {
        if ($winnieUserData instanceof WinnieUser) {
            $this->winnie = $winnieUserData;

            return $winnieUserData;
        }
        $winnieUser   = WinnieUser::makeFromArray($winnieUserData);
        $this->winnie = $winnieUser;

        return $winnieUser;
    }

    public function hasRole(string $role): bool
    {
        $user = $this->winnie;
        if ( ! ($user instanceof WinnieUser) || ! $user->getRoles()->count()) {
            return false;
        }

        return $this->winnie->hasRole($role);
    }
}
