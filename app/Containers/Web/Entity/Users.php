<?php

namespace App\Containers\Web\Entity;

use Rudra\Model\Entity;

/**
 * @see App\Containers\Web\Repository\UsersRepository
 */
class Users extends Entity
{
    public static string $table = "users";
    public static string $directory = __DIR__;
}
