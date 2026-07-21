<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Controller;

use App\Containers\Demo\Contract\AsClosureInterface;
use App\Containers\Demo\Contract\AsFactoryObjectInterface;
use App\Containers\Demo\Contract\AsFactoryStringInterface;
use App\Containers\Demo\Contract\AsObjectInterface;
use App\Containers\Demo\Contract\AsStringInterface;
use App\Containers\Demo\Contract\CacheInterface;
use App\Containers\Demo\Contract\SmsSenderInterface;
use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\DemoController;
use App\Containers\Demo\Service\SomeDemoService;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;
use Rudra\Routing\Attributes\AfterMiddleware;
use Rudra\Routing\Attributes\Middleware;
use Rudra\Routing\Attributes\Routing;

class IndexController extends DemoController
{
    /**
     * 💉 Automatic Dependency Injection
     * 
     * Notice: I never wrote `new \stdClass()` — the framework did it for me.
     * Just type-hint the parameter, and the container resolves it.
     */
    public function __construct(\stdClass $std)
    {
        parent::__construct();
        
        $std->injected_by = 'Rudra Container';
        $std->proof = 'No `new` keyword was used in this constructor';
        
        $this->info([
            'feature' => 'Automatic Dependency Injection',
            'object' => $std,
            'note' => 'Framework created and injected this object automatically',
        ]);
    }

    /**
     * 🎯 Routing Patterns Demo
     * 
     * Rudra supports multiple route types on a single method:
     * 
     * 1. Static routes (exact match):
     *    #[Routing(url: '')]              → matches homepage /
     *    #[Routing(url: 'about/team')]    → matches /about/team exactly
     * 
     * 2. Regex-constrained parameters (:[pattern]):
     *    #[Routing(url: 'page/:[\d]{1,3}')]   → matches /page/1 to /page/999
     *    #[Routing(url: 'lang/:[a-z]{1,3}')]  → matches /lang/en, /lang/ru
     *    The regex validates input BEFORE the method is called.
     * 
     * 3. Dynamic parameters (:name):
     *    #[Routing(url: 'user/:name')]    → matches /user/anything
     *    Captured value is passed to method parameter with same name.
     * 
     * 🔄 Middleware Pipeline:
     *    #[Middleware]      → executes BEFORE the action (request phase)
     *    #[AfterMiddleware] → executes AFTER the action (response phase)
     *    Order matters: First → Second → Action → Second → First
     */

    // ─── ROUTING PATTERNS ───────────────────────────────────────
    #[Routing(url: '')]                              // Static: homepage
    #[Routing(url: 'about/team')]                    // Static: exact path
    #[Routing(url: 'page/:[\d]{1,3}')]               // Regex: numeric constraint
    #[Routing(url: 'lang/:[a-z]{1,3}')]              // Regex: alphabetic constraint
    #[Routing(url: 'user/:name')]                    // Dynamic: any string → $name

    // ─── BEFORE MIDDLEWARE (request phase) ──────────────────────
    #[Middleware(name: 'App\Containers\Demo\Middleware\FirstMiddleware')]
    #[Middleware(name: 'App\Containers\Demo\Middleware\SecondMiddleware')]

    // ─── AFTER MIDDLEWARE (response phase) ──────────────────────
    #[AfterMiddleware(name: 'App\Containers\Demo\Middleware\FirstMiddleware')]
    #[AfterMiddleware(name: 'App\Containers\Demo\Middleware\SecondMiddleware')]
    public function attributes(string $name = 'John'): void
    {
        // ─── EVENT SYSTEM ─────────────────────────────────────
        // Fire event with rich context — listeners can inspect all fields
        Dispatcher::dispatch('message', [
            'action'     => 'controller.invoked',
            'controller' => static::class,
            'method'     => __FUNCTION__,
            'user'       => $name,
            'timestamp'  => date('Y-m-d H:i:s'),
        ]);
        
        $this->info([
            'feature' => 'Event Dispatching (with payload)',
            'event'   => 'message',
            'data'    => [
                'action'     => 'controller.invoked',
                'controller' => static::class,
                'method'     => __FUNCTION__,
                'user'       => $name,
            ],
            'note'    => 'All listeners receive this structured payload',
        ]);

        $this->info("Hello $name");
        
        // Access container-specific settings
        $this->info(config('demo.settings'));

        // ─── NOTIFICATION (STATELESS) ─────────────────────────
        // Lightweight notification — no payload, just a signal
        Dispatcher::notify('one');
        
        $this->info([
            'feature' => 'Event Notification (stateless)',
            'event'   => 'one',
            'note'    => 'Unlike dispatch(), notify() sends no payload — just a signal',
        ]);

        // ─── CONTENT RESOLUTION ───────────────────────────────
        // Cache-first strategy: try cache, fall back to view rendering
        data([
            'content' => cache(['mainpage']) ?? view(['index', 'mainpage']),
        ]);

        // ─── FINAL RENDER ─────────────────────────────────────
        render('layout', data());
    }

    /**
     * 🔄 Autowiring Demo
     * 
     * The container automatically resolves ALL dependencies by type-hints.
     * No manual binding, no factory methods — just declare what you need.
     * 
     * How it works:
     * 1. Container sees interface/class in method signature
     * 2. Automatically instantiates or retrieves from container
     * 3. Injects ready-to-use instances into the method
     */
    #[Routing(url: 'autowire')]
    public function autowire(
        RudraInterface $rudra,
        UserRepositoryInterface $user,
        SmsSenderInterface $smsSender,
        CacheInterface $cache,
        SomeDemoService $service
    ): void
    {
        // Use the autowired service
        $result = $service->greet(1);
        
        // Dump all resolved dependencies + service result
        dd([
            'feature' => 'Autowiring (Automatic Dependency Resolution)',
            'note' => 'Container resolved all dependencies automatically by type-hints',
            'dependencies' => [
                'rudra' => [
                    'interface' => RudraInterface::class,
                    'resolved' => get_class($rudra),
                ],
                'user' => [
                    'interface' => UserRepositoryInterface::class,
                    'resolved' => get_class($user),
                ],
                'smsSender' => [
                    'interface' => SmsSenderInterface::class,
                    'resolved' => get_class($smsSender),
                ],
                'cache' => [
                    'interface' => CacheInterface::class,
                    'resolved' => get_class($cache),
                ],
                'service' => [
                    'class' => SomeDemoService::class,
                    'resolved' => get_class($service),
                ],
            ],
            'service_result' => $result,
        ]);
    }

    /**
     * 📦 Container Binding Patterns Demo
     * 
     * Demonstrates 5 different ways to register dependencies in Rudra Container:
     * 
     * 1. As String (class name) → container instantiates on first access
     * 2. As Object (instance)   → container returns the same instance always
     * 3. As Factory String      → container calls factory class to create instance
     * 4. As Factory Object      → container calls factory instance to create
     * 5. As Closure             → container executes closure to create instance
     * 
     * See config files for binding examples:
     * - App\Containers\Demo\config.php (Demo container bindings)
     * - App\Ship\config.php (Ship-level bindings - shared across all containers)
     */
    #[Routing(url: 'example')]
    public function example(
        AsStringInterface $string, 
        AsObjectInterface $object, 
        AsFactoryStringInterface $stringAsFactory,
        AsFactoryObjectInterface $objectAsFactory,
        AsClosureInterface $closure
    ): void
    {
        dd([
            'feature' => 'Container Binding Patterns',
            'note' => '5 ways to register dependencies — all resolved automatically via type-hints',
            
            'bindings' => [
                '1. As String (class name)' => [
                    'interface' => AsStringInterface::class,
                    'binding' => 'AsString::class (string)',
                    'resolved' => get_class($string),
                    'note' => 'Container instantiates class on first access',
                    'instance' => $string,
                ],
                
                '2. As Object (instance)' => [
                    'interface' => AsObjectInterface::class,
                    'binding' => 'new AsObject() (object)',
                    'resolved' => get_class($object),
                    'note' => 'Container returns the same pre-created instance',
                    'instance' => $object,
                ],
                
                '3. As Factory String' => [
                    'interface' => AsFactoryStringInterface::class,
                    'binding' => 'AsStringFactory::class (factory class name)',
                    'resolved' => get_class($stringAsFactory),
                    'note' => 'Container calls factory class to create instance',
                    'instance' => $stringAsFactory,
                ],
                
                '4. As Factory Object' => [
                    'interface' => AsFactoryObjectInterface::class,
                    'binding' => 'new AsObjectFactory() (factory instance)',
                    'resolved' => get_class($objectAsFactory),
                    'note' => 'Container calls factory instance to create',
                    'instance' => $objectAsFactory,
                ],
                
                '5. As Closure' => [
                    'interface' => AsClosureInterface::class,
                    'binding' => 'fn() => new AsClosure() (closure)',
                    'resolved' => get_class($closure),
                    'note' => 'Container executes closure to create instance',
                    'instance' => $closure,
                ],
            ],
            
            'container_state' => [
                'bindings' => Rudra::binding(),
                'waiting' => Rudra::waiting(),
            ],
        ]);
    }
}
