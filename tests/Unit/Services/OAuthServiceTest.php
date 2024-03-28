<?php

namespace Iffifan\WinnieClient\Tests\Unit\Services;

use Iffifan\WinnieClient\Exceptions\OAuthException;
use Iffifan\WinnieClient\Services\OAuthService;
use Iffifan\WinnieClient\Tests\TestCase;
use Illuminate\Http\Client\HttpClientException;

class OAuthServiceTest extends TestCase
{

    public function testMakeTokenURL()
    {
        $service = $this->app->make(OAuthService::class);
        $url = $service->makeTokenURL();
        $this->assertEquals(OAuthService::TOKEN_PATH, $url);
    }

    public function testGetUserFromAccessToken()
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
                                    "id"       => 3, "name" => "sa",
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
        $user = $service->getUserFromAccessToken('test');
        $this->assertEquals('Super Admin', $user['name']);
        $this->assertEquals($clientExpectations[0]['return']['response']['data'], $user);
    }
    public function testGetUserFromAccessTokenThrowsException()
    {
        $clientExpectations = [
            [
                'method' => 'get',
                'with'   => '/api/auth/me',

                'return' => [
                    'status'    =>  500,
                    'response'  =>  'Internal Server Error',
                ],
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $this->expectException(HttpClientException::class);
        $user = $service->getUserFromAccessToken('test');
    }

    public function testRefreshAccessToken()
    {
        $clientExpectations = [
            [
                'method' => 'post',
                'with'   => [
                    '/oauth/token',
                    [
                        'grant_type'    => 'refresh_token',
                        'client_id'     => 'test',
                        'client_secret' => 'test',
                        'refresh_token' => 'foo',
                        'scope'         => '',
                    ]
                ],
                'return' => [
                    'response' => [
                        "token_type"    => "Bearer",
                        "expires_in" => 31622400,
                        "access_token"  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiYzA3MzdiMDI4NjlkZjIxOWI1YTRhZjc4M2M5MDJjM2YyMzRiYzEzNzM4ODM2NTUzMWMyOGIzZjBiZWRmMTA3NDBhMmQyYmJmYzI4MjI3NmUiLCJpYXQiOjE3MDgzMjM2MDIuMzg4NjE3LCJuYmYiOjE3MDgzMjM2MDIuMzg4NjIxLCJleHAiOjE3Mzk5NDYwMDIuMzc3NjA4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Rd0zuKx7oTU0VTxFvWjawI_pinbJz9enQhdFLT7yDJOPR1JicLreij0qm1kDjF2oLAQ81zTk4CWbjqgieJp2B7Uai4U2MBk_mvFbjJq8OPsqBY244sqHMDlbGqmR9rCPY8InAfSKfNQroRMmC3RFrKYyVNqgqta0t8Mb3f0WB0LtUIExZOCrERh8JO00p4ZtgZ0B2TWv7PjM86b4enMhrtCHKZniQQ9GIqKkCI_Z2uSnBp0cnFh2_RGMOzdLA0A6m1OHa8BEoRxUgFihuF5DFTGbnEbU1GCf_R9KXLrG_g6M4ZC9PsyKKCU3FYcEVri6Urko1gZKgqaXh-uz7iaJeUjTBgAMX3lLQDzTgA9BocZbtpE3rd65ExUUTUYSk2NcouSpKxql8kg3ph2v0BYmTP1wUxTTPFELaPDQy5kAlCEHUjrOcc6ufz2y2aHVDwjb8O9dKeW57OOqD3LbZ-QM2fLW6v0oScxSFHzOt1U6vF_bFS-wSyvvgTc2PI7Jxs3QuMRXIX93ubflfrDyWFlJKC0Ujiw14g8eLsvRKMitwXdz3anyP3KR8yP9AbC4FxIKMGad1x6-osQOxAuGECSXjzIukIERyASZylig18hpcHW8rd97J9GokvIWnqod3J6Z2zf8qeQxlPFrW4Xi8aR8iydjReY7ycP9f1z2tUr0eC8",
                        "refresh_token" => "def50200e0e08bf702b4d5f7e2abc73c595374d470e110052d1d5cde805f9429364aa88fad420537db6c816427c3ada687ed56c67cae3724e47a2c15e9867252918992a16c8059f93e42de9a262d58a6acd0ea63dbf06c3265340f652bd0305c2516ff45f93f94b7fc81581170591a7cd3cafb93974fa86c2d9f6420997e4d14727b20c2c010adb67e4e2a20a8f33947c2bc5b6a0bd4453b4b762ebf54bce477ca9e290cb2445f9751852b34a2e1a15253d8bd4e1939961529b67d07ab2b485c75ec609ef6cc3213fe865685b96627def5afeeaf08eb3944edde5784309040e79e64cc9587a2b644ef743226bde66dbcc3a86c88c13f1d8b121dd295ebe7b3c528885738aaa52922d5d61e3558c0f05741979aea583d307629ed98526cc1ef3846bbb17ba8a1ea89cbd5f874f15bae3532d5562ce356e7251461267363aac934161bc99bdd3d4dd7a433127831940a5c7e2cea1cc50da23557b0ad995bafd4513b"
                    ]
                ]
            ],
            [
                'method'    =>  'getClientID',
                'return'    =>  'test'
            ],
            [
                'method'    =>  'getClientSecret',
                'return'    =>  'test'
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $token = $service->refreshAccessToken('foo');
        $this->assertEquals('Bearer', $token['token_type']);
        $this->assertEquals($clientExpectations[0]['return']['response'], $token);
    }

    public function testMakeAuthorizationURL()
    {
        $clientExpectation = [
            [
                'method' => 'fromBaseURL',
                'with'   => OAuthService::AUTH_PATH,
                'return' => 'https://foo.com/oauth/authorize'
            ],
            [
                'method' => 'getClientID',
                'return' => 'test'
            ],
            [
                'method' => 'getClientSecret',
                'return' => 'test'
            ],
            [
                'method' => 'getClientRedirectURL',
                'return' => 'test'
            ]
        ];
        $this->setUpWinnieClient($clientExpectation);
        $service = $this->app->make(OAuthService::class);
        $url = $service->makeAuthorizationURL();
        $this->assertEquals('https://foo.com/oauth/authorize?client_id=test&redirect_uri=test&response_type=code&scope=&prompt=login', $url);
    }

    public function testGetAccessToken()
    {
        $clientExpectations = [
            [
                'method'    =>  'post',
                'with'      =>  [
                    '/oauth/token',
                    [
                        'grant_type'    => 'authorization_code',
                        'client_id'     => 'test',
                        'client_secret' => 'test',
                        'redirect_uri'  => 'test',
                        'code'          => 'foo',
                    ]
                ],
                'return'    =>  [
                    'response' => [
                        "token_type"    => "Bearer",
                        "expires_in"    => 31622400,
                        "access_token"  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiYzA3MzdiMDI4NjlkZjIxOWI1YTRhZjc4M2M5MDJjM2YyMzRiYzEzNzM4ODM2NTUzMWMyOGIzZjBiZWRmMTA3NDBhMmQyYmJmYzI4MjI3NmUiLCJpYXQiOjE3MDgzMjM2MDIuMzg4NjE3LCJuYmYiOjE3MDgzMjM2MDIuMzg4NjIxLCJleHAiOjE3Mzk5NDYwMDIuMzc3NjA4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Rd0zuKx7oTU0VTxFvWjawI_pinbJz9enQhdFLT7yDJOPR1JicLreij0qm1kDjF2oLAQ81zTk4CWbjqgieJp2B7Uai4U2MBk_mvFbjJq8OPsqBY244sqHMDlbGqmR9rCPY8InAfSKfNQroRMmC3RFrKYyVNqgqta0t8Mb3f0WB0LtUIExZOCrERh8JO00p4ZtgZ0B2TWv7PjM86b4enMhrtCHKZniQQ9GIqKkCI_Z2uSnBp0cnFh2_RGMOzdLA0A6m1OHa8BEoRxUgFihuF5DFTGbnEbU1GCf_R9KXLrG_g6M4ZC9PsyKKCU3FYcEVri6Urko1gZKgqaXh-uz7iaJeUjTBgAMX3lLQDzTgA9BocZbtpE3rd65ExUUTUYSk2NcouSpKxql8kg3ph2v0BYmTP1wUxTTPFELaPDQy5kAlCEHUjrOcc6ufz2y2aHVDwjb8O9dKeW57OOqD3LbZ-QM2fLW6v0oScxSFHzOt1U6vF_bFS-wSyvvgTc2PI7Jxs3QuMRXIX93ubflfrDyWFlJKC0Ujiw14g8eLsvRKMitwXdz3anyP3KR8yP9AbC4FxIKMGad1x6-osQOxAuGECSXjzIukIERyASZylig18hpcHW8rd97J9GokvIWnqod3J6Z2zf8qeQxlPFrW4Xi8aR8iydjReY7ycP9f1z2tUr0eC8",
                        "refresh_token" => "def50200e0e08bf702b4d5f7e2abc73c595374d470e110052d1d5cde805f9429364aa88fad420537db6c816427c3ada687ed56c67cae3724e47a2c15e9867252918992a16c8059f93e42de9a262d58a6acd0ea63dbf06c3265340f652bd0305c2516ff45f93f94b7fc81581170591a7cd3cafb93974fa86c2d9f6420997e4d14727b20c2c010adb67e4e2a20a8f33947c2bc5b6a0bd4453b4b762ebf54bce477ca9e290cb2445f9751852b34a2e1a15253d8bd4e1939961529b67d07ab2b485c75ec609ef6cc3213fe865685b96627def5afeeaf08eb3944edde5784309040e79e64cc9587a2b644ef743226bde66dbcc3a86c88c13f1d8b121dd295ebe7b3c528885738aaa52922d5d61e3558c0f05741979aea583d307629ed98526cc1ef3846bbb17ba8a1ea89cbd5f874f15bae3532d5562ce356e7251461267363aac934161bc99bdd3d4dd7a433127831940a5c7e2cea1cc50da23557b0ad995bafd4513b"
                    ]
                ]
            ],
            [
                'method'    =>  'getClientID',
                'return'    =>  'test'
            ],
            [
                'method'    =>  'getClientSecret',
                'return'    =>  'test'
            ],
            [
                'method'    =>  'getClientRedirectURL',
                'return'    =>  'test'
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $request = $this->app->make('request');
        $request->merge(['code' => 'foo']);
        $token = $service->getAccessToken($request);
        $this->assertEquals('Bearer', $token['token_type']);
        $this->assertEquals($clientExpectations[0]['return']['response'], $token);
    }

    public function testGetAccessTokenWithCodeExpired()
    {
        $clientExpectations = [
            [
                'method'    =>  'post',
                'with'      =>  [
                    '/oauth/token',
                    [
                        'grant_type'    => 'authorization_code',
                        'client_id'     => 'test',
                        'client_secret' => 'test',
                        'redirect_uri'  => 'test',
                        'code'          => 'foo',
                    ]
                ],
                'return'    =>  [
                    'response' => [
                        "error"    => true,
                        "hint"    => "Authorization code has expired",
                    ]
                ]
            ],
            [
                'method'    =>  'getClientID',
                'return'    =>  'test'
            ],
            [
                'method'    =>  'getClientSecret',
                'return'    =>  'test'
            ],
            [
                'method'    =>  'getClientRedirectURL',
                'return'    =>  'test'
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $request = $this->app->make('request');
        $request->merge(['code' => 'foo']);
        $this->expectException(OAuthException::class);
        $token = $service->getAccessToken($request);
    }

    public function testGetClientCredentialsToken()
    {
        $clientExpectations = [
            [
                'method' => 'post',
                'with'   => [
                    '/oauth/token',
                    [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => 'test',
                        'client_secret' => 'test',
                        'scope'          => '*',
                    ]
                ],
                'return' => [
                    'response' => [
                        "token_type"    => "Bearer",
                        "expires_in"    => 31622400,
                        "access_token"  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiYzA3MzdiMDI4NjlkZjIxOWI1YTRhZjc4M2M5MDJjM2YyMzRiYzEzNzM4ODM2NTUzMWMyOGIzZjBiZWRmMTA3NDBhMmQyYmJmYzI4MjI3NmUiLCJpYXQiOjE3MDgzMjM2MDIuMzg4NjE3LCJuYmYiOjE3MDgzMjM2MDIuMzg4NjIxLCJleHAiOjE3Mzk5NDYwMDIuMzc3NjA4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Rd0zuKx7oTU0VTxFvWjawI_pinbJz9enQhdFLT7yDJOPR1JicLreij0qm1kDjF2oLAQ81zTk4CWbjqgieJp2B7Uai4U2MBk_mvFbjJq8OPsqBY244sqHMDlbGqmR9rCPY8InAfSKfNQroRMmC3RFrKYyVNqgqta0t8Mb3f0WB0LtUIExZOCrERh8JO00p4ZtgZ0B2TWv7PjM86b4enMhrtCHKZniQQ9GIqKkCI_Z2uSnBp0cnFh2_RGMOzdLA0A6m1OHa8BEoRxUgFihuF5DFTGbnEbU1GCf_R9KXLrG_g6M4ZC9PsyKKCU3FYcEVri6Urko1gZKgqaXh-uz7iaJeUjTBgAMX3lLQDzTgA9BocZbtpE3rd65ExUUTUYSk2NcouSpKxql8kg3ph2v0BYmTP1wUxTTPFELaPDQy5kAlCEHUjrOcc6ufz2y2aHVDwjb8O9dKeW57OOqD3LbZ-QM2fLW6v0oScxSFHzOt1U6vF_bFS-wSyvvgTc2PI7Jxs3QuMRXIX93ubflfrDyWFlJKC0Ujiw14g8eLsvRKMitwXdz3anyP3KR8yP9AbC4FxIKMGad1x6-osQOxAuGECSXjzIukIERyASZylig18hpcHW8rd97J9GokvIWnqod3J6Z2zf8qeQxlPFrW4Xi8aR8iydjReY7ycP9f1z2tUr0eC8",
                    ]
                ]
            ],
            [
                'method' => 'getClientID',
                'return' => 'test'
            ],
            [
                'method' => 'getClientSecret',
                'return' => 'test'
            ]
        ];
        $this->setUpWinnieClient($clientExpectations);
        $service = $this->app->make(OAuthService::class);
        $token = $service->getClientCredentialsToken();
        $this->assertEquals($clientExpectations[0]['return']['response']['access_token'], $token);
    }

}
