<?php

namespace Iffifan\WinnieClient\Tests\Unit;

use Iffifan\WinnieClient\Tests\TestCase;
use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\PendingRequest;
use Mockery;

class WinnieClientTest extends TestCase
{
    public function testWithToken()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $return = $client->withToken('foo');
        $this->assertEquals($client, $return);
        $this->assertEquals('foo', $client->getToken());
    }

    public function testGetToken()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $client->withToken('token');
        $this->assertNotNull($client->getToken());
    }

    public function testGet()
    {
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
            ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                                         ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                                         ->withAnyArgs()
                                         ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('get')
            ->andReturn(new \Illuminate\Http\Client\Response(
                new \GuzzleHttp\Psr7\Response(200, [], json_encode(['foo' => 'bar']))
            ));

        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->get('test');
        $this->assertNotNull($response);
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function testPost()
    {
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                                         ->withAnyArgs()
                                         ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asForm')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('post')
            ->with('test', [])
                          ->andReturn(new \Illuminate\Http\Client\Response(
                            new \GuzzleHttp\Psr7\Response(200, [], json_encode(['foo' => 'bar']))
                          ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->post('test', []);
        $this->assertNotNull($response);
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function testPut()
    {
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                       ->withAnyArgs()
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('put')
                       ->with('test', [])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                            new \GuzzleHttp\Psr7\Response(200, [], json_encode(['foo' => 'bar']))
                       ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->put('test', []);
        $this->assertNotNull($response);
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function testDelete()
    {
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                       ->withAnyArgs()
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('delete')
                       ->with('test', [])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                           new \GuzzleHttp\Psr7\Response(200, [], json_encode(['foo' => 'bar']))
                       ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->delete('test', []);
        $this->assertNotNull($response);
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function testPatch()
    {
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                       ->withAnyArgs()
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('patch')
                       ->with('test', [])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                           new \GuzzleHttp\Psr7\Response(200, [], json_encode(['foo' => 'bar']))
                       ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->patch('test', []);
        $this->assertNotNull($response);
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function testMakeRequest()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $request = $client->makeRequest();
        $this->assertNotNull($request);
        $this->assertInstanceOf(PendingRequest::class, $request);
    }

    public function testGetHttpClient()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $httpClient = $client->getHttpClient();
        $this->assertNotNull($httpClient);
        $this->assertInstanceOf(\Illuminate\Http\Client\Factory::class, $httpClient);
    }

    public function testGetUser()
    {
        $expectedResponse = [
            "status"  => "success",
            "message" => "User retrieved successfully!",
            "data"    => [
                "id"              => 1,
                "global_id" => null,
                "name"            => "Super Admin",
                "email"           => "admin@earnie.com",
                "username"        => "admin",
                "first_name"      => "Super",
                "last_name"       => "Admin",
                "timezone"        => null,
                "phone"           => null,
                "dob"             => null,
                "activation_code" => null,
                "external_id"     => null,
                "is_activated"    => 1,
                "activated_at"    => null,
                "last_login_at"   => null,
                "created_at"      => "2023-12-04 06=>27=>13",
                "updated_at"      => "2023-12-04 06=>27=>13",
                "paypal_email"    => null,
                "created_by"      => null,
                "updated_by"      => null,
                "deleted_at"      => null,
                "world_id"        => "world-1",
                "roles"           => [
                    [
                        "id"       => 3, "name" => "sa",
                        "world_id" => "world-1"
                    ]
                ],
                "groups"          => []
            ]
        ];
        $httpClient = Mockery::mock(\Illuminate\Http\Client\Factory::class);
        $pendingRequest = Mockery::mock(PendingRequest::class);
        $httpClient->shouldReceive('baseUrl')
                   ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('acceptJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('asJson')
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('withToken')
                       ->withAnyArgs()
                       ->andReturn($pendingRequest);
        $pendingRequest->shouldReceive('get')
                       ->with('/api/auth/me', [])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                           new \GuzzleHttp\Psr7\Response(200, [], json_encode($expectedResponse))
                       ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->getUser();
        $this->assertNotNull($response);
        $this->assertEquals($expectedResponse['data'], $response);
    }

    public function testGetClientID()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $this->assertEquals('test', $client->getClientID());
    }

    public function testGetClientRedirectURL()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $this->assertEquals('test', $client->getClientRedirectURL());
    }

    public function testGetHost()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $this->assertEquals('test', $client->getHost());
    }

    public function testGetClientSecret()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $this->assertEquals('test', $client->getClientSecret());
    }

    public function testFromBaseURL()
    {
        $client = new WinnieClient($this->app, $this->app->make(\Illuminate\Http\Client\Factory::class));
        $url = $client->fromBaseURL('\foo');
        $this->assertEquals('test\foo', $url);
    }
}
