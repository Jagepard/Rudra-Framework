<?php

namespace App\Ship\Utils\Database;

use Rudra\Model\QBFacade;
use Rudra\Container\Facades\Rudra;
use App\Ship\Utils\Database\Driver\MySQL;
use App\Ship\Utils\Database\Driver\PgSQL;
use App\Ship\Utils\Database\Driver\SQLite;

class LoggerAdapter
{
    protected $driver;
    protected string $table;

    public function __construct()
    {
        if (Rudra::get('connection')->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $this->driver = new MySQL($this->table);
        } elseif (Rudra::get('connection')->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $this->driver = new PgSQL($this->table);
        } elseif (Rudra::get('connection')->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            $this->driver = new SQLite($this->table);
        }
    }

    public function up(): void
    {
        $query = QBFacade::create($this->table)
            ->integer('id', '', true)
            ->string('name')
            ->created_at()
            ->updated_at()
            ->pk('id')
            ->close()
            ->get();

        Rudra::get('connection')->prepare("$query")->execute();
    }

    public function isTable()
    {
        return $this->driver->isTable();
    }

    public function writeLog(string $namespace): void
    {
        $query = Rudra::get('connection')->prepare("
            INSERT INTO {$this->table} (name, created_at, updated_at)
            VALUES (:name, :created_at, :updated_at)
        ");

        $query->execute([
            ':name'        => $namespace,
            ":created_at"  => date('Y-m-d H:i:s'),
            ":updated_at"  => date('Y-m-d H:i:s'),
        ]);
    }


    public function checkLog(string $name)
    {
        $stmt = Rudra::get('connection')->prepare(QBFacade::select()
            ->from($this->table)
            ->where('name = :name')
            ->get()
        );

        $stmt->execute([
            ':name' => $name,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
