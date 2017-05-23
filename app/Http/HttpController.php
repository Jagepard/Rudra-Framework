<?php

namespace App\Http;

use Rudra\Container;
use Rudra\ContainerInterface;
use Rudra\Controller;

/**
 * Class Module
 *
 * @package Http
 */
class HttpController extends Controller
{

    /**
     * @param ContainerInterface $container
     * @param string             $templateEngine
     */
    public function init(ContainerInterface $container, string $templateEngine)
    {
        parent::init($container, $templateEngine);
        $this->setData('Rudra', 'title');
    }

    /**
     * Метод выполняется перед вызовом контроллера
     */
    public function before()
    {
        $this->container()->get('auth')->check();
    }

    public function after()
    {
        // Очищаем сессию от alert
        $this->container()->unsetSession('value');
        $this->container()->unsetSession('alert');
    }

    public function templateEngine(string $config): void
    {
        parent::templateEngine($config);

        $d = new \Twig_SimpleFunction('d', function ($var) {
            return d($var);
        });

        $this->getTwig()->addFunction($d);

        $date = new \Twig_SimpleFunction('date', function ($var) {
            return date($var);
        });

        $this->getTwig()->addFunction($date);

        $auth = new \Twig_SimpleFunction('auth', function () {
            return $this->container()->get('auth')->regularAccess();
        });

        $this->getTwig()->addFunction($auth);


        $active = new \Twig_SimpleFunction('active', function ($var) {
            return $this->container()->get('pagination')->activeClass($var);
        });

        $this->getTwig()->addFunction($active);

        $strstr = new \Twig_SimpleFunction('strstr', function ($var) {
            return strstr($var, '<a id="strstr" name="strstr"></a>', true);
        });

        $this->getTwig()->addFunction($strstr);

        if (DEV) {
            $debugbarRenderer = Container::$app->get('debugbar')->getJavascriptRenderer();
            $this->getTwig()->addGlobal('debugbar', $debugbarRenderer);
        }

    }

    public function error404()
    {
        $this->container()->get('redirect')->responseCode('404');

        return $this->twig('errors/error.html.twig', [
            'title'  => '404 Page Not Found :: ' . $this->getData('title'),
        ]);
    }

    public function error503()
    {
        $this->container()->get('redirect')->responseCode('503');

        return $this->twig('errors/error.html.twig', [
            'title'  => '503 Service Unavailable :: ' . $this->getData('title'),
        ]);
    }

    public function error500()
    {
        $this->container()->get('redirect')->responseCode('503');

        return $this->twig('errors/503.html.twig', [
            'title'  => '503 Service Unavailable :: ' . $this->getData('title'),
        ]);
    }
}
