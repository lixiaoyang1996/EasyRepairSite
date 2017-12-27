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

    public function checkLogin()
    {
        $username = I('post.username');
        $password = I('post.password');
        $users = M('users');

        $result = $users->where("username='%s' AND password='%s'", $username, $password)->find();
        if ($result) {
            $_SESSION['username'] = $result['username'];
            $this->success('登陆成功', U('Index/index'), 3);
        }else{
            $this->error("登录失败");
        }
    }
    public function logout()
    {
        session(null);
        header('location:' . U('Login/index'));
    }
}