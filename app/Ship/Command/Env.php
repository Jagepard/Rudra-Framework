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

use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class Env
{
    /**
     * 🛠️ Interactive Environment Setter (CLI only)
     * 
     * Sets the environment for CLI commands by updating `app_env.php`.
     * 
     * Architecture note:
     *  - For web requests, `index.php` automatically detects the environment:
     *      • 'ddev'       if IS_DDEV_PROJECT env var is 'true'
     *      • 'local'      if running via php -S (cli-server SAPI)
     *      • 'production' otherwise
     *  - For CLI commands, `index.php` is not executed, so the framework 
     *    reads `app_env.php` to determine which `config/setting.{env}.yml` 
     *    file to load. If the file is missing, it safely defaults to 'local'.
     * 
     * This command is useful when you need to run CLI tasks (migrations, seeds, 
     * cache warmup, etc.) against a specific environment without starting a web server.
     */
    public function actionIndex(): void
    {
        Cli::printer("⚙️  Set environment [1. local 2. ddev 3. production]: ", "light_cyan");
        $choice = trim(Cli::reader());

        $environments = [
            '1' => 'local',
            '2' => 'ddev',
            '3' => 'production',
        ];

        if (!array_key_exists($choice, $environments)) {
            Cli::printer("❌ Invalid choice. Please enter 1, 2, or 3." . PHP_EOL, "light_red");
            return;
        }

        $env = $environments[$choice];
        $appPath = Rudra::config()->get('app.path');
        $envFilePath = $appPath . '/app_env.php';
        $exampleFilePath = $appPath . '/app_env.php.example';

        // Create file from example if it doesn't exist
        if (!file_exists($envFilePath)) {
            if (file_exists($exampleFilePath)) {
                copy($exampleFilePath, $envFilePath);
                Cli::printer("📄 app_env.php created from app_env.php.example" . PHP_EOL, "light_cyan");
            } else {
                $fallbackContent = "<?php\n\n/**\n * Environment configuration for CLI commands.\n * Available values: 'local', 'ddev', 'production'\n * \n * Note: For web requests, environment is auto-detected in index.php.\n * This file is used ONLY for CLI commands.\n */\nreturn 'local';\n";
                file_put_contents($envFilePath, $fallbackContent);
                Cli::printer("📄 app_env.php created with default template" . PHP_EOL, "light_cyan");
            }
        }

        // Replace only the last line to preserve the MPL-2.0 header and comments
        $lines = file($envFilePath, FILE_IGNORE_NEW_LINES);
        if ($lines !== false) {
            while (!empty($lines) && trim(end($lines)) === '') {
                array_pop($lines);
            }
            $lines[count($lines) - 1] = "return '{$env}';";
            
            if (file_put_contents($envFilePath, implode("\n", $lines) . "\n") !== false) {
                Cli::printer("✅ Environment set to '{$env}'" . PHP_EOL, "light_green");
            } else {
                Cli::printer("❌ Failed to write to app_env.php. Check permissions." . PHP_EOL, "light_red");
            }
        }
    }
}
