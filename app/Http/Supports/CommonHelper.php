<?php

namespace App\Http\Supports;

trait CommonHelper
{

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
    public function searchArrayKey($array, $arrayKey)
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
    public function dateFormat($value)
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
    public function checkbox($value)
    {
        return ($value) ? '1' : '0';
    }
}