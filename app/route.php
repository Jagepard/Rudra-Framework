<?php

namespace App;

use Rudra\ExternalTraits\RouteTrait;
use Rudra\ExternalTraits\SetContainerTrait;

class Route
{
    use RouteTrait;

    /**
     * @throws \Rudra\Exceptions\RouterException
     */
    public function run()
    {
        $this->collect(config('namespaces'), config('database', 'active'));
        $this->handleException();
    }
}
