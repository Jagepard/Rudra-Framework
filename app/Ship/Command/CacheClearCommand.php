<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class CacheClearCommand
{
    public function actionIndex(): void
    {
        Cli::printer("Enter cache type [database, routes, templates](empty for all): ", "magneta");
        $type = str_replace(PHP_EOL, "", Cli::reader());

        $folderPath = !empty($type)
            ? dirname(__DIR__, 2) . "/cache/$type"
            : dirname(__DIR__, 2) . "/cache";

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

    /**
     * @param string $dir
     * @return bool
     */
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
