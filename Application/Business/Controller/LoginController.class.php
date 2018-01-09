<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 19:05
 */

namespace Business\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function checkLogin()
    {
        $username = I('post.username');
        $password = md5(I('post.password'));
        $users = M('users');

        $result = $users->where("username='%s' AND password='%s'", $username, $password)->find();

        if ($result) {
            $_SESSION['userId'] = $result['id'];
//            $_SESSION['username'] = $result['username'];
            // 判断用户是否有权限登录管理员后台
            $auth = new \Think\Auth();
            $result = $auth->check('Business/*', $_SESSION['userId']);
            if (!$result) {
               return response('2', '登陆失败！');
            }
            return response(1, '登录成功！', null, U('Business/SalesVolume/index'));
        } else {
           return response('2', '登陆失败！');
        }
    }

    public function logout()
    {
        session(null);
        header('location:' . U('Login/index'));
    }
}