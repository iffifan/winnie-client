<?php

namespace Iffifan\WinnieClient\Tests\Integration;

use Iffifan\WinnieClient\Tests\TestCase;
use Iffifan\WinnieClient\WinnieClient;
use Illuminate\Http\Client\PendingRequest;
use Mockery;
use PHPUnit\Event\Facade;

class UserGroupsDataTest extends TestCase
{

    public function testGetUserGroupsWithToken()
    {
        $userGroupsResponse = [
            "status"  => "success",
            "message" => "Groups found successfully!",
            "data" => [
                [
                    "id"         => 1, "name" => "Delectus",
                    "parent_id"  => null, "is_default_group" => 0,
                    "created_at" => "2023-12-04 06=>27=>52",
                    "updated_at" => "2023-12-04 06=>27=>52",
                    "deleted_at" => null, "created_by" => null,
                    "updated_by" => null, "world_id" => "world-1",
                    "children"   => [
                        [
                            "id"               => 71,
                            "name"             => "Vel",
                            "parent_id"        => 1,
                            "is_default_group" => 0,
                            "created_at"       => "2023-12-04 06=>27=>52",
                            "updated_at"       => "2023-12-04 06=>27=>52",
                            "deleted_at"       => null,
                            "created_by"       => null,
                            "updated_by"       => null,
                            "world_id"         => "world-1",
                            "children"         => [
                                [
                                    "id"               => 448,
                                    "name"             => "Neque",
                                    "parent_id"        => 71,
                                    "is_default_group" => 0,
                                    "created_at"       => "2023-12-04 06=>27=>54",
                                    "updated_at"       => "2023-12-04 06=>27=>54",
                                    "deleted_at"       => null,
                                    "created_by"       => null,
                                    "updated_by"       => null,
                                    "world_id"         => "world-1",
                                    "children"         => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
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
        $pendingRequest->shouldReceive('get')
                       ->with('/api/user/groups', [])
                       ->andReturn(new \Illuminate\Http\Client\Response(
                           new \GuzzleHttp\Psr7\Response(200, [],
                               json_encode($userGroupsResponse))
                       ));

        $client = new WinnieClient($this->app, $httpClient);
        $client->withToken('token');
        $response = $client->get('/api/user/groups');
        $this->assertNotNull($response);
        $this->assertEquals($userGroupsResponse, $response->json());
    }
}
