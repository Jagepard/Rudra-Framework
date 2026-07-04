<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Utils;

use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Session;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;

/**
 * 🔒 Security DebugBar Collector
 * 
 * Displays authentication status and user info in DebugBar.
 * Shows session state, user email, role, and auth links.
 */
class SecurityCollector extends DataCollector implements Renderable
{
    #[\Override]
    public function getName()
    {
        return 'security';
    }

    #[\Override]
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

    #[\Override]
    public function collect()
    {
        try {
            // Check if session is active
            if (session_status() !== PHP_SESSION_ACTIVE) {
                return [
                    '⚠️ Status'  => 'Session not started',
                    '📧 Email'   => '—',
                    '🔗 Action'  => '—'
                ];
            }

            // Check if Session facade is available
            if (!\class_exists(Session::class)) {
                return [
                    '⚠️ Status'  => 'Session facade unavailable',
                    '📧 Email'   => '—',
                    '🔗 Action'  => '—'
                ];
            }

            $user = null;
            if (Session::has("user")) {
                $user = Session::get("user");
            }

            $url = Rudra::config()->get('url') ?? 'http://localhost';

            if ($user && isset($user['email'])) {
                return [
                    '✅ Status'    => 'Authenticated',
                    '📧 Email'     => $user['email'],
                    '🛡️ Role'      => $user['role'] ?? 'user',
                    '🚪 Logout'    => "{$url}/auth/logout"
                ];
            } else {
                return [
                    '👤 Status'    => 'Guest',
                    '🛡️ Role'      => 'guest',
                    '🔑 Login'     => "{$url}/auth/login"
                ];
            }

        } catch (\Throwable $e) {
            return [
                '💥 Error'     => $e->getMessage(),
                '📍 Location'  => basename($e->getFile()) . ':' . $e->getLine(),
                '🔄 Action'    => 'Check session and configuration'
            ];
        }
    }
}
