<?php

namespace App\Containers\Demo\Controller;

use stdClass;
use Rudra\Container\Facades\Rudra;
use App\Containers\Demo\DemoController;
use App\Containers\Demo\Factory\StdFactory;
use Rudra\Container\Interfaces\RudraInterface;
use App\Containers\Demo\Interface\TestInterface;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

class IndexController extends DemoController
{
    /**
     * Serves solely for demonstrating Dependency Injection (DI).
     * Without calling parent::__construct(), it breaks the application logic.
     * -------------------------
     * Служит только для демонстрации внедрения зависимостей (DI).
     * Без вызова parent::__construct(); ломает логику приложения.
     *
     * @param  stdClass $std
     */
    public function __construct(stdClass $std)
    {
        parent::__construct();
        $std->name = __METHOD__ . '::Dependency Injection';
        dump($std);
    }

    #[Routing(url: '')]
    #[Routing(url: 'name/name')]
    #[Routing(url: 'named3/:[\d]{1,3}')]
    #[Routing(url: 'namestr/:[a-z]{1,3}')]
    #[Routing(url: 'name/:name')]
    #[Middleware(name: 'App\Containers\Demo\Middleware\FirstMiddleware')]
    #[Middleware(name: 'App\Containers\Demo\Middleware\SecondMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Demo\Middleware\FirstMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Demo\Middleware\SecondMiddleware')]
    public function attributes(stdClass $std, stdClass $asd,stdClass $asw,string $name = 'John'): void
    {
        data([
            "content" => cache(['mainpage']) ?? view(["index", 'mainpage']),
        ]);

        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");
        Dispatcher::notify('one');

        // dump($std);
        // dump(Rudra::get('factory'));
        dump(Rudra::get('callable'));

        Rudra::set(['one',  [new StdFactory()]]);
        dump(Rudra::get('one'));

        Rudra::set(['two',  [fn() => (new StdFactory())->create()]]);
        dump(Rudra::get('two'));

        Rudra::set(['three',  [StdFactory::class]]);
        dump(Rudra::get('three'));

        render("layout", data());
    }

    #[Routing(url: 'autowire')]
    public function autowire(RudraInterface $rudra, stdClass $std, TestInterface $test, StdFactory $factory): void
    {
        dd($rudra, $std, $test, $factory);
    }

    /**
     * @Routing(url = '')
     * @Routing(url = 'name/{name}')
     * 
     * @Middleware(name = 'App\Containers\Demo\Middleware\FirstMiddleware')
     * @Middleware(name = 'App\Containers\Demo\Middleware\SecondMiddleware')
     * 
     * @AfterMiddleware(name = 'App\Containers\Demo\Middleware\FirstMiddleware')
     * @AfterMiddleware(name = 'App\Containers\Demo\Middleware\SecondMiddleware')
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
}
