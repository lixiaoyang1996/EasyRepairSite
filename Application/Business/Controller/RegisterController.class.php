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
        $data['login_time'] = time();
        $data['register_time'] = time();

        $uName = $user->getField('username', true);
        for ($i = 0; $i < count($uName); $i++) {
            if ($username == $uName[$i]) {
                response(2, '注册失败！,用户名已经存在');
                break;
            }
        }
        if ($username == null) {
            return response(2, '用户名不能为空！');
        }
        if ($phone == null) {
            return response(2, '手机号码不能为空！');
        }
        if ($email == null) {
            return response(2, '邮箱不能为空！');
        }
        if ($password1 == null) {
            return response(2, '输入密码不能为空！');
        }
        if ($password2 == null) {
            return response(2, '确认密码不能为空！');
        }
        if ($password1 != $password2) {
            return response(2, '两次密码不同！');
        }


        if ($password2 == $password1 && $username != null && $password1 != null && $password2 != null) {
            $result = $user->add($data);
            $id = $user->where(array('username' => $username))->getField('id');
            $ers_auth_group_access = M('auth_group_access');
            $group['uid'] = $id;
            $group['group_id'] = 2;
            $ers_auth_group_access->add($group);

        } else {
            response(2, '注册失败！');
        }
        if ($result != false) {
            //header('location:' . U('Index/index'));

            return response(1, '注册成功！', null, U('Business/Login/index'));

        } else {
            return response(2, '注册失败！');
        }
    }
}