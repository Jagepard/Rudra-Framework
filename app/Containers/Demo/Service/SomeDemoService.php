<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\Contract\SmsSenderInterface;

class SomeDemoService
{
    public function __construct(
        private UserRepositoryInterface $users,
        private SmsSenderInterface $sms
    ) {}

    public function greet(int $id): void
    {
        if ($user = $this->users->findById($id)) {
            $this->sms->send('+79991234567', "Hello, {$user['name']}!");
        }
    }
}