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
use Rudra\Container\Facades\Rudra;

class CacheClear
{
    /**
     * 🧹 Interactive Cache Clearer
     * 
     * CLI command that removes cached files from the storage directory.
     * Supports clearing specific cache types or the entire cache at once.
     * 
     * Workflow:
     *  1. Enter cache type → (database, routes, templates, twig) or leave empty for ALL
     *  2. Targets the corresponding folder in App\storage\cache\
     *  3. Deletes the directory and outputs a colorized status message
     * 
     * Supported types:
     *  - database  : App\storage\cache\database
     *  - routes    : App\storage\cache\routes
     *  - templates : App\storage\cache\templates
     *  - twig      : App\storage\cache\twig
     *  - (empty)   : App\storage\cache (clears everything)
     * 
     * @see self::deleteDirectory() for the actual deletion logic
     */
    public function actionIndex(): void
    {
        echo PHP_EOL;
        Cli::printer("🧹 Cache Clearer" . PHP_EOL, "light_magenta");
        echo PHP_EOL;

        Cli::printer(" Enter cache type [database, routes, templates, twig] (empty for all): ", "cyan");
        $type = trim(Cli::reader());

        $allowedTypes = ['database', 'routes', 'templates', 'twig'];
        if (!empty($type) && !in_array($type, $allowedTypes, true)) {
            Cli::printer("❌ Invalid cache type. Allowed: " . implode(', ', $allowedTypes) . PHP_EOL, "light_red");
            return;
        }

        $cacheBasePath = Rudra::config()->get('app.path') . '/storage/cache';
        $folderPath = !empty($type) ? $cacheBasePath . "/$type" : $cacheBasePath;

        if (!is_dir($folderPath)) {
            Cli::printer("⚠️  Cache directory does not exist: $folderPath" . PHP_EOL, "light_yellow");
            return;
        }

        // Delete contents (files and subdirectories), keep the folder itself
        if ($this->clearDirectory($folderPath)) {
            $message = !empty($type) 
                ? "✅ Cache '$type' was cleared" . PHP_EOL 
                : "✅ All caches were cleared" . PHP_EOL;
            Cli::printer($message, "light_green");
        } else {
            Cli::printer("❌ Failed to clear cache: $folderPath" . PHP_EOL, "light_red");
        }
    }

    /**
     * Recursively deletes all contents of a directory (files and subdirectories).
     * The directory itself is preserved.
     */
    protected function clearDirectory(string $dir): bool
    {
        $items = scandir($dir);
        if ($items === false) {
            return false;
        }

        foreach ($items as $item) {
            // Skip current and parent directory references
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                // Recursively clear subdirectory
                if (!$this->clearDirectory($path)) {
                    return false;
                }
                // Remove empty subdirectory
                if (!rmdir($path)) {
                    return false;
                }
            } else {
                // Delete file
                if (!unlink($path)) {
                    return false;
                }
            }
        }

        return true;
    }
}
