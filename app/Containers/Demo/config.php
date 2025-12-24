<?php

use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\Contract\SmsSenderInterface;
use App\Containers\Demo\Contract\CacheInterface;

use App\Containers\Demo\Factory\UserRepositoryFactory;
use App\Containers\Demo\Stub\FakeUserRepository;
use App\Containers\Demo\Stub\FakeSmsSender;
use App\Containers\Demo\Stub\ArrayCache;
use App\Containers\Demo\Service\TwilioSmsSender;
use App\Containers\Demo\Service\RedisCache;

use App\Containers\Demo\Contract\AsFactoryObjectInterface;
use App\Containers\Demo\Contract\AsFactoryStringInterface;
use App\Containers\Demo\Contract\AsClosureInterface;
use App\Containers\Demo\Contract\AsStringInterface;
use App\Containers\Demo\Contract\AsObjectInterface;
use App\Containers\Demo\Factory\AsObjectFactory;
use App\Containers\Demo\Factory\AsStringFactory;
use App\Containers\Demo\Stub\AsClosure;
use App\Containers\Demo\Stub\AsString;
use App\Containers\Demo\Stub\AsObject;

return [
    // === Сценарий: продакшен-реализации ===
    'contracts' => [
        UserRepositoryInterface::class => 'user_repo_factory',
        SmsSenderInterface::class      => 'twilio_sender',
        CacheInterface::class          => 'redis_cache',

        AsStringInterface::class => AsString::class,
        AsObjectInterface::class => new AsObject(),
        AsFactoryStringInterface::class => AsStringFactory::class,
        AsFactoryObjectInterface::class => new AsObjectFactory(),
        AsClosureInterface::class => fn() => new AsClosure(),
    ],

    // === Сценарий: заглушки (раскомментируйте для тестов) ===
    // 'contracts' => [
    //     UserRepositoryInterface::class => 'fake_user_repo',
    //     SmsSenderInterface::class      => 'fake_sms_sender',
    //     CacheInterface::class          => 'array_cache',
    // ],

    'services' => [
        // Продакшен
        'user_repo_factory' => UserRepositoryFactory::class,
        // 'user_repo_factory' => new UserRepositoryFactory(),
        'twilio_sender'     => TwilioSmsSender::class,
        'redis_cache'       => new RedisCache(),

        // Заглушки (всегда доступны)
        'fake_user_repo'    => fn() => new FakeUserRepository(),
        'fake_sms_sender'   => fn() => new FakeSmsSender(),
        'array_cache'       => fn() => new ArrayCache(),
    ],
];
