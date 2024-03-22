<?php

use App\Containers\Web\Factory\StdFactory;
use App\Containers\Web\Factory\TestFactory;
use App\Containers\Web\Interface\TestInterface;

return [
    'contracts'   => [
        stdClass::class      => StdFactory::class,
        TestInterface::class => TestFactory::class
    ],
    'services'    => [

    ]
];
