<?php

namespace Iffifan\WinnieClient;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class WinnieClient
{

    protected Application $app;
    protected Factory $httpClient;

    /**
     * @param   \Illuminate\Http\Client\Factory  $httpClient
     */
    public function __construct(Application $app, Factory $httpClient)
    {
        $this->app = $app;
        $this->httpClient = $httpClient;
    }


    public function getHttpClient(): Factory
    {
        return $this->httpClient;
    }

    public function post(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->httpClient->asForm()->post($url, $data);
    }
    public function get(string $url, $query = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->httpClient->get($url, $query);
    }

    public function patch(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->httpClient->get($url, $data);
    }

    public function put(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->httpClient->get($url, $data);
    }

    public function delete(string $url, $data = []): Response
    {
        $url = $this->fromBaseURL($url);

        return $this->httpClient->get($url, $data);
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
