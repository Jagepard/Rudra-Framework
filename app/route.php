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
        $route = new Http\Route($router, $this->container()->config('namespaces', 'web'));
        $router->setToken($route->getToken());

        if (!$router->isToken()) {
            throw new RouterException('404');
        }
    }
}
