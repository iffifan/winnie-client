<?php

namespace Iffifan\WinnieClient;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class WinnieClient
{

    protected Application $app;
    protected Factory $httpClient;
    protected string $token;

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

    public function makeRequest(): Factory
    {
        $request =  $this->getHttpClient()
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

    public function post(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->makeRequest()->asForm()->post($url, $data);
    }

    public function get(string $url, $query = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->makeRequest()->get($url, $query);
    }

    public function patch(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->makeRequest()->get($url, $data);
    }

    public function put(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->makeRequest()->get($url, $data);
    }

    public function delete(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->makeRequest()->get($url, $data);
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

    public function fromBaseURL(string $url): string
    {
        return $this->getHost().$url;
    }
}
