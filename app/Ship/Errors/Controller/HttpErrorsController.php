<?php

namespace App\Ship\Errors\Controller;

use App\Ship\Errors\ErrorsController;
use Rudra\Exceptions\RouterException;

class HttpErrorsController extends ErrorsController
{
    /**
     * @throws RouterException
     */
    public function handle404($data, string $type = 'db', array $page = []): void
    {
        if ($type == 'db') {
            if (count($data) < 1 || !$data) {
                throw new RouterException('404');
            }
        } elseif ($type == 'pagination') {
            if ($page['id'] > count($data)) {
                throw new RouterException('404');
            }
        }
    }

    public function error404(): void
    {
        data(["content" => view("errors.404")]);
        render("layout", data());
    }

    public function error503(): void
    {
        data(["content" => view("errors.503")]);
        render("layout", data());
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function error500()
    {
        $this->redirect()->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title' => '503 Service Unavailable :: ' . data('title'),
        ]);
    }
}
