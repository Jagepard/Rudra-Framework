<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Stub\AsFactoryObject;
use Rudra\Container\Interfaces\FactoryInterface;

class AsObjectFactory implements FactoryInterface
{
    public function create(): object
    {
        return new AsFactoryObject();
    }
}
