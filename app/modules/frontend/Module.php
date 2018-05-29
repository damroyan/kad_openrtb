<?php
namespace Tizer\Modules\Frontend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Session\Adapter\Files as Session;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'Tizer\Modules\Frontend\Controllers' => __DIR__ . '/controllers/',
            'Tizer\Modules\Frontend\Models' => __DIR__ . '/models/',
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the view component
         */
        $di->set('view', function () {
            $view = new View();
            $view->setDI($this);
            $view->setViewsDir(__DIR__ . '/views/');

            $view->registerEngines([
                '.volt'  => 'voltShared',
                '.phtml' => PhpEngine::class
            ]);

            return $view;
        });

        $di->setShared(
            'session',
            function () {
                $session = new Session();

                $session->start();

                return $session;
            }
        );

        $di->set('crypt', function() {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey('ReallyRandomKey');
            return $crypt;
        });


    }
}
