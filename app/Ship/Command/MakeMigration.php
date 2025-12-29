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

class MakeMigration extends FileCreator
{
    /**
     * Creates a file with Migration data
     * -----------------------------
     * Создает файл с данными Migration
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter table name: ", "magneta");
        $table = str_replace(PHP_EOL, "", Cli::reader());

        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        $date      = date("_dmYHis");
        $className = trim(ucfirst($table) . $date);

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $namespace = 'App\Containers\\' . $container . '\Migration';

            $this->writeFile([Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migration/", "{$className}_migration.php"],
                $this->createMigration($className, $table, $namespace)
            );
        } else {
            $namespace = "App\Ship\Migration";

            $this->writeFile([Rudra::config()->get('app.path') . "/app/Ship/Migration/", "{$className}_migration.php"],
                $this->createMigration($className, $table, $namespace)
            );
        }
    }

    /**
     * Creates class data
     * ------------------
     * Создает данные класса
     *
     * @param string $className
     * @param string $table
     * @param string $namespace
     * @return string
     */
    private function createMigration(string $className, string $table, string $namespace): string
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

namespace $namespace;

use Rudra\Model\Schema;
use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up(): void
    {
        Schema::create('$table', function (\$table) {
            \$table->integer('id', autoincrement: true)
                ->created_at()
                ->updated_at()
                ->pk('id');
        })->execute();
    }
}\r\n
EOT;
    }
}
