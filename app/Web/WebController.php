<?php

namespace App\Web;

use App\AppController;
use App\Web\Models\Web;
use Rudra\Router\RouterFacade as Router;
use Rudra\View\ViewFacade as View;

class WebController extends AppController
{
    public function init()
    {
        View::setup([
            "base.path"      => dirname(__DIR__) . '/',
            "engine"         => "native",
            "view.path"      => "Web/UI/tmpl",
            "cache.path"     => "Web/UI/cache",
            "file.extension" => "phtml",
        ]);
    }

    /**
     * @Routing(url = 'web/{controller}/{action}')
     */
    public function routeResolver($controller, $action)
    {
        Router::directCall([null, null, [
            __NAMESPACE__ . '\\Controllers\\' . ucfirst($controller) . "Controller", $action
        ]]);
    }
}
