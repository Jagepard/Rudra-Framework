<?php

namespace App\Common;

trait CommonHelper
{
    protected function getPaginationLinks($page, $perPage, $numRows = null) :array
    {
        $this->setPagination($page, $perPage, isset($numRows) ? $numRows : $this->model()->numRows());

        return $this->pagination()->getLinks();
    }

    protected function getPaginationViewData($page, $limit, $uri, &$links) :array
    {
        return [
            'limit' => $limit,
            'page'  => $page['id'],
            'uri'   => $uri,
            'links' => $links
        ];
    }

    protected function validationErrors(array $result, string $type = 'alert') :void
    {
        if ($type == 'API') {
            $this->container()->jsonResponse($this->validation()->flash($result, []));
            exit();
        }

        $data = ($type == 'value') ? $this->validation()->get($result, ['csrf_field'])
            : $this->validation()->flash($result, ['csrf_field']);

        foreach ($data as $key => $message) {
            $this->setSession($type, $message, $key);
        }
    }

    protected function getIdFromSlug(string $slug) :string
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

    protected function checkbox($value)
    {
        return ($value) ? '1' : '0';
    }

    public function model()
    {
        return $this->model;
    }

    public function setModel(string $modelName): void
    {
        $this->model = $this->container()->new($modelName);
    }
}
