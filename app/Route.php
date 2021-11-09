<?php

namespace App;

use Rudra\Container\Facades\Rudra;
use Rudra\Exceptions\RouterException;

class Route
{
    /**
     * @throws RouterException
     */
    public function run()
    {
        $this->collect(Rudra::config()->get('bundles'));
        throw new RouterException("404");
    }

    protected function collect(array $namespaces)
    {
        foreach ($namespaces as $bundle => $item) {
            $this->getRoutes($bundle);
        }
    }

    protected function getRoutes(string $bundle)//: array
    {
        $path = "../app/" . ucfirst($bundle) . "/routes";

        if (file_exists($path . ".php")) {
            return require_once $path . ".php";
        }
    }
}
