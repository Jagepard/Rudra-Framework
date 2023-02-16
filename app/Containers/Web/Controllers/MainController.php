<?php

namespace App\Containers\Web\Controllers;

use App\Containers\Web\WebController;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

class MainController extends WebController
{
    /**
     * @Routing(url = '')
     * @Routing(url = 'name/{name}')
     * 
     * @Middleware(name = 'App\Containers\Web\Middleware\FirstMiddleware')
     * @Middleware(name = 'App\Containers\Web\Middleware\SecondMiddleware')
     * 
     * @AfterMiddleware(name = 'App\Containers\Web\Middleware\FirstMiddleware')
     * @AfterMiddleware(name = 'App\Containers\Web\Middleware\SecondMiddleware')
     */
    public function annotations(string $name = 'John')
    {
        data([
            "content" => cache(['mainpage', 'now']) ?? view(["index", 'mainpage']),
        ]);

        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");

        Dispatcher::notify('one');

        dump(PHP_VERSION);
        dump(__METHOD__);

        render("layout", data());
    }

    #[Routing(url: '')]
    #[Routing(url: 'name/{name}')]
    #[Middleware(name: 'App\Containers\Web\Middleware\FirstMiddleware')]
    #[Middleware(name: 'App\Containers\Web\Middleware\SecondMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Web\Middleware\FirstMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Web\Middleware\SecondMiddleware')]
    public function attributes(string $name = 'John')
    {
        data([
            "content" => cache(['mainpage', 'now']) ?? view(["index", 'mainpage']),
        ]);

        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");

        Dispatcher::notify('one');
        dump(PHP_VERSION);
        dump(__METHOD__);

        render("layout", data());
    }
}
