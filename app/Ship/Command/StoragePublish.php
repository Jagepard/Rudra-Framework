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

class StoragePublish
{
    /**
     * Publish (symlink) every entry from storage/public into public/.
     */
    public function actionIndex(): void
    {
        $storage = config("app_path") . '/storage/public';
        $public  = config("app_path") . '/public';

        // Collect all entries (folders and files) inside storage/public
        $entries = $this->getEntries($storage);

        if (empty($entries)) {
            Cli::printer("⚠️  No entries found in '$storage'.\n", "light_yellow");
            return;
        }

        Cli::printer("Linking storage to public:\n", "light_cyan");

        // Link every entry, no manual selection needed
        foreach ($entries as $name) {
            $this->createSymlink($storage . '/' . $name, $public . '/' . $name);
        }
    }

    /**
     * Return names of all entries (folders and files) inside the given directory.
     * Note: glob() already skips hidden entries like .gitkeep.
     */
    private function getEntries(string $dir): array
    {
        $items = glob($dir . '/*');

        if ($items === false) {
            return [];
        }

        return array_values(array_map('basename', $items));
    }

    /**
     * Create a symbolic link, replacing an existing symlink or empty directory if needed.
     */
    private function createSymlink(string $source, string $destination): void
    {
        if (!is_dir($source) && !is_file($source)) {
            Cli::printer("❌  Error: Source '$source' does not exist.\n", "light_red");
            return;
        }

        // If the destination already exists — remove it (if it's a symlink or an empty directory)
        if (file_exists($destination) || is_link($destination)) {
            if (is_link($destination)) {
                unlink($destination);
                Cli::printer("Existing symlink removed: $destination\n", "light_yellow");
            } elseif (is_dir($destination) && count(scandir($destination)) <= 2) { // empty directory
                rmdir($destination);
                Cli::printer("Empty destination directory removed: $destination\n", "light_yellow");
            } else {
                Cli::printer("❌  Error: Destination '$destination' already exists and is not empty or not a symlink.\n", "light_red");
                return;
            }
        }

        // Create a symbolic link
        if (symlink($source, $destination)) {
            Cli::printer("✅ Symlink created successfully:\n", "light_green");
            Cli::printer("  From: $source\n", "light_cyan");
            Cli::printer("  To:   $destination\n", "light_cyan");
        } else {
            Cli::printer("❌  Failed to create symlink.\n", "light_red");
        }
    }
}
