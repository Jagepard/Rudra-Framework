<?php

declare(strict_types = 1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2017, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Http\Supports;

trait ApiHelper
{

    protected function jsonResponse(array $data)
    {
        $this->container()->jsonResponse($data);
    }

    protected function successMessage(string $message, string $key = 'alert')
    {
        if ($this->hasData('api')) {
            $this->jsonResponse(['success' => [$key => $message]]);
        } else {
            $this->setSession($key, $message, 'success');
            $this->redirect($this->data('uri'));
        }
    }

    protected function errorMessage($errors, string $key = 'alert')
    {
        if ($this->hasData('api')) {
            $this->jsonResponse($errors);
        } else {
            foreach ($errors as $errorKey => $error) {
                $this->setSession($key, $error, $errorKey);
            }

            $this->redirect($this->data('uri'));
        }
    }
}