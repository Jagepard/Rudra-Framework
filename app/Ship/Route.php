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
        if (Rudra::config()->get('environment') === 'development') {
            Rudra::get("debugbar")['time']->stopMeasure('index');
            Rudra::get("debugbar")['time']->startMeasure('routing');
        }

        $this->collect(Rudra::config()->get('containers'));

        throw new RouterException("404");
    }

    protected function collect(array $namespaces): void
    {
        foreach ($namespaces as $container => $item) {
            $this->getRoutes($container);
        }
    }

    protected function getRoutes(string $container): ?array
    {
        $path = "../app/Containers/" . ucfirst($container) . "/routes";

        return Router::annotationCollector(require_once $path . ".php", false, Rudra::config()->get("attributes"));
    }
}
