<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 10:58
 */

namespace Business\Controller;

use Common\Controller;

class StoreManageController extends Controller\BusinessBaseController
{
    public function index()
    {
        $result = $_SESSION['userId'];
        $type = M("type");
        $t = $type->where("pid = 0")->select();
        $this->assign('t', $t);

        $shop = M("shop");
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');
        $n = $shop->where("id = $sid")->find();
//        var_dump($n);
        $this->assign('n', $n);
        $this->display();
    }

    public function doedit()
    {
        $shop = M("shop");
        $data = $shop->create();


        $result = $shop->save($data);
//          var_dump($result);
        if ($result >= 0) {
            return response(1, '修改成功！', null, U('Business/StoreManage/index'));
        } else {
            response(2, '修改失败！');
        }
    }


    public function doUploadPic()
    {
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Upload/';
        $upload->savePath = '/';
        $info = $upload->upload();
        if (!$info) {
            $this->error($upload->getError());
        } else {
            foreach ($info as $file) {
                $data = __ROOT__ . '/Upload' . $file['savepath'] . $file['savename'];
                $this->ajaxReturn($data, 'EVAL');
            }
        }
    }
}