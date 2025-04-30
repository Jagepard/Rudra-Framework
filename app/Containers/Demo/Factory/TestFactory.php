<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Interface\TestInterface;

class TestFactory implements TestInterface
{
    public string $method;

    public function create()
    {
        $this->method = __METHOD__ . '->autowired';
        return $this;
    }
}
