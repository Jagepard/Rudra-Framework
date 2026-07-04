<?php

declare(strict_types=1);

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
use Rudra\Container\Facades\Rudra;

class Serve
{
    private const int PORT = 8000;
    private const string HOST = '127.0.0.1';

    /**
     * 🚀 Development Server Launcher
     * 
     * CLI command that starts PHP's built-in development server for local testing.
     * Provides a zero-config way to run Rudra Framework without Apache/Nginx setup.
     * 
     * How it works:
     * 1. Spawns PHP's built-in web server via `passthru()`
     * 2. Binds to 127.0.0.1:8000 (localhost only)
     * 3. Sets `public/` as the document root
     * 4. Blocks the terminal until interrupted (Ctrl+C)
     * 
     * Server details:
     *  - URL       : http://127.0.0.1:8000
     *  - Host      : 127.0.0.1 (localhost only, not accessible from network)
     *  - Port      : 8000
     *  - Doc root  : public/
     * 
     * Usage:
     *  - Run this command to start the dev server
     *  - Open http://127.0.0.1:8000 in your browser
     *  - Press Ctrl+C to stop the server
     * 
     * ⚠️  WARNING: This is a DEVELOPMENT server only!
     * Do NOT use in production — it's not optimized for performance or security.
     * For production, use Apache, Nginx, or another production-grade web server.
     */
    public function actionIndex(): void
    {
        $publicPath = Rudra::config()->get('app.path') . '/public';

        if (!$this->validateEnvironment($publicPath)) {
            return;
        }

        $this->renderHeader();
        $this->renderServerInfo($publicPath);
        
        passthru(sprintf(
            'php -S %s:%d -t %s',
            self::HOST,
            self::PORT,
            escapeshellarg($publicPath)
        ));
    }

    private function validateEnvironment(string $publicPath): bool
    {
        if (!is_dir($publicPath)) {
            Cli::printer("\n❌  Public directory not found: {$publicPath}\n\n", "light_red");
            return false;
        }

        if ($this->isPortInUse()) {
            Cli::printer("\n⚠️  Port " . self::PORT . " is already in use\n\n", "light_yellow");
            return false;
        }

        return true;
    }

    private function renderHeader(): void
    {
        $phpVersion = PHP_VERSION;
        $line = str_repeat('━', 54);
        
        Cli::printer("{$line}\n", "dark_gray");
        Cli::printer(sprintf("  🚀  Rudra Development Server  (PHP %s)\n", $phpVersion), "light_green");
        Cli::printer("{$line}\n", "dark_gray");
    }

    private function renderServerInfo(string $publicPath): void
    {
        $url = "http://" . self::HOST . ":" . self::PORT;
        
        Cli::printer(sprintf("  🌐  %s\n", $url), "light_cyan");
        Cli::printer(sprintf("  📁  %s\n", $publicPath), "light_gray");
        Cli::printer(sprintf("  🛑  %s\n\n", "Press Ctrl+C to stop"), "light_yellow");
        Cli::printer(str_repeat('━', 54) . "\n\n", "dark_gray");
    }

    private function isPortInUse(): bool
    {
        $connection = @fsockopen(self::HOST, self::PORT, $errno, $errstr, 1);
        
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
