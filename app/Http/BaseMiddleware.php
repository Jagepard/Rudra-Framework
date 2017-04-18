<?php

/**
 * Date: 17.04.17
 * Time: 14:19
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Http;


use Rudra\ContainerInterface;


/**
 * Class BaseMiddleware
 *
 * @package App\Http
 */
abstract class BaseMiddleware
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Middleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param null $middleware
     *
     * @return bool
     */
    abstract public function __invoke($middleware = null);

    /**
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param $middleware
     */
    protected function next($middleware)
    {
        if (isset($middleware[1])) {
            (new $middleware[1][0]($this->container()))(array_pop($middleware));
        }
    }

    /**
     * @param $middleware
     *
     * @return mixed
     */
    protected function handleArray($middleware)
    {
        if (!is_array($middleware[0])) {
            $middleware[0] = $middleware;
            unset($middleware[1]);

            return $middleware;
        }

        return $middleware;
    }
}