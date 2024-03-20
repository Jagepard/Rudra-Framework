<?php

namespace App\Containers\Web\Controller;

use App\Containers\Web\Interface\TestInterface;
use App\Containers\Web\WebController;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

use Rudra\Container\Interfaces\RudraInterface;
use stdClass;

class IndexController extends WebController
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
    public function annotations(string $name = 'John'): void
    {
        data([
            "content" => cache(['mainpage', 'now']) ?? view(["index", 'mainpage']),
        ]);

        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");

        Dispatcher::notify('one');

        dump(__METHOD__);

        render("layout", data());
    }

    #[Routing(url: '')]
    #[Routing(url: 'name/:[\d]{1,3}')]
    #[Middleware(name: 'App\Containers\Web\Middleware\FirstMiddleware')]
    #[Middleware(name: 'App\Containers\Web\Middleware\SecondMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Web\Middleware\FirstMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Web\Middleware\SecondMiddleware')]
    public function attributes(string $name = 'John'): void
    {
        data([
            "content" => cache(['mainpage', 'now']) ?? view(["index", 'mainpage']),
        ]);

        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");

        Dispatcher::notify('one');

        dump(__METHOD__);

        render("layout", data());
    }

    #[Routing(url: 'autowire')]
    public function autowire(RudraInterface $rudra, stdClass $std, TestInterface $test): void
    {
        dd($rudra, $std, $test);
    }
}
