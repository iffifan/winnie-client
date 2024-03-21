<?php

namespace Iffifan\WinnieClient\Tests\Unit\Http\Middleware;

use Iffifan\WinnieClient\Http\Middleware\CheckWinnieUser;
use Iffifan\WinnieClient\Tests\Dummy\User;
use Iffifan\WinnieClient\Tests\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWinnieUserTest extends TestCase
{

    public function testIfAdminIsAllowed()
    {
        $request = new Request();
        $user = new User();
        $userData =  [
            "id"         => 1, "global_id" => null,
            "name"       => "Super Admin",
            "email"      => "foo@mail.com",
            "username"   => "admin",
            "first_name" => "Super",
            "last_name"  => "Admin",
            "roles"      => [
                [
                    "id"       => 3,
                    "name"     => "sa",
                    "world_id" => "world-1"
                ]
            ],
            "groups"     => [
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
        ];
        $user->winnie = \Iffifan\WinnieClient\Models\User::makeFromArray($userData);
        $request->setUserResolver(fn() => $user);

        $middleware = new CheckWinnieUser();
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK');
        }, 'admin');

        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIfAdminIsAllowedWhenWinnieReturnsUserArray()
    {
        $request = new Request();
        $user = new User();
        $user->access_token = 'token';

        $userData = [
            "id"         => 1,
            "global_id"  => null,
            "name"       => "Super Admin",
            "email"      => "foo@mail.com",
            "username"   => "admin",
            "first_name" => "Super",
            "last_name"  => "Admin",
            "roles"      => [
                [
                    "id"       => 3,
                    "name"     => "sa",
                    "world_id" => "world-1"
                ]
            ],
            "groups"     => [
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
        ];
        $clientExpectations = [
            [
                'method'    =>  'getUser',
                'return'    =>  $userData
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $request->setUserResolver(fn() => $user);

        $middleware = new CheckWinnieUser();
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK');
        }, 'admin');

        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testIfUserIsNotAllowedToAdminRoute()
    {
        $request      = new Request();
        $user         = new User();
        $userData     = [
            "id"         => 1, "global_id" => null,
            "name"       => "Super Admin",
            "email"      => "foo@mail.com",
            "username"   => "admin",
            "first_name" => "Super",
            "last_name"  => "Admin",
            "roles"      => [
                [
                    "id"       => 3,
                    "name"     => "user",
                    "world_id" => "world-1"
                ]
            ],
            "groups"     => [
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
        ];
        $user->winnie = \Iffifan\WinnieClient\Models\User::makeFromArray($userData);
        $request->setUserResolver(fn() => $user);

        $middleware = new CheckWinnieUser();
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $response   = $middleware->handle($request, function ($req) {
            return new Response('OK');
        }, 'admin');
        $this->assertEquals(403, $response->getStatusCode());
    }


    public function testIfUserIsNotAllowedToAdminRouteWhenWinnieReturnsUserArray()
    {
        $request            = new Request();
        $user               = new User();
        $user->access_token = 'token';

        $userData           = [
            "id"         => 1,
            "global_id"  => null,
            "name"       => "Super Admin",
            "email"      => "foo@mail.com",
            "username"   => "admin",
            "first_name" => "Super",
            "last_name"  => "Admin",
            "roles"      => [
                [
                    "id"       => 3,
                    "name"     => "user",
                    "world_id" => "world-1"
                ]
            ],
            "groups"     => [
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
        ];
        $clientExpectations = [
            [
                'method' => 'getUser',
                'return' => $userData
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $request->setUserResolver(fn() => $user);

        $middleware = new CheckWinnieUser();
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $response   = $middleware->handle($request, function ($req) {
            return new Response('OK');
        }, 'admin');

        $this->assertEquals(403, $response->getStatusCode());
    }

}
