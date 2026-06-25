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
        Cli::printer("🌐 Rudra is running:", "cyan");
        exec('php -S 127.0.0.1:8000 -t public');
    }
}
