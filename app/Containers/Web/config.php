<?php

use App\Containers\Web\Factory\StdFactory;
use App\Containers\Web\Factory\TestFactory;
use App\Containers\Web\Interface\TestInterface;

return [
    'contracts'   => [
        // stdClass::class      => StdFactory::class,
        stdClass::class      => 'callable',
        // stdClass::class      => function (){
        //     $std = new stdClass;
        //     $std->method = __METHOD__ . '::Dependency Injection';
    
        //     return $std;
        // },
        TestInterface::class => TestFactory::class
    ],

    'services'    => [
        'factory'  => StdFactory::class,
        'callable' => function (){
            $std = new stdClass;
            $std->method = __METHOD__ . '::Created from waiting';
    
            return $std;
        },
    ]
];
