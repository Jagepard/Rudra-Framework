<?php

namespace App\Containers\Web\Models;

use App\Containers\Web\Repository\WebRepository;
use Rudra\Model\Model;

/**
 * @method static array getMenu()
 *
 * @see WebRepository
 */
class Web extends Model
{
    public static string $directory = __DIR__;
}
