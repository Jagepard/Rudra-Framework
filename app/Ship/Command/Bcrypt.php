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

class Bcrypt
{
    /**
     * 🔐 Bcrypt Password Hasher
     * 
     * CLI utility that generates a bcrypt hash for a given password.
     * Useful for manually creating user records in the database, seeding test data,
     * or verifying password hashing behavior.
     * 
     * How it works:
     * 1. Simulates an HTTP request context (REMOTE_ADDR, HTTP_USER_AGENT)
     *    because Auth::bcrypt() relies on the Request facade internally.
     * 2. Prompts the user to enter a plain-text password via stdin.
     * 3. Outputs the resulting bcrypt hash in green color.
     * 
     * Workflow:
     *  - Enter password → (typed interactively, trimmed)
     *  - Output         → $2y$10$... (bcrypt hash string)
     * 
     * Note: The hash includes a random salt each time, so the same password
     * will produce different hashes on each run — this is expected bcrypt behavior.
     * 
     * @see Auth::bcrypt() for the underlying hashing implementation
     */
    public function actionIndex(): void
    {
        // Initialize server variables required by Auth component
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        // Prompt for password until non-empty value is provided
        $password = '';
        while (empty($password)) {
            Cli::printer("🔐 Enter password: ", "cyan");
            $password = trim(Cli::reader());

            if (empty($password)) {
                Cli::printer("⚠️  Password cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
        }

        // Generate and display bcrypt hash
        $hash = Auth::bcrypt($password);
        Cli::printer("✅ Bcrypt hash:" . PHP_EOL, "light_green");
        Cli::printer($hash . PHP_EOL, "cyan");
    }
}
