<?php

namespace App;

use Rudra\RouteTrait;
use Rudra\SetContainerTrait;

class Route
{

    use SetContainerTrait;
    use RouteTrait;

    public function run()
    {
        $this->route(Web\Route::class, 'web');
        $this->handleException();
    }
}
