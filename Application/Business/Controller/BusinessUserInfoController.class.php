<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 20:00
 */

namespace Business\Controller;

use Common\Controller;

class BusinessUserInfoController extends Controller\BusinessBaseController
{
    public function index()
    {
        $result = $_SESSION['userId'];
        $user = M('users');
        $u = $user->where("id=$result")->find();
        $this->assign('u',$u);
        $this->display();
    }

    public function doedit()
    {
        $user = M("users");
        $data = $user->create();
        $result = $user->save($data);
        if ($result >= 0) {
            return response(1, '修改成功！', null, U('Business/BusinessUserInfo/index'));
        } else {
            return response(2, '修改失败！');
        }
    }
}