<?php

namespace Iffifan\WinnieClient\Services;

use Iffifan\WinnieClient\Exceptions\OAuthException;
use Exception;
use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
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
    public function getAccessToken(Request $request): array
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
     * @param  string  $token
     *
     * @return array
     * @throws Exception
     */
    public function revokeAccessToken(string $token): array
    {
        $response = $this->winnieClient->withToken($token)
                                       ->post($this->makeLogoutURL());
        if ($response->failed()) {
            throw new Exception('Token Revocation Failed!'.$response->body());
        }

        return $response->json();
    }

    public function getClientCredentialsToken(): string
    {
        $response = $this->winnieClient->post($this->makeTokenURL(), $this->getClientCredentialsGrantFields());
        if ($response->failed()) {
            throw new Exception('Client Credentials Grant Request Failed!');
        }

        return $response->json()['access_token'];
    }

    /**
     * @throws \Illuminate\Http\Client\HttpClientException
     */
    public function getUserFromAccessToken(string $accessToken): array
    {
        $response = $this->winnieClient->withToken($accessToken)->get('/api/auth/me');
        if ($response->status() !== 200) {
            throw new HttpClientException();
        }

        return $response->json('data');
    }

    /**
     * @throws Exception
     */
    public function refreshAccessToken(string $refresh_token): array
    {
        $response = $this->winnieClient->post($this->makeTokenURL(), $this->getRefreshTokenFields($refresh_token));
        if ($response->failed()) {
            throw new Exception('Refresh Token Request Failed!');
        }

        return $response->json();
    }

    /**
     *
     * @return string
     */
    public function makeAuthorizationURL(): string
    {
        $url         = $this->winnieClient->fromBaseURL(self::AUTH_PATH);
        $queryParams = http_build_query($this->getCodeFields(), '', '&', $this->encodingType);

        return $url.'?'.$queryParams;
    }

    public function makeTokenURL(): string
    {
        return self::TOKEN_PATH;
    }

    public function makeLogoutURL(): string
    {
        return $this->winnieClient->fromBaseURL('/api/auth/logout');
    }

    private function getCodeFields(): array
    {
        return [
            'client_id'     => $this->winnieClient->getClientID(),
            'redirect_uri'  => $this->winnieClient->getClientRedirectURL(),
            'response_type' => 'code',
            'scope'         => '',
            'prompt'        => 'login'
        ];
    }

    private function getTokenFields(string $authCode): array
    {
        return [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->winnieClient->getClientID(),
            'client_secret' => $this->winnieClient->getClientSecret(),
            'redirect_uri'  => $this->winnieClient->getClientRedirectURL(),
            'code'          => $authCode,
        ];
    }

    private function getClientCredentialsGrantFields(): array
    {
        return [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->winnieClient->getClientID(),
            'client_secret' => $this->winnieClient->getClientSecret(),
            'scope'         => '*'
        ];
    }

    private function getRefreshTokenFields(string $refresh_token): array
    {
        return [
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->winnieClient->getClientID(),
            'client_secret' => $this->winnieClient->getClientSecret(),
            'refresh_token' => $refresh_token,
            'scope'         => '',
        ];
    }

}
