<?php

namespace App\Web\Supports;

use Twig_SimpleFunction;
use Twig_Environment;
use Rudra\ContainerInterface;

trait TwigFunctions
{

    public function templateEngine(array $config): void
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

        $active = new Twig_SimpleFunction('active', function ($link, $page) {
            if ($link == $page) {
                echo 'class="active"';
            }
        });

        $this->getTwig()->addFunction($active);

        $value = new Twig_SimpleFunction('value', function ($var) {
            if ($this->container()->hasSession('value', $var)) {
                return $this->container()->getSession('value', $var);
            }
        });

        $this->getTwig()->addFunction($value);

        $alert = new Twig_SimpleFunction('alert', function ($value, $style, $label = null) {
            if ($this->container()->hasSession('alert', $value)) {
                return '<div class="alert alert-' . $style . '" style="padding: 15px">' . $this->container()->getSession('alert', $value) . $label . '</div>';
            }
        });

        $this->getTwig()->addFunction($alert);

        if (DEV) {
            $debugbarRenderer = $this->container()->get('debugbar')->getJavascriptRenderer();
            $this->getTwig()->addGlobal('debugbar', $debugbarRenderer);
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