<?php

/**
 * Date: 17.04.17
 * Time: 14:19
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Http\Middleware;


use App\Http\BaseMiddleware;


/**
 * Class MainMiddleware
 *
 * @package App\Http\Middleware
 */
class MainMiddleware extends BaseMiddleware
{

    /**
     * @param null $middleware
     *
     * @return bool
     */
    public function __invoke($middleware = null)
    {
        $middleware = $this->handleArray($middleware);

        // StartMiddleware

        if ($middleware[0][1]['int'] % 2) {
            echo json_encode($_SERVER);
        }

        $this->container()->set('middleware', 'middleware', 'raw');

        // EndMiddleware

        $this->next($middleware);
    }
}