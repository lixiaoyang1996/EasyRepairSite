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
        $u = $user->where("sid = $sid")->find();
        $n = $shop->where("id = $sid")->find();

        $this->assign('u', $u);
        $this->assign('n', $n);
        $this->display();
    }

    public function add()
    {
        $result = $_SESSION['userId'];
        $type = M("type");
        $t = $type->where("pid = 0")->select();
        $this->assign('t', $t);

        $shop = M("shop");
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');
        $u = $user->where("sid = $sid")->find();
        $n = $shop->where("id = $sid")->find();

        $this->assign('u', $u);
        $this->assign('n', $n);
        $this->display();
    }

    public function doadd()
    {
        $shop = M("shop");
        $name = $_POST['name'];
        $data = $shop->create();
        $data["create_time"] = time();

        if ($name != null) {
            $result = $shop->add($data);
            $bsid = $shop->where(array('name' => $name))->getField('id');

            $userId = $_SESSION['userId'];
            $user = M('users');

            $arr['sid']= $bsid;
            $user ->where("id= $userId")->save($arr);

        } else {
            return response(2, '店铺名不能为空！');
        }
        if ($result != false) {
            return response(1, '添加成功', null, U('Business/StoreManage/index'));

        } else {
            return response(2, '添加失败');
        }


//        $result = $shop->add($data);

//        $sid = $shop->where(array('id' => $id))->getField('id');
//        $user = M('users');
//        $arr['sid'] = $sid;
//        $user->add($arr);


//        if ($result > 0) {
//            return response(1, '添加成功！', null, U('Business/StoreManage/index'));
//        } else {
//            return response(2, "添加失败！");
//        }
    }

    public function edit()
    {
        $result = $_SESSION['userId'];
        $type = M("type");
        $t = $type->where("pid = 0")->select();
        $this->assign('t', $t);

        $shop = M("shop");
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');
        $u = $user->where("sid = $sid")->find();
        $n = $shop->where("id = $sid")->find();

        $this->assign('u', $u);
        $this->assign('n', $n);
        $this->display();
    }

    public function doedit()
    {
        $shop = M("shop");
        $data = $shop->create();
        $result = $shop->save($data);
        //var_dump($result);
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