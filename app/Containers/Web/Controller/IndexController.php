<?php

namespace App\Containers\Web\Controller;

use stdClass;
use App\Containers\Web\WebController;
use App\Containers\Web\Factory\StdFactory;
use Rudra\Container\Interfaces\RudraInterface;
use App\Containers\Web\Interface\TestInterface;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

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
    public function autowire(RudraInterface $rudra, stdClass $std, TestInterface $test, StdFactory $factory): void
    {
        dd($rudra, $std, $test, $factory);
    }
}
