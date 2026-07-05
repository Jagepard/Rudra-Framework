<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jageard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Utils;

use Rudra\Container\Facades\Rudra;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class RudraDumper
{
    /**
     * Register custom dump handler for development environment
     * Shows caller location after dump output
     */
    public static function register(): void
    {
        VarDumper::setHandler(function ($var) {
            $cloner = new VarCloner();
            $dumper = new HtmlDumper();
            $dumper->setStyles(self::styles());
            
            // Render dump
            $dumper->dump($cloner->cloneVar($var));
            
            // Show caller after dump
            self::renderCaller();
        });
    }
    
    /**
     * Render caller info after dump
     * Shows only the direct caller (first frame outside var-dumper)
     * Displays relative path from project root
     */
    private static function renderCaller(): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $appPath = Rudra::config()->get('app.path');
        
        foreach ($trace as $frame) {
            if (!isset($frame['file']) || 
                strpos($frame['file'], 'var-dumper') !== false ||
                strpos($frame['file'], 'RudraDumper.php') !== false) {
                continue;
            }
            
            // Calculate relative path from app.path
            $relativePath = self::getRelativePath($frame['file'], $appPath);
            
            echo sprintf(
                '<div style="background:#2d2d2d; color:#d4d4d4; font:11px monospace; padding:6px 10px; border-left:3px solid #17a2b8; margin:4px 0; border-radius:2px;"><span style="color:#808080;">%s:%d</span> → <span style="color:#569cd6;">%s%s%s()</span></div>',
                htmlspecialchars($relativePath),
                $frame['line'],
                isset($frame['class']) ? htmlspecialchars($frame['class']) : '',
                isset($frame['class']) ? $frame['type'] : '',
                htmlspecialchars($frame['function'])
            );
            
            break;
        }
    }
    
    /**
     * Get relative path from project root
     */
    private static function getRelativePath(string $file, string $basePath): string
    {
        if (!$basePath) {
            return basename($file);
        }
        
        $realFile = realpath($file);
        $realBase = realpath($basePath);
        
        if ($realFile === false || $realBase === false) {
            return basename($file);
        }
        
        // Check if file is within base path
        if (strpos($realFile, $realBase) === 0) {
            $relative = substr($realFile, strlen($realBase) + 1);
            return $relative;
        }
        
        return basename($file);
    }
    
    /**
     * Get custom color styles for HTML dump output
     * Accent line on the left matches backtrace style
     */
    private static function styles(): array
    {
        return [
            'default'   => 'background:#2d2d2d; color:#d4d4d4; line-height:1.6; font:12px "JetBrains Mono", "Fira Code", Consolas, monospace; padding:10px 14px; border-radius:4px; border:1px solid rgba(255,255,255,0.1); border-left:3px solid #17a2b8; font-feature-settings:"calt" 1, "liga" 1;',
            'num'       => 'color:#b5cea8;',
            'const'     => 'color:#b5cea8;',
            'str'       => 'color:#ce9178;',
            'note'      => 'color:#569cd6;',
            'ref'       => 'color:#c586c0;',
            'public'    => 'color:#569cd6;',
            'protected' => 'color:#dcdcaa;',
            'private'   => 'color:#f48771;',
            'meta'      => 'color:#d7ba7d;',
            'key'       => 'color:#9cdcfe;',
            'index'     => 'color:#b5cea8;',
            'ellipsis'  => 'color:#808080;',
            'ns'        => 'user-select:none;',
        ];
    }
}
