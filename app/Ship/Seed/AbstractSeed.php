<?php

namespace App\Ship\Seed;

use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;

abstract class AbstractSeed
{
    public function __construct()
    {
        Request::server()->set([
            "REMOTE_ADDR" => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla"
        ]);
    }

    abstract public function create();

    protected function createStmtString(array $fields): array
    {
        $insert = [];
        $execute = [];

        foreach ($fields as $key => $data) {
            $insert[] = "{$key}";
            $execute[] = ":{$key}";
        }

        return [implode(",", $insert), implode(",", $execute)];
    }

    protected function execute(string $table, array $fields): void
    {
        $stmtString = $this->createStmtString($fields);

        $query = Rudra::get("DSN")->prepare("
                INSERT INTO {$table} ({$stmtString[0]}) 
                VALUES ({$stmtString[1]})");

        $query->execute($fields);
    }
}
