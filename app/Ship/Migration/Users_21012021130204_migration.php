<?php

namespace App\Ship\Migration;

use Rudra\Model\QBFacade;
use Rudra\Container\Facades\Rudra;

class Users_21012021130204_migration
{
    public function up(): void
    {
        $table = "users";

        $qString = QBFacade::create($table)
            ->integer('id', '', true)
            ->string('name')
            ->string('email', 'NOT NULL UNIQUE')
            ->string('password')
            ->string('role')
            ->integer('status', 'DEFAULT 1')
            ->created_at()
            ->updated_at()
            ->pk('id')
            ->close()
            ->get();

        Rudra::get("DSN")->prepare("$qString")->execute();
    }
}
