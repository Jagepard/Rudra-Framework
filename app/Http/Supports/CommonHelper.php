<?php

namespace App\Http\Supports;

trait CommonHelper
{

    /**
     * @param      $page
     * @param      $perPage
     * @param null $numRows
     *
     * @return array
     */
    protected function getPaginationLinks($page, $perPage, $numRows = null) :array
    {
        $numRows = isset($numRows) ? $numRows : $this->model()->numRows();

        $this->setPagination($page);
        $this->pagination()->setPerPage($perPage);
        $this->pagination()->setCount($numRows);

        return $this->pagination()->getLinks();
    }

    /**
     * @param $page
     * @param $limit
     * @param $uri
     * @param $links
     *
     * @return array
     */
    protected function getPaginationViewData($page, $limit, $uri, &$links) :array
    {
        return [
            'limit' => $limit,
            'page'  => $page['id'],
            'uri'   => $uri,
            'links' => $links
        ];
    }

    /**
     * @param array  $result
     * @param string $type
     */
    protected function validationErrors(array $result, string $type = 'alert') :void
    {
        if ($type == 'API') {
            exit($this->container()->jsonResponse($this->validation()->flash($result, [])));
        }

        $data = ($type == 'value') ? $this->validation()->get($result, ['csrf_field'])
            : $this->validation()->flash($result, ['csrf_field']);

        foreach ($data as $key => $message) {
            $this->setSession($type, $message, $key);
        }
    }

    /**
     * @param $slug
     *
     * @return string
     */
    protected function getIdFromSlug(string $slug) :string
    {
        $slug = strip_tags($slug);

        return (strpos($slug, '_') !== false) ? strstr($slug, '_', true) : $slug;
    }

    /**
     * @param string $uri
     *
     * @return mixed
     */
    protected function redirectToSelf(string $uri)
    {
        $page = $this->validation()->sanitize($this->post('page'))->required()->run();

        if ($this->validation()->access($page)) {
            return $this->redirect($uri . '/page/' . $page[0]);
        }

        return $this->redirect($uri);
    }

    /**
     * @param $array
     * @param $arrayKey
     *
     * @return int|string
     */
    protected function searchArrayKey($array, $arrayKey)
    {
        foreach ($array as $key => $value) {
            if (stristr($key, $arrayKey) !== false) {
                return $key;
            }
        }
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function dateFormat($value)
    {
        $date  = \DateTime::createFromFormat('d.m.Y H:i', $value);
        $value = $date->format('Y-m-d H:i');

        return $value;
    }

    /**
     * @param $value
     *
     * @return int
     */
    protected function checkbox($value)
    {
        return ($value) ? '1' : '0';
    }
}