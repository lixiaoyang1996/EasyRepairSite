<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 19:10
 */

namespace Business\Controller;

use Think\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function checkRegister()
    {
        $user = M('users');


        $data = $user->create();

        $password1 = I("password1");
        $password2 = I("password2");
        $username = $_POST['username'];
        $phone = I('phone');
        $email = I('email');

        $data['password'] = md5(I("password1"));
        $data['register_time'] = time();

        if ($password2 == $password1 && $username != null && $password1 != null && $password2 != null) {
            $result = $user->add($data);

            $bid = $user->where(array('username' => $username))->getField('id');

            $group_access = M('auth_group_access');
            $arr['uid'] = $bid;
            $arr['group_id'] = 2;
            $group_access->add($arr);

        } else if ($username == null) {
            return response(2, '用户名不能为空！');
        } else if ($phone == null) {
            return response(2, '手机号码不能为空！');
        } else if ($email == null) {
            return response(2, '邮箱不能为空！');
        } else if ($password1 == null) {
            return response(2, '密码不能为空！');
        } else if ($password2 == null) {
            return response(2, '确认密码不能为空！');
        } else if ($password1 != $password2) {
            return response(2, '两次密码不相同！');
        } else {
            return response(2, '注册失败！');
        }
        if ($result != false) {
            return response(1, '注册成功', null, U('Business/Login/index'));
        } else {
            return response(2, '注册失败');
        }
    }
}