<?php

namespace App\Containers\Demo\Entity;

use Rudra\Model\Entity;

/**
 * @see \App\Containers\Demo\Repository\UsersRepository
 */
class Users extends Entity
{
    public static string $table = "users";
}
