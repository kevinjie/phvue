<?php

namespace Phvue\Demo\Controllers;

use \Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function error($msg = '')
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $this->jsonError($msg);
        } else {
            return $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'index',
                    'params' => ['msg'=>$msg]
                ]
            );
        }
    }

    public function success($msg = '', $data = [])
    {
        if ($this->request->isAjax()) {
            $this->jsonSuccess($data, $msg);
        } else {
            return $this->dispatcher->forward(
                [
                    'controller' => 'success',
                    'action' => 'index',
                    'params' => ['msg'=>$msg]
                ]
            );
        }
    }

    public function jsonSuccess($data = [], $msg = 'success')
    {
        $this->jsonEcho(true, $msg, $data);
    }

    public function jsonError($msg = 'error')
    {
        $this->jsonEcho(false, $msg);
    }

    public function jsonEcho($status, $msg, $data = [])
    {
        header('Content-Type:application/json');
        $response = [
            'success' => $status,
            'msg' => $msg,
            'data' => $data,
        ];
        echo json_encode($response);
    }

}
