<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Interface\TestInterface;
use Rudra\Container\Interfaces\FactoryInterface;

class TestFactory implements TestInterface, FactoryInterface
{
    public string $method;

    public function create(): object
    {
        $this->method = __METHOD__ . '->autowired';
        return $this;
    }
}
