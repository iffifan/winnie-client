<?php

namespace Iffifan\WinnieClient\Tests;

use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\Response;
use Mockery;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \Iffifan\WinnieClient\ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('winnie-client', [
            'client_id'     => 'test',
            'client_secret' => 'test',
            'redirect'      => 'test',
            'host'          => 'test',
        ]);

        $app['config']->set('auth', [
            'defaults' => [
                'guard' => 'web',
                'passwords' => 'users',
            ],
            'guards' => [
                'web' => [
                    'driver'   => 'session',
                    'provider' => 'users',
                ],
            ],
            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model'  => \Iffifan\WinnieClient\Tests\Dummy\User::class,
                ],
            ],
        ]);

        $app['config']->set('database', [
            'default'     => 'sqlite',
            'connections' => [
                'sqlite' => [
                    'driver'   => 'sqlite',
                    'database' => ':memory:',
                    'prefix'   => '',
                ],
            ],
        ]);
    }

    protected function setUpWinnieClient(array $expectations = []): void
    {
        $winnieClient = Mockery::mock(WinnieClient::class);
        $winnieClient = $winnieClient->shouldReceive('withToken')
                                     ->andReturnSelf();
        foreach ($expectations as $expectation) {
            $winnieClient = $winnieClient->shouldReceive($expectation['method']);
            if (array_key_exists('times', $expectation)) {
                $winnieClient = $winnieClient->atLeast()->times(0)->atMost()->times($expectation['times']);
            } else {
                $winnieClient = $winnieClient->zeroOrMoreTimes();
            }
            if (array_key_exists('with', $expectation)) {
                if ($expectation['with'] === 'any') {
                    $winnieClient = $winnieClient->withAnyArgs();
                } elseif ($expectation['with'] instanceof \Closure) {
                    $winnieClient = $winnieClient->with(Mockery::on($expectation['with']));
                } elseif (is_array($expectation['with'])) {
                    $winnieClient = $winnieClient->withSomeOfArgs(...$expectation['with']);
                } else {
                    $winnieClient = $winnieClient->with($expectation['with']);
                }
            }
            if ($expectation['return'] instanceof \Exception) {
                $winnieClient = $winnieClient->andThrow($expectation['return']);
            } elseif ($expectation['return'] === 'self') {
                $winnieClient = $winnieClient->andReturnSelf();
            } elseif (isset($expectation['return']['response'])) {
                $winnieClient = $winnieClient->andReturn(
                    (new Response(
                        new \GuzzleHttp\Psr7\Response(
                            $expectation['return']['status'] ?? 200,
                            $expectation['return']['headers'] ?? [],
                            json_encode(
                                $expectation['return']['response'] ?? [],
                                JSON_THROW_ON_ERROR
                            )
                        )
                    ))
                );
            } else {
                $winnieClient = $winnieClient->andReturn($expectation['return']);
            }
        }
        $this->app->instance(WinnieClient::class, $winnieClient->getMock());
    }
}
