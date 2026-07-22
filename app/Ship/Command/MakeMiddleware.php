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

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class MakeMiddleware extends FileCreator
{
    use CamelCaseInputTrait;

    /**
     * 🛡️ Interactive Middleware Generator
     * 
     * CLI wizard that scaffolds a Middleware class following Porto architecture.
     * 
     * Workflow:
     *  1. Enter middleware name → becomes class name (e.g. "Auth" → "AuthMiddleware")
     *  2. Enter container name  → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated file:
     *  - App\Containers\{Name}\Middleware\{Name}Middleware.php
     * 
     * Validates container existence before writing.
     * 
     * @see self::createClass() for template generation
     */
    public function actionIndex(): void
    {
        $prefix = $this->getValidCamelCaseName("🛡️ Enter middleware name: ", "Middleware");
        $container = $this->getValidCamelCaseName("📦 Enter container: ", "Container");
        $className = $prefix . 'Middleware';
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $middlewarePath = $containerPath . "Middleware/";
        $middlewareFile = "{$className}.php";

        if (file_exists($middlewarePath . $middlewareFile)) {
            Cli::printer("⚠️  Middleware '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        $this->writeFile(
            [$middlewarePath, $middlewareFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Middleware '$className' was created in container '$container'" . PHP_EOL, "light_green");
    }

    protected function createClass(string $className, string $container): string
    {
        return <<<EOT
<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\\{$container}\Middleware;

use Rudra\Router\RouterFacade as Router;

class {$className}
{
    public function __invoke(array \$next, ...\$params)
    {
        dump(__CLASS__);
        
        if (\$next) {
            Router::handleMiddleware(\$next);
        }
    }
}

EOT;
    }
}
