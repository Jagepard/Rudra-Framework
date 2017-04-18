<?php

/**
 * Date: 15.07.16
 * Time: 17:40
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App;


use Rudra\Router;
use Rudra\RouterException;


/**
 * Class Route
 *
 * @package App
 */
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
