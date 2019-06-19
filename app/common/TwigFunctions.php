<?php

namespace App\Common;

use Twig_Environment;
use Twig_SimpleFunction;
use Rudra\Interfaces\ContainerInterface;

trait TwigFunctions
{
    public function template(array $config): void
    {
        parent::template($config);

        $this->getTwig()->addFunction(new Twig_SimpleFunction('d', function ($var) {
            return d($var);
        }));

        $this->getTwig()->addFunction(new Twig_SimpleFunction('date', function ($var) {
            return date($var);
        }));

        $this->getTwig()->addFunction(new Twig_SimpleFunction('auth', function () {
            return $this->container()->get('auth')->access();
        }));

        $this->getTwig()->addFunction(new Twig_SimpleFunction('active', function ($link, $page) {
            if ($link == $page) {
                echo 'class="active"';
            }
        }));

        $this->getTwig()->addFunction(new Twig_SimpleFunction('value', function ($var) {
            if ($this->container()->hasSession('value', $var)) {
                return $this->container()->getSession('value', $var);
            }
        }));

        $this->getTwig()->addFunction(new Twig_SimpleFunction('alert', function ($value, $style, $label = null) {
            if ($this->container()->hasSession('alert', $value)) {
                return '<div class="alert alert-' . $style . '" style="padding: 15px">' . $this->container()->getSession('alert', $value) . $label . '</div>';
            }
        }));

        if ('development' == config('env')) {
            $debugbarRenderer = $this->container()->get('debugbar')->getJavascriptRenderer();
            $this->getTwig()->addGlobal('debugbar', $debugbarRenderer);
        }

        $this->getTwig()->addGlobal('env', config('env'));
        $this->getTwig()->addGlobal('url', config('url'));
        $this->getTwig()->addGlobal('container', $this->container());
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
