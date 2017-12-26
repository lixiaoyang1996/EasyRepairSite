<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 19:05
 */

namespace Business\Controller;

use Common\Controller;

class LoginController extends Controller\BusinessBaseController
{
    public function login()
    {
        $this->display();
    }
}