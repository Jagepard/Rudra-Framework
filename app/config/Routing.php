<?php
/**
 * Date: 15.07.16
 * Time: 17:40
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Config;


use Rudra\Router;
use Main\Main;

class Routing
{
    /**
     * @var Route
     */
    private $app;

    /**
     * Routing constructor.
     * @param Router $app
     */
    public function __construct(Router $app)
    {
        $this->app = $app;
    }

    /**
     * Устанавливаем Routing
     */
    public function run()
    {
        $this->app->set('',
            ['Main\\MainController', 'actionIndex']
        );

        if (!$this->app->isToken()) $this->app->error404(new Main());
    }
}