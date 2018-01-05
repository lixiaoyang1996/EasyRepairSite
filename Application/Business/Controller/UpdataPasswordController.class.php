<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5
 * Time: 20:24
 */

namespace Business\Controller;

use Common\Controller;

class UpdataPasswordController extends Controller\BusinessBaseController
{
    public function index()
    {
        $this->display();
    }

    public function saves()
    {

        $id = $_SESSION['userId'];
        $user = M("users");
        $pwd1 = md5($_POST['pwd1']);
        $pwd2 = $_POST['pwd2'];
        $pwd3 = $_POST['pwd3'];

        $password = $user->where('id=' . $id)->getField('password');

        if ($pwd1 == $password && ($pwd2 == $pwd3) && $pwd2 != null) {
            $result = $user->where('id=' . $id)->setField('password', md5($pwd2));
        } else {
            response(2, '修改失败！');
        }
        if ($result != false) {

            return response(1, '修改成功', null, U('Business/Login/index'));

        } else {
            response(2, '修改失败！');
        }
    }

}