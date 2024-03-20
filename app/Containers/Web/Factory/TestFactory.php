<?php

namespace App\Containers\Web\Factory;

use App\Containers\Web\Interface\TestInterface;

class TestFactory implements TestInterface
{
    public string $method;

    public function create()
    {
        $this->method = __METHOD__ . '->autowired';
        return $this;
    }
}
