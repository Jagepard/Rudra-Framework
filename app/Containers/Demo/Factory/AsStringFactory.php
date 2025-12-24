<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Stub\AsFactoryString;
use Rudra\Container\Interfaces\FactoryInterface;

class AsStringFactory implements FactoryInterface
{
    public function create(): object
    {
        return new AsFactoryString();
    }
}
