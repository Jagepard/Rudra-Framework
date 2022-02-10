<?php

namespace App\Ship\Errors\Controllers;

use App\Ship\Errors\ErrorsController;
use Rudra\Exceptions\RouterException;
use Rudra\View\ViewFacade as View;

class HttpErrorsController extends ErrorsController
{
    public function handle404($data, string $type = 'db', array $page = [])
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

    public function error404()
    {
        data(["content" => view("errors.404")]);

        render("uikit", data());
    }

    public function error503()
    {
        data(["content" => view("errors.503")]);
        render("uikit", data());
    }

    public function error500()
    {
        $this->redirect()->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title' => '503 Service Unavailable :: ' . data('title'),
        ]);
    }
}