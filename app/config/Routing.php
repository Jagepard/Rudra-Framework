<?php

/**
 * Date: 15.07.16
 * Time: 17:40
 * 
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Config;

use Rudra\Router;
use Rudra\RouterException;

class Routing
{

    /**
     * Устанавливаем приоритет маршрутов
     * @param \Rudra\Router $router
     *
     * @throws \Rudra\RouterException
     */
    public function run(Router $router)
    {
        $router->annotation('MainController', 'actionIndex');
        if (!$router->isToken()) throw new RouterException('404');
    }

}