<?php

namespace Iffifan\WinnieClient\Tests\Integration;

use Iffifan\WinnieClient\Services\OAuthService;
use Iffifan\WinnieClient\Tests\TestCase;

class UserDataTest extends TestCase
{

    public function testGetUserWithToken()
    {
        $clientExpectations = [
            [
                'method' => 'get',
                'with'   => '/api/auth/me',
                'return' => [
                    'response' => [
                        "status"  => "success",
                        "message" => "User retrieved successfully!",
                        "data"    => [
                            "id"              => 1, "global_id" => null,
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
                                    "id"       => 3,
                                    "name" => "sa",
                                    "world_id" => "world-1"
                                ]
                            ],
                            "groups"          => []
                        ]
                    ]
                ],
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $user    = $service->getUserFromAccessToken('token');
        $this->assertEquals('Super Admin', $user['name']);
        $this->assertEquals($clientExpectations[0]['return']['response']['data'], $user);
    }
}
