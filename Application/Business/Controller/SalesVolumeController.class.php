<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 11:27
 */

namespace Business\Controller;

use Common\Controller;

class SalesVolumeController extends Controller\BusinessBaseController
{
    public function index()
    {
        $result = $_SESSION['userId'];
        $users = M('users');
        $user = $users->where("id = $result")->find();

        $sid = $users->where("id=$result")->getField('sid');

        $shop = M('shop');
        $s = $shop->where("id=$sid")->find();
//        var_dump($s);
        $this->assign('s',$s);
        $this->assign('user', $user);
        $this->display();
    }

    public function countType()
    {
        $arr1 = array(
            ['手机-小米', 16.4],
            ['手机-一加', 15.6],
            ['手机-华为', 18.0],
            ['电脑-联想', 17.2],
            ['电脑-神州', 12.8],
            ['平板-Apple', 19.2],
            ['其他', 0.8]
        );
        $arr = array($arr1);
        response('', '', $arr, '');
    }

    public function countMoney()
    {
        $arr1 = array(4.0,2.9,3.8,4.6,3.4,4.2,2.4,5.1,3.9,4.3,4,6,3.7);
        $arr = array($arr1);
        response('', '', $arr, '');
    }
}