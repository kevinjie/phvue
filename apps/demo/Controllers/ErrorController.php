<?php

namespace Phvue\Demo\Controllers;

class ErrorController extends BaseController
{
    public function indexAction()
    {
        $msg = $this->dispatcher->getParam('msg');

        if ($this->request->isAjax()) {
            $this->view->disable();
            $this->jsonError($msg);
        } else {
            $this->view->msg = $msg;
        }
    }

}

