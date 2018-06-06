<?php

namespace App\Web;

use Rudra\Controller;
use App\Web\Supports\HttpErrors;
use App\Web\Supports\TwigFunctions;
use Rudra\Interfaces\ContainerInterface;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MessagesCollector;

class WebController extends Controller
{

    use TwigFunctions;
    use HttpErrors;

    public function init(ContainerInterface $container, array $config)
    {
        parent::init($container, $container->config('template', 'web'));
        $this->checkCookie();
        $this->getTwig()->addGlobal('container', $container);
        $this->container()->get('debugbar')['time']->startMeasure('Controller', 'Controller');
        $this->setData('Rudra Framework', 'title');
    }

    public function twig(string $template, array $params = []): void
    {
        $this->container()->get('debugbar')->addCollector(new ConfigCollector($params));
        $this->container()->get('debugbar')->addCollector(new MessagesCollector('Twig'));
        $this->container()->get('debugbar')['Twig']->info($template);

        parent::twig($template, $params);
    }
}
