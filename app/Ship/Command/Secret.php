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
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class Secret
{
    /**
     * 🔑 Cryptographic Secret Generator
     * 
     * CLI utility that generates a cryptographically secure random secret string.
     * Useful for creating application keys (APP_KEY), CSRF tokens, encryption salts,
     * or any other secrets required by the framework configuration.
     * 
     * How it works:
     * 1. Simulates an HTTP request context (REMOTE_ADDR, HTTP_USER_AGENT)
     *    because the Request facade is initialized during framework bootstrapping.
     * 2. Generates 8 random bytes using PHP's CSPRNG (random_bytes).
     * 3. Converts the bytes to a 16-character hexadecimal string using bin2hex().
     * 4. Outputs the resulting secret in green color.
     * 
     * Output format:
     *  - Length: 16 characters
     *  - Charset: Hexadecimal (0-9, a-f)
     *  - Example: a1b2c3d4e5f6a7b8
     * 
     * Note: Uses `random_bytes()` which is cryptographically secure.
     * Do NOT use standard `rand()` or `mt_rand()` for generating secrets.
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

        Cli::printer(bin2hex(random_bytes(8)) . PHP_EOL, "light_green");
    }
}
