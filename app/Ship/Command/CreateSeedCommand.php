<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateSeedCommand extends FileCreator
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter table name: ", "magneta");
        $table     = str_replace(PHP_EOL, "", Cli::reader());
        $date      = date("_dmYHis");
        $className = ucfirst($table . $date);

        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        Cli::printer("multiline Seed (yes): ", "magneta");
        $multiline = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));
        $multiline = empty($multiline);

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $namespace = 'App\Containers\\' . $container . '\Seed';

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Seed/", "{$className}_seed.php"],
                $this->createClass($className, $table, $namespace, $multiline)
            );
        } else {
            $namespace = "App\Ship\Seed";

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Ship/Seed/", "{$className}_seed.php"],
                $this->createClass($className, $table, $namespace, $multiline)
            );
        }
    }

    /**
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createClass(string $className, string $table, string $namespace, bool $multiline = false): string
    {
        if ($multiline) {
            return <<<EOT
<?php

namespace {$namespace};

use App\Ship\Seed\AbstractSeed;

class {$className}_seed extends AbstractSeed
{
    public function create(): void
    {
        \$table = "$table";

        \$fieldsArray = [
            [
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];

        foreach (\$fieldsArray as \$fields) {
            \$this->execute(\$table, \$fields);
        }
    }
}\r\n
EOT;
        } else {
            return <<<EOT
<?php

namespace {$namespace};

use App\Ship\Seed\AbstractSeed;

class {$className}_seed extends AbstractSeed
{
    public function create(): void
    {
        \$table = "$table";
        \$fields = [
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ];

        \$this->execute(\$table, \$fields);
    }
}\r\n
EOT;
        }
    }
}
