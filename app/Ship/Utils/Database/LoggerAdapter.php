<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Utils\Database;

use Rudra\Model\QBFacade;
use Rudra\Container\Facades\Rudra;
use App\Ship\Utils\Database\Driver\MySQL;
use App\Ship\Utils\Database\Driver\PgSQL;
use App\Ship\Utils\Database\Driver\SQLite;

class LoggerAdapter
{
    protected string $table;
    protected object $driver;
    protected \PDO $connection;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->connection = Rudra::get('connection');
        $driverName = $this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME);

        $this->driver = match ($driverName) {
            'mysql'  => new MySQL($this->table),
            'pgsql'  => new PgSQL($this->table),
            'sqlite' => new SQLite($this->table),
            default  => throw new \InvalidArgumentException("Unsupported database driver: {$driverName}"),
        };
    }

    public function up(): void
    {
        $query = QBFacade::create($this->table)
            ->integer('id', autoincrement: true)
            ->string('name')
            ->createdAt()
            ->updatedAt()
            ->primaryKey('id')
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

        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}
