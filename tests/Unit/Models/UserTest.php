<?php

namespace Iffifan\WinnieClient\Tests\Unit\Models;

use Iffifan\WinnieClient\Models\User;
use Iffifan\WinnieClient\Tests\TestCase;

class UserTest extends TestCase
{

    public function testId()
    {
        $user = new User();
        $user->setId(1);
        $this->assertEquals(1, $user->getId());
    }

    public function testName()
    {
        $user = new User();
        $user->setName('foo');
        $this->assertEquals('foo', $user->getName());
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail('foo@mail.com');
        $this->assertEquals('foo@mail.com', $user->getEmail());
    }

    public function testUserName()
    {
        $user = new User();
        $user->setUsername('foo');
        $this->assertEquals('foo', $user->getUsername());
    }

    public function testFirstName()
    {
        $user = new User();
        $user->setFirstName('foo');
        $this->assertEquals('foo', $user->getFirstName());
    }

    public function testLastName()
    {
        $user = new User();
        $user->setLastName('foo');
        $this->assertEquals('foo', $user->getLastName());
    }

    public function testRoles()
    {
        $roles = [
            [
                "id"       => 3,
                "name"     => "sa",
                "world_id" => "world-1"
            ]
        ];
        $roles = collect($roles);
        $user = new User();
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
    }

    public function testGroups()
    {
        $groups = [
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
        ];
        $groups = collect($groups);
        $user = new User();
        $user->setGroups($groups);
        $this->assertEquals($groups, $user->getGroups());
    }

    public function testHasRole()
    {
        $roles = [
            [
                "id"       => 3,
                "name"     => "sa",
                "world_id" => "world-1"
            ]
        ];
        $roles = collect($roles);
        $user = new User();
        $user->setRoles($roles);
        $this->assertTrue($user->hasRole('sa'));
    }

    public function testMakeFromArray()
    {
        $data = [
            "id"              => 1, "global_id" => null,
            "name"            => "Super Admin",
            "email"           => "foo@mail.com",
            "username"        => "admin",
            "first_name"      => "Super",
            "last_name"       => "Admin",
            "roles"           => [
                [
                    "id"       => 3,
                    "name"     => "sa",
                    "world_id" => "world-1"
                ]
            ],
            "groups"          => [
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

        $user = User::makeFromArray($data);
        $this->assertEquals($data['id'], $user->getId());
        $this->assertEquals($data['name'], $user->getName());
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertEquals($data['first_name'], $user->getFirstName());
        $this->assertEquals($data['last_name'], $user->getLastName());
        $this->assertEquals($data['roles'], $user->getRoles()->toArray());
        $this->assertEquals($data['groups'], $user->getGroups()->toArray());
        $this->assertTrue($user->hasRole('sa'));
    }
}
