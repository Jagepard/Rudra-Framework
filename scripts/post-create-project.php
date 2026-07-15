<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

/**
 * Post-create-project script for Rudra Framework.
 * Automatically generates app_env.php and detects DDEV environment.
 */

$file    = 'app_env.php';
$example = 'app_env.php.example';

// Create app_env.php from example if it doesn't exist
if (!file_exists($file) && file_exists($example)) {
    copy($example, $file);
}

// Auto-detect DDEV and update only the return statement
if (getenv('DDEV_PROJECT') && file_exists($file)) {
    $content = file_get_contents($file);
    $pos = strrpos($content, 'return');

    if ($pos !== false) {
        // Preserve everything before "return" (including MPL-2.0 header)
        $header = substr($content, 0, $pos);
        file_put_contents($file, $header . "return 'ddev';\n");
    }
}
