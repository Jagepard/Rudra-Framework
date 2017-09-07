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

        $this->setData('Rudra Framework', 'title');
        $this->setData($this->container(), 'container');
        $this->check();
    }
}
