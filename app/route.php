<?php

namespace App;

use Rudra\ExternalTraits\RouteTrait;
use Rudra\ExternalTraits\SetContainerTrait;

class Route
{

    use SetContainerTrait;
    use RouteTrait;

    /**
     * @throws \Rudra\Exceptions\RouterException
     */
    public function run()
    {
        $this->route(Web\Route::class, 'web');
        $this->handleException();
    }
}
