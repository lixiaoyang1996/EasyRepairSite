<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 下午8:54
 */

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
use Think\Model;

class ShopController extends AdminBaseController
{
    public function index()
    {
        $model = M('shop');
        $shops = $model->select();
        for ($i = 0; $i < count($shops); $i++) {
            $model = new Model();
            $sql = "select u.username as username,t.name as type from ers_users as u,ers_type as t,ers_shop as s where u.sid=s.id and s.tid=t.id and s.id={$shops[$i]['id']}";
            $res = $model->query($sql);
            $shops[$i]['username'] = $res[0]['username'];
            $shops[$i]['type'] = $res[0]['type'];
        }
        $this->assign('shops', $shops);
        $this->display();
    }

    public function edit()
    {
        $id = I('id');
        $model = M('shop');
        $shop = $model->where(array('id' => $id))->find();
        $model = new Model();
        $sql = "select u.username as username,u.address as address,t.name as type from ers_users as u,ers_type as t,ers_shop as s where u.sid=s.id and s.tid=t.id and s.id={$id}";
        $res = $model->query($sql);
        $shop['username'] = $res[0]['username'];
        $shop['type'] = $res[0]['type'];
        $shop['address'] = $res[0]['address'];
        $sql = "SELECT p.*,t.name AS brand FROM  ers_shop AS s,ers_type AS t,ers_price AS p WHERE p.tid=t.id AND p.sid=s.id AND s.id={$id}";
        $repairs = $model->query($sql);
        for ($i = 0; $i < count($repairs); $i++) {
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$repairs[$i]['tid']}))";
            $res = $model->query($sql);
            $type = $res[1]['type'];
            $repairs[$i]['type'] = $type;
        }
        $this->assign('repairs', $repairs);
        $this->assign('shop', $shop);
        $this->display();
    }

    public function save()
    {
        $model = M('shop');
        $data = $model->create();
        if ($data['id']) {
            $res = $model->save($data);
        } else {

        }
        if ($res >= 0) {
            response(1, '保存成功！', null, U('Admin/Shop/index'));
        } else {
            response(2, '保存失败！');
        }
    }

    public function del()
    {
        $id = I('id');
        $model = M('shop');
        $res = $model->where(array('id' => $id))->delete();
        $model = M('price');
        $model->where(array('sid' => $id))->delete();
        if ($res) {
            response(1, '删除成功！', null, U('Admin/Shop/index'));
        } else {
            response(2, '删除失败！');
        }
    }
}