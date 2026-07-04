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
        // Initialize server variables required by Auth/Request components
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        // Display header
        echo PHP_EOL;
        Cli::printer("🔑 Secret Key Rotator" . PHP_EOL, "light_magenta");
        echo PHP_EOL;

        $basePath = Rudra::config()->get('app.path') . '/';
        $files = [
            'config/setting.local.yml',
            'config/setting.ddev.yml',
            'config/setting.production.yml',
        ];

        $updated = 0;
        $skipped = 0;

        foreach ($files as $relativePath) {
            $filePath = $basePath . $relativePath;

            // Check if file exists
            if (!file_exists($filePath)) {
                Cli::printer("⚠️  Skipped (not found): {$relativePath}" . PHP_EOL, "light_yellow");
                $skipped++;
                continue;
            }

            // Check if file is writable
            if (!is_writable($filePath)) {
                Cli::printer("❌ Cannot write: {$relativePath}" . PHP_EOL, "light_red");
                $skipped++;
                continue;
            }

            // Generate new secret (8 bytes = 16 hex chars)
            $secret = bin2hex(random_bytes(8));

            $content = file_get_contents($filePath);

            // Match "secret:" line with or without quotes, preserving YAML structure
            $pattern = '/^secret:\s*(?:[\'"][^\'"]*[\'"]|[^\s#]+).*/m';
            $newContent = preg_replace($pattern, "secret: '{$secret}'", $content);

            // Check if replacement was successful
            if ($newContent === null || $newContent === $content) {
                Cli::printer("⚠️  Could not update (no 'secret:' key found): {$relativePath}" . PHP_EOL, "light_yellow");
                $skipped++;
                continue;
            }

            file_put_contents($filePath, $newContent);
            Cli::printer("✅ {$relativePath}: {$secret}" . PHP_EOL, "light_green");
            $updated++;
        }

        // Summary
        echo PHP_EOL;
        Cli::printer("📊 Summary: {$updated} updated, {$skipped} skipped" . PHP_EOL, "cyan");
    }
}
