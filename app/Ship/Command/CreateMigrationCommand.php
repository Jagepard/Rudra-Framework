<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateMigrationCommand extends FileCreator
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
        $className = ucfirst($table) . $date;

        if (!empty($container)) {
            $namespace = 'App\Containers\\' . $container . '\Migration';

            $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migration/"), "{$className}_migration.php"],
                $this->createMigration($className, $table, $namespace)
            );
        } else {
            $namespace = "App\Ship\Migration";

            $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migration/"), "{$className}_migration.php"],
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
<?php

namespace $namespace;

use Rudra\Model\QBFacade;
use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up(): void
    {
        \$table = "$table";

        Rudra::get("DSN")->prepare(QBFacade::create(\$table)
            ->integer('id', '', true)
            ->created_at()
            ->updated_at()
            ->pk('id')
            ->close()
            ->get()
        )->execute();
    }
}\r\n
EOT;
    }
}
