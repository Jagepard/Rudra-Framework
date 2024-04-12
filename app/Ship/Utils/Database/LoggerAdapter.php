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
        if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $this->driver = new MySQL($this->table);
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $this->driver = new PgSQL($this->table);
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            $this->driver = new SQLite($this->table);
        }
    }

    public function up(): void
    {
        $query = QBFacade::create($this->table)
            ->integer('id', '', true)
            ->string('name')
            ->created_at()
            ->pk('id')
            ->close()
            ->get();

        Rudra::get("DSN")->prepare("$query")->execute();
    }

    public function isTable()
    {
        return $this->driver->isTable();
    }

    public function writeLog(string $name): void
    {
        $this->driver->writeLog($name);
    }

    public function checkLog(string $name)
    {
        $stmt = Rudra::get("DSN")->prepare(QBFacade::select()
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
