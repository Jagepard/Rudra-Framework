<?php

namespace App\Ship;

use Rudra\Container\Facades\Rudra;
use Rudra\Exceptions\RouterException;
use Rudra\Router\RouterFacade as Router;

class Route
{
    /**
     * @throws RouterException
     */
    public function run()
    {
        $this->collect(Rudra::config()->get('containers'));
        throw new RouterException("404");
    }

    protected function collect(array $namespaces)
    {
        foreach ($namespaces as $container => $item) {
            $this->getRoutes($container);
        }
    }

    protected function getRoutes(string $container)//: array
    {
        $path = "../app/Containers/" . ucfirst($container) . "/routes";

        if (file_exists($path . ".php")) {
            return Router::annotationCollector(require_once $path . ".php");
        }
    }
}
