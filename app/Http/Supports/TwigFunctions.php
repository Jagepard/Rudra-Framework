<?php

namespace App\Http\Supports;

use Twig_SimpleFunction;
use Twig_Environment;
use Rudra\ContainerInterface;

trait TwigFunctions
{

    public function templateEngine(string $config): void
    {
        parent::templateEngine($config);

        $d = new Twig_SimpleFunction('d', function ($var) {
            return d($var);
        });

        $this->getTwig()->addFunction($d);

        $date = new Twig_SimpleFunction('date', function ($var) {
            return date($var);
        });

        $this->getTwig()->addFunction($date);

        $auth = new Twig_SimpleFunction('auth', function () {
            return $this->container()->get('auth')->access(true);
        });

        $this->getTwig()->addFunction($auth);


        $active = new Twig_SimpleFunction('active', function ($var) {
            return $this->container()->get('pagination')->activeClass($var);
        });

        $this->getTwig()->addFunction($active);

        $strstr = new Twig_SimpleFunction('strstr', function ($var) {
            return strstr($var, '<a id="strstr" name="strstr"></a>', true);
        });

        $this->getTwig()->addFunction($strstr);

        if (DEV) {
            $debugBarRenderer = $this->container()->get('debugbar')->getJavascriptRenderer();
            $this->getTwig()->addGlobal('debugbar', $debugBarRenderer);
        }
    }

    /**
     * @return Twig_Environment
     */
    public abstract function getTwig(): Twig_Environment;

    /**
     * @return mixed
     */
    public abstract function container(): ContainerInterface;
}