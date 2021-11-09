<?php

namespace App\Utils;

use Rudra\Container\Facades\Request;
use Rudra\Validation\ValidationFacade as Validation;
use Rudra\Container\Facades\Session;

trait HelperTrait
{
    protected function validationErrors(array $result, string $type = 'alert'): void
    {
        if ($type == 'API') {
            $this->container()->jsonResponse($this->validation()->flash($result, []));
            exit();
        }

        $data = ($type == 'value') ? Validation::getChecked($result, ['csrf_field'])
            : Validation::getAlerts($result, ['csrf_field']);

        foreach ($data as $key => $message) {
            Session::set([$type, [$key => $message]]);
        }
    }

    protected function getIdFromSlug(string $slug): string
    {
        $slug = strip_tags($slug);

        return (strpos($slug, '_') !== false) ? strstr($slug, '_', true) : $slug;
    }

    protected function redirectToSelf(string $uri, $page = null)
    {
        if (isset($page)) {
            $page = $this->validation()->sanitize($page)->required()->run();

            if ($this->validation()->access($page)) {
                return $this->redirect($uri . '/page/' . $page[0]);
            }
        }

        return $this->redirect($uri);
    }

    protected function searchArrayKey($array, $arrayKey)
    {
        foreach ($array as $key => $value) {
            if (stristr($key, $arrayKey) !== false) {
                return $key;
            }
        }
    }

    protected function dateFormat($value)
    {
        $date  = \DateTime::createFromFormat('d.m.Y H:i', $value);
        $value = $date->format('Y-m-d H:i');

        return $value;
    }

    private function handleField(string $field, string $checkBoxName)
    {
        if ($field === $checkBoxName) {
            return (Request::post()->has($field)) ? '1' : '0';
        }

        return Request::post()->get($field);
    }

    protected function checkbox($value)
    {
        return ($value) ? '1' : '0';
    }
}
