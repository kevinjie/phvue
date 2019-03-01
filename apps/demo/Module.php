<?php

namespace Phvue\Demo;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Url as UrlResolver;
use Phvue\Demo\Plugins\ExceptionHandler;
use Phvue\Demo\Plugins\SecurityPlugin;
use Phalcon\Logger\Formatter\Line;
use Phvue\Common\Utils\Json;
use Phalcon\Flash\Direct as FlashDirect;



class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
    }

    /**
     * Registers service_adaptor related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the index component
         */
        $di['view'] = function () {
            $view = new View();
            if(env('APP_ENV') == 'prd') {
                $view->setViewsDir(__DIR__ . '/views/');
            } else{
                $view->setViewsDir(__DIR__ . '/views/');
            }

            return $view;
        };


        /**
         * The URL component is used to generate all kinds of URLs in the application
         */
        $di->setShared('url', function ()  {
            $url = new UrlResolver();
            $url->setBaseUri('/demo/');

            return $url;
        });

        $di->setShared('logger', function() use($di){
            $logger = new File(
                APP_PATH . "/logs/Demo.log",
                array(
                    'mode' => 'a+'
                )
            );
            $json = new Json();
            $logger->setFormatter($json);
            return $logger;
        });

        $eventsManager = $di->get('dispatcher')->getEventsManager();
        $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin());


        $di->set('exceptionHandler', new ExceptionHandler());

    }
}
