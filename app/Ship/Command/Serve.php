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
use Rudra\Container\Facades\Rudra;

class Serve
{
    /**
     * 🚀 Development Server Launcher
     * 
     * CLI command that starts PHP's built-in development server for local testing.
     * Provides a zero-config way to run Rudra Framework without Apache/Nginx setup.
     * 
     * How it works:
     * 1. Spawns PHP's built-in web server via `exec()`
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
        $port = 8000;
        $host = '127.0.0.1';
        $publicPath = Rudra::config()->get('app.path') . '/public';

        // Check if public directory exists
        if (!is_dir($publicPath)) {
            Cli::printer("❌ Public directory not found: $publicPath" . PHP_EOL, "light_red");
            return;
        }

        // Check if port is already in use
        if ($this->isPortInUse($host, $port)) {
            Cli::printer("⚠️  Port $port is already in use. Please stop the other server or choose a different port." . PHP_EOL, "light_yellow");
            return;
        }

        Cli::printer("🚀 Starting Rudra development server..." . PHP_EOL, "light_green");
        Cli::printer("🌐 Server running at: http://$host:$port" . PHP_EOL, "cyan");
        Cli::printer("📁 Document root: $publicPath" . PHP_EOL, "cyan");
        Cli::printer("🛑 Press Ctrl+C to stop the server" . PHP_EOL . PHP_EOL, "light_yellow");

        // Use passthru instead of exec for interactive processes
        // passthru streams output in real-time and handles signals (Ctrl+C) correctly
        passthru("php -S $host:$port -t " . escapeshellarg($publicPath));
    }

    /**
     * Checks if a TCP port is already in use
     */
    protected function isPortInUse(string $host, int $port): bool
    {
        $connection = @fsockopen($host, $port);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
