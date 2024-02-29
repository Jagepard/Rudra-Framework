<?php

namespace App\Ship\Seed;

use Rudra\Auth\AuthFacade as Auth;

class Users_21012021144905_seed extends AbstractSeed
{
    public function create()
    {
        $table  = "users";
        $fields = [
            "name"     => "Admin",
            "email"    => "admin@admin.com",
            "password" => Auth::bcrypt(123),
            "role"     => "admin",
            "status"   => 1,
        ];

        $this->execute($table, $fields);
    }
}
