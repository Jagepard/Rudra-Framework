<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Stub\AsFactoryObject;
use Rudra\Container\Interfaces\FactoryInterface;

class AsObjectFactory implements FactoryInterface
{
    #[\Override]
    public function create(): object
    {
        return new AsFactoryObject();
    }
}
