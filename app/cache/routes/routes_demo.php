<?php
return array (
  0 => 
  array (
    0 => 
    array (
      'url' => '',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'attributes',
      'middleware' => 
      array (
        'before' => 
        array (
          0 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ),
          1 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ),
        ),
        'after' => 
        array (
          0 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ),
          1 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ),
        ),
      ),
      'method' => 'GET',
    ),
  ),
  1 => 
  array (
    0 => 
    array (
      'url' => 'name/:[\\d]{1,3}',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'attributes',
      'middleware' => 
      array (
        'before' => 
        array (
          0 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ),
          1 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ),
        ),
        'after' => 
        array (
          0 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\FirstMiddleware',
          ),
          1 => 
          array (
            0 => 'App\\Containers\\Demo\\Middleware\\SecondMiddleware',
          ),
        ),
      ),
      'method' => 'GET',
    ),
  ),
  2 => 
  array (
    0 => 
    array (
      'url' => 'autowire',
      'controller' => 'App\\Containers\\Demo\\Controller\\IndexController',
      'action' => 'autowire',
      'middleware' => 
      array (
      ),
      'method' => 'GET',
    ),
  ),
);