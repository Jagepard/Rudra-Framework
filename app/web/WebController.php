<?php

namespace App\Web;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MessagesCollector;
use App\Web\Supports\HttpErrors;
use App\Web\Supports\TwigFunctions;
use Rudra\Controller;
use Rudra\ContainerInterface;

class WebController extends Controller
{

    use TwigFunctions;
    use HttpErrors;

    public function init(ContainerInterface $container, array $templateEngine)
    {
        parent::init($container, $container->config('template', 'web'));

        $this->getTwig()->addGlobal('container', $this->container());
        $this->container()->get('debugbar')['time']->startMeasure('Controller', 'Controller');
        $this->setData('Rudra Framework', 'title');
        $this->check();
    }

    public function after()
    {

    }

    public function twig(string $template, array $params = []): void
    {
        $this->container()->get('debugbar')->addCollector(new ConfigCollector($params));
        $this->container()->get('debugbar')->addCollector(new MessagesCollector('Twig'));
        $this->container()->get('debugbar')['Twig']->info($template);

        parent::twig($template, $params);
    }
}
