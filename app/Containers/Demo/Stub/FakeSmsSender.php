<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Stub;

use App\Containers\Demo\Contract\SmsSenderInterface;

class FakeSmsSender implements SmsSenderInterface
{
    public array $sent = [];

    public function send(string $phone, string $message): bool
    {
        $this->sent[] = compact('phone', 'message');
        echo "[STUB] Captured SMS: $phone → $message\n";
        return true;
    }
}
