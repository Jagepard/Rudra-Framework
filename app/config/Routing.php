<?php

/**
 * Date: 15.07.16
 * Time: 17:40
 * 
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Config;

use Rudra\Router;
use App\Main\Module;

class Routing
{
    /**
     * @var Router
     */
    private $router;

    /**
     * Routing constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Устанавливаем приоритет Routing
     */
    public function run()
    {
        $this->getRouter()->annotation('MainController', 'actionIndex');

        if (!$this->getRouter()->isToken()) $this->getRouter()->error404(new Module());
    }

    /**
     * @return \Rudra\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

}