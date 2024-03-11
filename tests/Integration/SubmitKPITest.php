<?php

namespace Iffifan\WinnieClient\Tests\Integration;

use Iffifan\WinnieClient\Models\KPI;
use Iffifan\WinnieClient\Tests\TestCase;
use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\PendingRequest;
use Mockery;

class SubmitKPITest extends TestCase
{

    public function testSubmit()
    {
        $kpi = KPI::fromArray([
            'userId'     => 1,
            'email'      => 'foo@mail.com',
            'externalID' => 'foo',
            'value'      => 1.0,
            'weight'     => 1.0,
            'timestamp'  => '2023-12-04 06:27:13',
            'meta'       => []
        ]);
        $expectedResponse = [
            "status" => "success",
            "message" => "KPI data added successfully!"
        ];
        $httpClient     = Mockery::mock(\Illuminate\Http\Client\Factory::class);
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
                       ->with('/api/client/kpi/create', ['kpi' => $kpi->toArray()])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                           new \GuzzleHttp\Psr7\Response(200, [],
                               json_encode($expectedResponse))
                       ));
        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('foo');
        $response = $client->post('/api/client/kpi/create', ['kpi' => $kpi->toArray()]);
        $this->assertNotNull($response);
        $this->assertEquals($expectedResponse, $response->json());
    }
}
