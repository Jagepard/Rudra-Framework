<?php

namespace App;

use Rudra\Router;
use Rudra\RouterException;

class Route
{

    /**
     * Устанавливаем приоритет маршрутов
     *
     * @param \Rudra\Router $router
     *
     * @throws \Rudra\RouterException
     */
    public function run(Router $router)
    {
        $router->annotation('MainController', 'actionIndex');              // mainpage

        $router->middleware('get', [
            'pattern'     => '123/123',
            'controller'  => 'MainController',
            'method'      => 'actionIndex',
            'middleware'  => [['\\App\\Http\\Middleware\\MainMiddleware', ['int' => 123]], ['\\App\\Http\\Middleware\\MainMiddleware', ['int' => 124]]]
        ]);

        $router->middleware('get', [
            'pattern'     => '123/122',
            'controller'  => 'MainController',
            'method'      => 'actionIndex',
            'middleware'  => [['\\App\\Http\\Middleware\\MainMiddleware', ['int' => 123]], ['\\App\\Http\\Middleware\\MainMiddleware', ['int' => 125]]]
        ]);

        if (!$router->isToken()) {
            throw new RouterException('404');
        }
    }
}
