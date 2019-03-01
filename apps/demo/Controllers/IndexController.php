<?php

namespace Phvue\Demo\Controllers;

use Phvue\Demo\Models\MyData;
use Phvue\Common\Exceptions\ParamsException;

class IndexController extends BaseController 
{
    public function indexAction()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $this->view->disable();
            $post = $this->request->getPost();
            
            $user = MyData::findFirst([
                'conditions' => "id = :id:",
                'bind' => ['id' => 1]
            ]);
            if ($user) {
                return $this->jsonSuccess($user->name);
            }else {
                return $this->jsonError('error happen');
            }
        }
    } 
}