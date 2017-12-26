<?php

namespace User\Controller;

use Common\Controller;

class IndexController extends Controller\UserBaseController
{
    public function index()
    {
        $this->display();
    }
}