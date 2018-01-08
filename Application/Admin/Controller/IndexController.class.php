<?php

namespace Admin\Controller;

use Common\Controller;

class IndexController extends Controller\AdminBaseController
{
    public function index()
    {
        $userId = $_SESSION['userId'];
        $model = M('users');
        $user = $model->where(array('id' => $userId))->find();
        $this->assign('user', $user);
        $this->display();
    }

    function getPerson()
    {
        $arr1 = array(2, 7, 6, 14, 18, 21, 25, 26, 23, 18, 13, 9);
        $arr2 = array(3, 4, 5, 8, 11, 15, 17, 16, 14, 10, 6, 4);
        $arr = array($arr1, $arr2);
        response('', '', $arr, '');
    }

    function getMoney()
    {
        $arr1 = array(49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4);
        $arr2 = array(83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3);
        $arr = array($arr1, $arr2);
        response('', '', $arr, '');
    }
}