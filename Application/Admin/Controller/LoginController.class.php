<?php
/**
 * User: njzy
 * Date: 2017/12/25
 * Time: 下午3:01
 */

namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function login(){
        $userName=I('userName');
        $password=md5(I('password'));
        $model=M('users');
        $result=$model->where(array('userName'=>$userName,'password'=>$password))->find();
        if($result){
            $_SESSION['userId']=$result['id'];
            response('1','登陆成功！',array('id'=>$result['id'],'url'=>U('Admin/Index/index')));
        }else{
            response('0','登陆失败！');
        }
    }
}