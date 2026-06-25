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
        Cli::printer("Enter cache type [database, routes, templates, twig](empty for all): ", "magneta");
        $type = str_replace(PHP_EOL, "", Cli::reader());

        $folderPath = !empty($type)
            ? dirname(__DIR__, 3) . "/storage/cache/$type"
            : dirname(__DIR__, 3) . "/storage/cache";

        if ($this->deleteDirectory($folderPath)) {
            Cli::printer(!empty($type)
                ? "✅ Cache $type was cleared" . PHP_EOL
                : "✅ Cache was cleared" . PHP_EOL, "light_green");
        } else {
            Cli::printer(!empty($type)
                ? "❌ Cache type '$type' does not exist." . PHP_EOL
                : "⚠️  The directory does not exist or the cache was cleared." . PHP_EOL, !empty($type) ? "red" : "yellow");
        }
    }

    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        foreach (glob($dir . '/*') as $file) {
            is_dir($file) ? $this->deleteDirectory($file) : unlink($file);
        }

        return rmdir($dir);
    }
}
