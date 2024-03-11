# Winnie Client SDK Documentation

## Introduction

The Winnie Client SDK is a Laravel library designed to interact with the Winnie API. It provides a simple and efficient way to make requests to the API and handle responses. The SDK includes
a `WinnieClient` class, a `WinnieClient` facade, a `HasWinnieUser` trait and some middlewares and model classes.

## Installation

To install the Winnie Client SDK, you need to require it using composer:

```bash
composer require iffifan/winnie-client
```

## Configuration

After installation, you need to configure the SDK by setting up the environment variables in your `.env` file. The required variables include:

- `WINNIE_CLIENT_ID`: Your Winnie API client ID.
- `WINNIE_CLIENT_SECRET`: Your Winnie API client secret.
- `WINNIE_CLIENT_REDIRECT`: Your Winnie API redirect URL.
- `WINNIE_CLIENT_HOST`: The Winnie API host URL.

## Usage

### WinnieClient Class

The `WinnieClient` class is the main class for interacting with the Winnie API. It provides methods for making `GET`, `POST`, `PATCH`, `PUT`, and `DELETE` requests. Each of these methods accepts a
path and an optional data array. The path is the endpoint you want to hit on the Winnie API, and the data array contains any parameters you want to send with the request.

Here's an example of making a `GET` request:

```php
use Iffifan\WinnieClient\WinnieClient;

$winnieClient = new WinnieClient($app, $httpClient);
$response = $winnieClient->withToken('your-token')
                         ->get('/api/auth/me');

// The response is an instance of Illuminate\Http\Client\Response
// You can use the `json` method to get the response data as an array
$data = $response->json();
```

For `POST`, `PATCH`, `PUT`, and `DELETE` requests, you can send data as the second argument:

```php
$response = $winnieClient->withToken('your-token')
                         ->post('/api/auth/login', [
                            'email' => 'user@example.com',
                            'password' => 'password',
                        ]);

$data = $response->json();
```

The `WinnieClient` class also provides methods for setting and getting the API token. The `withToken` method sets the token, and the `getToken` method gets the current token.

```php
$winnieClient->withToken('your-token');
echo $winnieClient->getToken(); // Outputs: your-token
```

### WinnieClient Facade

The `WinnieClient` facade provides a static interface to the `WinnieClient` class. It can be used in the same way as the `WinnieClient` class.

```php
use Iffifan\WinnieClient\Facades\WinnieClient;

$response = WinnieClient::withToken('your-token')
                          ->get('/api/auth/me');
$data = $response->json();
```

### HasWinnieUser Trait

The `HasWinnieUser` trait can be used in your User model to provide additional methods related to the Winnie User. It provides methods for setting the Winnie User and checking if the user has a
specific role. Your authenticatable User model should use this trait to interact with the Winnie API. 

```php
use Iffifan\WinnieClient\Models\Traits\HasWinnieUser;

class User
{
    use HasWinnieUser;

    // ...
}
```

## Models

The Models directory contains the `User` model which represents a Winnie User. 
There is also a `KPI` model which represents a KPI in the Winnie API. This class can be used to submit KPI to Winnie API.


## Middlewares

The Middlewares directory contains middleware classes that can be used to handle requests and responses in your Laravel application. These middlewares can be added to your HTTP kernel.
There are mainly two middlewares:

- `CheckWinnieUser`: This middleware checks if the user has a valid Winnie token and also checks for the role of the user. You can provide the role you want to check. If the user does not have the
  required role, the middleware will return a 403 response. It also sets the Winnie User in Authenticatable User model. 
  
  In your `app/Http/Kernel.php` file, you can add the middleware to the `$middlewareAliases` array:
  
  ```php
  protected $routeMiddleware = [
      // ...
      'winnie.auth' => \Iffifan\WinnieClient\Http\Middleware\CheckWinnieUser::class,
  ];
  ```
  Then you can use the middleware in your routes:
  
  ```php
  Route::get('/admin', function () {
      // ...
  })->middleware('winnie.auth:admin');
  
  Route::get('/user', function () {
      // ...
  })->middleware('winnie.auth:user');
  ```

- `WinnieProxyAuth`: This middleware enables your application to authenticate the user using winnie mobile app token. It checks if the user has a valid winnie mobile app token and sets the user in
  Authenticatable User model.
  
  You can use the middleware in your routes:
  
  ```php
  
    Route::get('/winnie-login', 'winnieLogin')
        ->name('winnie-login')
        ->middleware(\Iffifan\WinnieClient\Http\Middleware\WinnieProxyAuth::class);
  
  ```


## Conclusion

The Winnie Client SDK provides a simple and efficient way to interact with the Winnie API in your Laravel application. By following the above steps, you can install, configure, and use the SDK in your
application.
