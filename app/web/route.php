<?php

namespace App\Web;

use Rudra\Router;

class Route
{
    public function run(Router $router, $namespace)
    {
        $router->setNamespace($namespace);
        $router->annotation('MainController'); // mainpage
        $router->middleware('get', [
                'pattern'     => '123/123',
                'controller'  => 'MainController',
                'method'      => 'actionIndex',
                'middleware'  => [
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 1]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 2]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 3]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 4]]
                ],
                'after_middleware'  => [
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 5]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 6]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 7]],
                    ['\\App\\Web\\Middleware\\MainMiddleware::namespace', ['int' => 8]]
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

        return $router->isToken();
    }
}
