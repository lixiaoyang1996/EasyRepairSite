<?php

namespace Admin\Controller;

use Common\Controller;

class IndexController extends Controller\AdminBaseController
{
    public function index()
    {
        $userId=$_SESSION['userId'];
        $model=M('users');
        $user=$model->where(array('id'=>$userId))->find();

        $this->assign('user',$user);
        $this->display();
    }
}