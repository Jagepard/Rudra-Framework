<?php

namespace App\Web;

use Rudra\Controller;
use App\Common\HttpErrors;
use App\Common\TwigFunctions;
//use App\Auth\Models\PDO\Users as PDO;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MessagesCollector;

class WebController extends Controller
{
    use HttpErrors;
    use TwigFunctions;

    public function init()
    {
        $this->template(config('template', 'web'));
        $this->updateSessionIfSetRememberMe();
        $this->setData('title', 'Rudra Framework');
//        $this->setData('user', PDO::user());
    }

    /**
     * @param string $template
     * @param array  $params
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function twig(string $template, array $params = []): void
    {
        $this->container()->get('debugbar')['time']->startMeasure('Controller', 'Controller');
        $this->container()->get('debugbar')->addCollector(new ConfigCollector($params));
        $this->container()->get('debugbar')->addCollector(new MessagesCollector('Twig'));
        $this->container()->get('debugbar')['Twig']->info($template);

        parent::twig($template, $params);
    }
}
