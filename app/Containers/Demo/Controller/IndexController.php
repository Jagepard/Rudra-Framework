<?php

namespace App\Containers\Demo\Controller;

use App\Containers\Demo\Contract\AsClosureInterface;
use App\Containers\Demo\Contract\AsFactoryObjectInterface;
use App\Containers\Demo\Contract\AsFactoryStringInterface;
use App\Containers\Demo\Contract\AsObjectInterface;
use App\Containers\Demo\Contract\AsStringInterface;

use stdClass;
use Rudra\Container\Facades\Rudra;
use App\Containers\Demo\DemoController;
use Rudra\Container\Interfaces\RudraInterface;
use App\Containers\Demo\Contract\CacheInterface;
use App\Containers\Demo\Contract\ExampleInterface;
use App\Containers\Demo\Service\SomeDemoService;
use App\Containers\Demo\Contract\SmsSenderInterface;
use App\Containers\Demo\Contract\UserRepositoryInterface;
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
    public function attributes(string $name = 'John'): void
    {
        Dispatcher::dispatch('message', __CLASS__);
        $this->info("Hello $name");
        Dispatcher::notify('one');

        data([
            "content" => cache(['mainpage']) ?? view(["index", 'mainpage']),
        ]);

        render("layout", data());
    }

    #[Routing(url: 'autowire')]
    public function autowire(RudraInterface $rudra, UserRepositoryInterface $user, SmsSenderInterface $smsSender, CacheInterface $cache, SomeDemoService $service): void
    {
        $service->greet(1);

        dd($rudra, $user, $smsSender, $cache, $service);
    }

    #[Routing(url: 'example')]
    public function example(
        AsStringInterface $string, 
        AsObjectInterface $object, 
        AsFactoryStringInterface $stringAsFactory,
        AsFactoryObjectInterface $objectAsFactory,
        AsClosureInterface $closure
    ): void
    {
        dump(
            $string, 
            $object,
            $stringAsFactory,
            $objectAsFactory,
            $closure
        );

        dd(Rudra::binding(), Rudra::waiting());
    }
}
