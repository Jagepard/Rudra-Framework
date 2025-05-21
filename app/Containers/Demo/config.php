<?php

use App\Containers\Demo\Factory\StdFactory;
use App\Containers\Demo\Factory\TestFactory;
use App\Containers\Demo\Interface\TestInterface;

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
        // 'factory'  => StdFactory::class,
        'callable' => function (){
            $std = new stdClass;
            $std->method = __METHOD__ . '::Created from waiting';
    
            return $std;
        },
    ]
];
