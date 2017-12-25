<?php

namespace Admin\Controller;

use Common\Controller;

class IndexController extends Controller\AdminBaseController
{
    public function index()
    {
        $this->display();
    }
}