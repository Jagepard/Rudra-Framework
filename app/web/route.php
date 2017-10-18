<?php

namespace App\Web;

use Rudra\Router;

class Route
{
    public function run(Router $router, $namespace)
    {
        $router->setNamespace($namespace);
        $router->annotation('MainController');

        return $router->isToken();
    }
}
