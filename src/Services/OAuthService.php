<?php

namespace Iffifan\WinnieClient\Services;

use Iffifan\WinnieClient\Exceptions\OAuthException;
use Exception;
use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class OAuthService
{

    public const TOKEN_PATH = '/oauth/token';

    public const AUTH_PATH = '/oauth/authorize';

    protected int $encodingType = PHP_QUERY_RFC1738;

    protected WinnieClient $winnieClient;

    protected bool $usesState = false;

    public function __construct(WinnieClient $winnieClient)
    {
        $this->winnieClient = $winnieClient;
    }

    /**
     * @throws Throwable
     * @throws OAuthException
     */
    public function getAccessToken(Request $request)
    {
        $response = $this->winnieClient->post($this->makeTokenURL(), $this->getTokenFields($request->get('code')));
        if ($response->json('error')) {
            if ($response->json('hint') == 'Authorization code has expired'
            || $response->json('hint') == 'Authorization code has been revoked'
            ) {
                throw new OAuthException();
            }
            throw new Exception('Access Token Request Failed!');
        }

        return $response->json();
    }

    /**
     * @throws \Illuminate\Http\Client\HttpClientException
     */
    public function getUserFromAccessToken(string $accessToken)
    {
        $response = $this->winnieClient->withToken($accessToken)->get('/api/auth/me');
        if ($response->status() !== 200) {
            throw new HttpClientException();
        }

        return $response->json('data');
    }

    /**
     *
     * @return string
     */
    public function makeAuthorizationURL(): string
    {
        $url = $this->winnieClient->fromBaseURL(self::AUTH_PATH);
        return $url.'?'.http_build_query($this->getCodeFields(), '', '&', $this->encodingType);
    }

    public function makeTokenURL(): string
    {
        return self::TOKEN_PATH;
    }

    private function getCodeFields(): array
    {
        return [
            'client_id'     => $this->winnieClient->getClientID(),
            'redirect_uri'  => $this->winnieClient->getClientRedirectURL(),
            'response_type' => 'code',
            'scope'         => '',
        ];
    }

    private function getTokenFields(string $authCode)
    {
        return [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->winnieClient->getClientID(),
            'client_secret' => $this->winnieClient->getClientSecret(),
            'redirect_uri'  => $this->winnieClient->getClientRedirectURL(),
            'code'          => $authCode,
        ];
    }

}
