<?php

namespace App\Http\Supports;

trait HttpErrors
{

    public function error404()
    {
        $this->redirect()->responseCode('404');

        return $this->twig('errors/404.html.twig', [
            'title'  => '404 Page Not Found :: ' . $this->data('title'),
        ]);
    }

    public function error503()
    {
        $this->redirect()->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title'  => '503 Service Unavailable :: ' . $this->data('title'),
        ]);
    }

    public function error500()
    {
        $this->redirect()->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title'  => '503 Service Unavailable :: ' . $this->data('title'),
        ]);
    }

    /**
     * @param null $target
     *
     * @return mixed
     */
    public abstract function redirect($target = null);

    /**
     * @param string $template
     * @param array  $params
     */
    public abstract function twig(string $template, array $params = []): void;

    /**
     * @param string $key
     *
     * @return string|array
     */
    public abstract function data(string $key = null);
}