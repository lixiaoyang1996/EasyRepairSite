<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 下午8:33
 */

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
use Think\Model;

class UserController extends AdminBaseController
{
    public function index()
    {
        $model = new Model();
        $sql = "SELECT a.* from ers_users as a,ers_auth_group_access as b WHERE a.id=b.uid and b.group_id=1";
        $users = $model->query($sql);
        for ($i = 0; $i < count($users); $i++) {
            $model = M('order');
            $count = $model->where(array('uid' => $users[$i]['id']))->count();
            $users[$i]['count'] = $count;
        };
        $this->assign('users', $users);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function edit()
    {
        $id = I('id');
        $model = M('users');
        $user = $model->where(array('id' => $id))->find();
        $this->assign('user', $user);
        $this->display();
    }

    public function save()
    {
        $model = M('users');
        $data = $model->create();
        if ($data['id']) {
            $res = $model->save($data);
        } else {
            $data['password'] = md5($data['password']);
            $data['register_time'] = time();
            $data['login_time'] = $data['register_time'];
            $res = $model->add($data);

            $model = M('auth_group_access');
            $model->add(array('uid' => $res, 'group_id' => 1));
        }
        if ($res >= 0) {
            response(1, '保存成功！', null, U('Admin/User/index'));
        } else {
            response(2, '保存失败！');
        }
    }

    public function del()
    {
        $id = I('id');
        $model = M('users');
        $res = $model->where(array('id' => $id))->delete();
        $model = M('auth_group_access');
        $model->where(array('uid' => $id))->delete();
        if ($res) {
            response(1, '删除成功！', null, U('Admin/User/index'));
        } else {
            response(2, '删除失败！');
        }
    }
}