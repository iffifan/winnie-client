<?php

namespace Iffifan\WinnieClient\Http\Middleware;

use Closure;
use Iffifan\WinnieClient\Facades\WinnieClient;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class WinnieProxyAuth
{

    /**
     * Handle an incoming request.
     *
     * @param   Request                       $request
     * @param   Closure(Request): (Response)  $next
     * @param   string                        $role
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function handle(
        Request $request,
        Closure $next,
    ): Response
    {
        if (!$request->is('api/*')
            || !$request->expectsJson()
            || !($token = $this->isWinnieAccessToken($request))
        ) {
            return $next($request);
        }
        $user = WinnieClient::withToken($token)->getUser();
        throw_if(is_null($user), new AuthorizationException('Winnie User not found'));
        $checklistUser = $this->checklistUserAddOrUpdate($request, $user);
        if ($checklistUser) {
            auth()->login($checklistUser);
            $request->user()->setWinnieUser($user);
        }
        return $next($request);
    }

    private function isWinnieAccessToken(Request $request): bool|string
    {
        //TODO check if token is from Winnie Mobile APP
        $token = $request->bearerToken();
        if (!is_null($token) && Str::length($token) > 200) {
            return $token;
        }
        return false;
    }

    private function checklistUserAddOrUpdate(Request $request, array $user): bool|Authenticatable
    {
        if (array_key_exists('id', $user)
            && ($token = $this->isWinnieAccessToken($request))
        ) {
            $defaultGuard = config('auth.defaults.guard');
            $defaultProvider = config("auth.guards.$defaultGuard.provider");
            $model = config("auth.providers.$defaultProvider.model");
            return $model::updateOrCreate(['winnie_id' => $user['id']],
                [
                    'access_token' => $token,
                    'refresh_token' => '',
                    'expires_at' => now(),
                    'last_login' => now(),
                    'name' => $user['name'],
                    'email' => $user['email'],
                ]);
        }
        return false;
    }

}
