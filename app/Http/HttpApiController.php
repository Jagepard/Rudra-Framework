<?php

namespace App\Http;

use App\Http\Supports\HttpErrors;
use Rudra\Controller;
use Rudra\ContainerInterface;

class HttpApiController extends Controller
{
    use HttpErrors;

    public function init(ContainerInterface $container, string $templateEngine)
    {
        parent::init($container, $templateEngine);

        $this->getTwig()->addGlobal('container', $this->container());
        $this->setData('Коротков Данила || Веб-разработчик (Web-developer)', 'title');
        $this->check('API');
    }
}
