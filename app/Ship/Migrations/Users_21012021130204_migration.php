<?php

namespace Db\Migrations;

use Rudra\Container\Facades\Rudra;

class Users_21012021130204_migration
{
    public function up()
    {
        $table = "users";

        $query = Rudra::get("DSN")->prepare("
            CREATE TABLE {$table} ( 
            `id` INT NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR(255) NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `password` VARCHAR(255) NOT NULL ,
            `role` VARCHAR(255) NOT NULL ,
            `status` VARCHAR(255) NOT NULL ,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
             PRIMARY KEY (`id`)) ENGINE = InnoDB
         ");

        $query->execute();
    }
}