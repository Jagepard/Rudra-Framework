<?php

namespace App\Errors\Controllers;

use App\Errors\ErrorsController;
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
        $this->data->set([
            "content" => View::view("errors.404"),
        ]);

        return View::render("bootstrap", $this->data->get());
    }

    public function error503()
    {
        $this->data->set([
            "content" => View::view("errors.503"),
        ]);

        return View::render("bootstrap", $this->data->get());
    }

    public function error500()
    {
        $this->redirect()->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title' => '503 Service Unavailable :: ' . $this->data('title'),
        ]);
    }
}
