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

return [
    // === Сценарий: продакшен-реализации ===
    'contracts' => [
        UserRepositoryInterface::class => 'user_repo_factory',
        SmsSenderInterface::class      => 'twilio_sender',
        CacheInterface::class          => 'redis_cache',
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
        'twilio_sender'     => fn() => new TwilioSmsSender(),
        'redis_cache'       => fn() => new RedisCache(),

        // Заглушки (всегда доступны)
        'fake_user_repo'    => fn() => new FakeUserRepository(),
        'fake_sms_sender'   => fn() => new FakeSmsSender(),
        'array_cache'       => fn() => new ArrayCache(),
    ],
];
