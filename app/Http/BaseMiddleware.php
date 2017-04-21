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


use Rudra\SetContainerTrait;


/**
 * Class Middleware
 *
 * @package stub
 */
class BaseMiddleware
{

    use SetContainerTrait;

    /**
     * @param null $middleware
     *
     * @return bool
     */
    public function __invoke($middleware = null)
    {
        // StartMiddleware

        if ($middleware[0][1]['int'] % 2) {
            echo json_encode($_SERVER);
        }

        // EndMiddleware

        $this->next($middleware);
    }

    /**
     * @param $middleware
     */
    protected function next($middleware)
    {
        $this->container()->get('router')->handleMiddleware($middleware, 1);
    }
}
