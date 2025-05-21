<?php
return [
  0 => 
  [
    0 => 
    [
      'url' => '',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'attributes',
      'middleware' => 
      [
        'before' => 
        [
          0 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ],
          1 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ],
        ],
        'after' => 
        [
          0 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ],
          1 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ],
        ],
      ],
      'method' => 'GET',
    ],
  ],
  1 => 
  [
    0 => 
    [
      'url' => 'name/:[\\d]{1,3}',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'attributes',
      'middleware' => 
      [
        'before' => 
        [
          0 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ],
          1 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ],
        ],
        'after' => 
        [
          0 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ],
          1 => 
          [
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ],
        ],
      ],
      'method' => 'GET',
    ],
  ],
  2 => 
  [
    0 => 
    [
      'url' => 'autowire',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'autowire',
      'middleware' => 
      [
      ],
      'method' => 'GET',
    ],
  ],
];