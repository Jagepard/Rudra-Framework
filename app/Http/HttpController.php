<?php

namespace App\Http;

use App\Http\Supports\HttpErrors;
use App\Http\Supports\TwigFunctions;
use Rudra\ContainerInterface;
use Rudra\Controller;

/**
 * Class Module
 *
 * @package Http
 */
class HttpController extends Controller
{

    use TwigFunctions;
    use HttpErrors;

    /**
     * @param ContainerInterface $container
     * @param string             $templateEngine
     */
    public function init(ContainerInterface $container, string $templateEngine)
    {
        parent::init($container, $templateEngine);

        $this->container()->get('debugbar')['time']->startMeasure('Controller', 'Controller');
        $this->setData($this->container()->config('title'), 'title');
        $this->setData($this->container(), 'container');
        $this->check();
    }
}
