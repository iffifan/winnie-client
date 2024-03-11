<?php

namespace Iffifan\WinnieClient;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class WinnieClient
{

    protected Application $app;
    protected Factory $httpClient;
    protected ?string $token = null;

    /**
     * @param   \Illuminate\Http\Client\Factory  $httpClient
     */
    public function __construct(Application $app, Factory $httpClient)
    {
        $this->app        = $app;
        $this->httpClient = $httpClient;
    }


    public function getHttpClient(): Factory
    {
        return $this->httpClient;
    }

    public function makeRequest(): PendingRequest
    {
        $request =  $this->getHttpClient()
            ->baseUrl($this->getHost())
            ->acceptJson()
            ->asJson();
        if ($this->token) {
            $request = $request->withToken($this->token);
        }
        return $request;
    }

    public function withToken(string $token): WinnieClient
    {
        $this->token = $token;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function post(string $path, $data = []): Response
    {
        return $this->makeRequest()->asForm()->post($path, $data);
    }

    public function get(string $path, $query = []): Response
    {
        return $this->makeRequest()->get($path, $query);
    }

    public function patch(string $path, $data = []): Response
    {
        return $this->makeRequest()->patch($path, $data);
    }

    public function put(string $path, $data = []): Response
    {
        return $this->makeRequest()->put($path, $data);
    }

    public function delete(string $path, $data = []): Response
    {
        return $this->makeRequest()->delete($path, $data);
    }


    public function getClientID()
    {
        return $this->app['config']->get('winnie-client.client_id');
    }

    public function getClientSecret()
    {
        return $this->app['config']->get('winnie-client.client_secret');
    }

    public function getClientRedirectURL()
    {
        return $this->app['config']->get('winnie-client.redirect');
    }

    public function getHost()
    {
        return $this->app['config']->get('winnie-client.host');
    }

    public function fromBaseURL(string $path): string
    {
        return $this->getHost().$path;
    }

    public function getUser()
    {
        return $this->get('/api/auth/me')->json('data');
    }
}
