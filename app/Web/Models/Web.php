<?php

namespace App\Web\Models;

use App\Web\Repository\WebRepository;
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
