<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Request;

class Secret
{
    /**
     * 🔑 Cryptographic Secret Generator
     * 
     * CLI utility that generates cryptographically secure random secret strings
     * and writes them to all environment configuration files.
     * 
     * Usage:
     *   php rudra secret
     * 
     * Generates unique secrets for:
     *   - config/setting.local.yml
     *   - config/setting.ddev.yml
     *   - config/setting.production.yml
     * 
     * @see random_bytes() for the underlying secure random generation
     * @see bin2hex()      for the hexadecimal conversion
     */
    public function actionIndex(): void
    {
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        $files = [
            'config/setting.local.yml',
            'config/setting.ddev.yml',
            'config/setting.production.yml',
        ];

        foreach ($files as $configFile) {
            if (!file_exists($configFile)) {
                Cli::printer("⚠️  Skipped (not found): {$configFile}" . PHP_EOL, "yellow");
                continue;
            }

            $secret = bin2hex(random_bytes(8));
            $content = file_get_contents($configFile);
            $pattern = "/^secret:\s*['\"][^'\"]*['\"]/m";
            $newContent = preg_replace($pattern, "secret: '{$secret}'", $content);

            if ($newContent === null || $newContent === $content) {
                Cli::printer("⚠️  Could not update: {$configFile}" . PHP_EOL, "yellow");
                continue;
            }

            file_put_contents($configFile, $newContent);
            Cli::printer("✅ {$configFile}: {$secret}" . PHP_EOL, "light_green");
        }
    }
}
