<?php

namespace App\Ship\Seed;

use Rudra\Auth\AuthFacade as Auth;

class Users_21012021144905_seed extends AbstractSeed
{
    public function create(): void
    {
        $table  = "users";
        $fields = [
            "name"     => "Admin",
            "email"    => "admin@admin.com",
            "password" => Auth::bcrypt('password'),
            "role"     => "admin",
            "status"   => 1,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ];

        $this->execute($table, $fields);
    }
}
