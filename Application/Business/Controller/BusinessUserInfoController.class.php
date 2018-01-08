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

        if ($_FILES['avatar']['tmp_name'] != '') {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Upload/';
            $upload->savePath = '/';
            $info = $upload->uploadOne($_FILES['avatar']);
            if (!$info) {
                $this->error($upload->getError());
            } else {
                $data['avatar'] = $info['savepath'].$info['savename'];
            }
        }
//        var_dump($data);
        $result = $user->save($data);
        if ($result >= 0) {
            return response(1, '修改成功！', null, U('Business/BusinessUserInfo/index'));
        } else {
            return response(2, '修改失败！');
        }
    }
}