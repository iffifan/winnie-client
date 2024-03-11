<?php

namespace Iffifan\WinnieClient\Http\Middleware;

use Iffifan\WinnieClient\Models\User;
use Closure;
use Iffifan\WinnieClient\Facades\WinnieClient;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWinnieUser
{
    protected WinnieClient $winnieClient;

    /**
     * Handle an incoming request.
     *
     * @param   Request                       $request
     * @param   Closure(Request): (Response)  $next
     * @param   string                        $role
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {
        $user = $request->user()->winnie ?? WinnieClient::withToken($request->user()->access_token)->getUser();

        if (is_array($user) && array_key_exists('roles', $user)) {
            $user = $request->user()->setWinnieUser($user);
        } elseif ($user instanceof Authenticatable) {
            $user = $user->winnie;
        }

        if ($user instanceof User) {
            if ( ! $user->hasRole($role)) {
                $this->throwException($request);
            }
        } else {
            $this->throwException($request);
        }

        return $next($request);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function throwException(Request $request): void
    {
        throw new AuthorizationException(
            $request->user()->name.' does not have access to '.$request->path()
        );
    }

}
