<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

use Rudra\Router\RouterFacade as Router;

// ─── MANUAL ROUTE REGISTRATION ──────────────────────────────────
// Define routes explicitly using Router facade (closure-based)
// Useful for simple routes, API endpoints, or quick prototypes
if (php_sapi_name() != "cli") {
    Router::get("callable/:name", function ($name) {
            echo "Hello $name!";
        }
    );
}

// ─── AUTOMATIC ROUTE REGISTRATION (via Attributes) ──────────────
// Return array of controllers — Rudra will:
// 1. Scan each controller class
// 2. Parse #[Routing] attributes from all public methods
// 3. Register routes automatically based on attribute patterns
//
// This is the recommended approach for structured applications.
// See IndexController for examples of #[Routing] attributes.

return [
    \App\Containers\Demo\Controller\IndexController::class,
];
