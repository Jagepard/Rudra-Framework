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

    /**
     * Greet user by ID.
     * Returns structured result for debugging/demonstration.
     *
     * @param int $id User ID
     * @return array{success: bool, message: string, user: array|null}
     */
    public function greet(int $id): array
    {
        $user = $this->users->findById($id);
        
        if ($user) {
            $message = "Hello, {$user['name']}!";
            $this->sms->send('+79991234567', $message);
            
            return [
                'success' => true,
                'message' => $message,
                'user' => $user,
                'sms_sent_to' => '+79991234567',
            ];
        }
        
        return [
            'success' => false,
            'message' => "User with ID {$id} not found",
            'user' => null,
        ];
    }
}
