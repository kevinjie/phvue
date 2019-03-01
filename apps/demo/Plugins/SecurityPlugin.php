<?php

namespace Phvue\Demo\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;


/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $whiteController = ["index"];
        $whiteControllerAction = [

        ];

        $commonAuthorized = [

        ];

        if (in_array($controllerName, $whiteController) ||
            in_array($controllerName.$action, $whiteControllerAction)
        ) {
            return true;
        } elseif (in_array($controllerName.$action, $commonAuthorized)) {
            return true;
        } else {
            return $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'index',
                'params' => ['msg' => 'error happen']
            ]);
        }
    }
}
