<?php

namespace Business\Controller;

use Common\Controller;

class IndexController extends Controller\BusinessBaseController
{
    public function index()
    {
        $this->display();
    }
}