<?php

namespace App\Ship\Utils;

use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Session;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;

class SecurityCollector extends DataCollector implements Renderable
{
    public function getName()
    {
        return 'security';
    }

    public function getWidgets()
    {
        return [
            "security" => [
                "icon" => "user",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "security",
                "default" => "{}"
            ]
        ];
    }

    public function collect()
    {
        try {
            // Проверяем, активна ли сессия
            if (session_status() !== PHP_SESSION_ACTIVE) {
                return [
                    '⚠️ Статус'   => 'Сессия не запущена',
                    '📧 Email'    => '—',
                    '🔗 Действие' => '—'
                ];
            }

            // Проверяем наличие фасадов
            if (!\class_exists(Session::class)) {
                return [
                    '⚠️ Статус'   => 'Фасад Session недоступен',
                    '📧 Email'    => '—',
                    '🔗 Действие' => '—'
                ];
            }

            $user = null;
            if (Session::has("user")) {
                $user = Session::get("user");
            }

            $url = Rudra::config()->get('url') ?? 'http://localhost';

            if ($user && isset($user['email'])) {
                return [
                    '✅ Статус'   => 'Авторизован',
                    '📧 Email'    => $user['email'],
                    '🛡️ Роль'     => $user['role'] ?? 'user',
                    '🚪 Выйти'    => "{$url}/auth/logout"
                ];
            } else {
                return [
                    '👤 Статус'   => 'Гость',
                    '🛡️ Роль'     => 'guest',
                    '🔑 Войти'    => "{$url}/auth/login"
                ];
            }

        } catch (\Throwable $e) {
            return [
                '💥 Ошибка'   => $e->getMessage(),
                '📍 Место'    => basename($e->getFile()) . ':' . $e->getLine(),
                '🔄 Акция'    => 'Проверьте сессию и конфигурацию'
            ];
        }
    }
}
