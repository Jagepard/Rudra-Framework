<?php

namespace App\Containers\Demo\Factory;

use Rudra\Container\Interfaces\FactoryInterface;
use stdClass;

class StdFactory implements FactoryInterface
{
    public function create(): object
    {
        $std = new stdClass;
        $std->method = __METHOD__ . '::Created by Factory';

        return $std;
    }
}
