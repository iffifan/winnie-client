<?php

namespace Iffifan\WinnieClient\Tests\Unit\Http\Middleware;

use Iffifan\WinnieClient\Http\Middleware\WinnieProxyAuth;
use Iffifan\WinnieClient\Tests\Dummy\User;
use Iffifan\WinnieClient\Tests\TestCase;
use Illuminate\Http\Request;

class WinnieProxyAuthTest extends TestCase
{

    public function testUserAllowedWithWinnieToken()
    {
        $request = Request::create('/api/test');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Authorization', 'Bearer ' . $this->getWinnieToken());
        $user = new User();
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

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
                'method' => 'getUser',
                'return' => $userData
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);

        $middleware = new WinnieProxyAuth();
        $response = $middleware->handle($request, function () {
            return response('OK');
        });
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(User::class, $request->user());
        $this->assertInstanceOf(\Iffifan\WinnieClient\Models\User::class, $request->user()->winnie);
        $this->assertTrue(auth()->check());
    }


    public function testIfRequestIsIgnoredIfNoWinnieTokenIsProvided()
    {
        $request = Request::create('/api/test');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Authorization', 'Bearer ' . $this->getWinnieToken(10));
        $middleware = new WinnieProxyAuth();
        $response = $middleware->handle($request, function () {
            return response('OK');
        });
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
        $this->assertNull($request->user());
        $this->assertFalse(auth()->check());
    }



    private function getWinnieToken(int $length = 201)
    {
        return str_repeat('a', $length);
    }

}
