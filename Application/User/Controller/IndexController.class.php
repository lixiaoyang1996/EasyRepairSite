<?php

namespace User\Controller;

use Common\Controller;

class IndexController extends Controller\UserBaseController
{
    public function index()
    {
        $this->show('普通用户后台');
    }
}