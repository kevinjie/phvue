<?php

namespace Phvue\Demo\Plugins;

use Phalcon\Mvc\User\Plugin;
use Exception;

class ExceptionHandler extends Plugin
{
    /**
     * handler user exception.
     *
     * @param Exception $exception
     * @return bool
     */
    public function handle(Exception $exception)
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $data = [
                'success' => false,
                'msg' => $exception->getMessage(),
            ];
            header('Content-Type:application/json');
            echo json_encode($data);
            return;
        } else {
            return $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'index',
                'params' => ['msg' => $exception->getMessage()]
            ]);
        }
    }

}
