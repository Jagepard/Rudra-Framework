<?php

namespace App\Http;

use Rudra\Router;

class Route
{

    protected $token;

    public function __construct(Router $router, string $namespace)
    {
        $router->setNamespace($namespace);
        
        $router->annotation('MainController');              // mainpage

        $router->middleware('get', [
                'pattern'     => '123/123',
                'controller'  => 'MainController',
                'method'      => 'actionIndex',
                'middleware'  => [
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 1]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 2]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 3]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 4]]
                ],

                'after_middleware'  => [
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 5]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 6]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 7]],
                    ['\\App\\Http\\Middleware\\MainMiddleware::namespace', ['int' => 8]]
                ]
            ]
        );

        $router->middleware('get', [
            'pattern'     => '123/122',
            'controller'  => 'MainController',
            'method'      => 'actionIndex',
            'middleware'  => [
                ['MainMiddleware', ['int' => 1]],
                ['MainMiddleware', ['int' => 2]],
                ['MainMiddleware', ['int' => 3]],
                ['MainMiddleware', ['int' => 4]]
            ],

            'after_middleware'  => [
                ['MainMiddleware', ['int' => 5]],
                ['MainMiddleware', ['int' => 6]],
                ['MainMiddleware', ['int' => 7]],
                ['MainMiddleware', ['int' => 8]]
            ]
        ]);

        $this->token = $router->isToken();
    }

    public function getToken()
    {
        return $this->token;
    }
}