<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 18:56
 */

namespace Business\Controller;

use Common\Controller;
use Think\Model;

class OrderInfoController extends Controller\BusinessBaseController
{
    public function index()
    {
        $result = $_SESSION['userId'];
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');

        $model = new Model();
        $sql = "
                SELECT
            o.*,
            o.create_time,
            s.name AS shopname,
            u.address AS useraddress,
            u.username,
            u.phone AS userphone,
            u.email AS useremail,
            p.price,
            t.id AS tid
        FROM
            ers_order AS o,
            ers_shop AS s,
            ers_users AS u,
            ers_price AS p,
            ers_type AS t
        WHERE
            o.sid = $sid
        AND o.uid = u.id
        AND o.pid = p.id
        AND p.tid = t.id
        AND o.sid = s.id
        AND o.status >=2
        ";
        $orders = $model->query($sql);

        for ($i = 0; $i < count($orders); $i++) {
            $model = M('users');
            $businessphone = $model->where(array('sid' => $orders[$i] ['sid']))->getField('phone');
            $orders [$i] ['businessphone'] = $businessphone;
            $model = new Model();
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$orders[$i]['tid']}))";
            $res = $model->query($sql);
            $type = $res[2]['type'] . $res[1]['type'];
            $orders[$i] ['type'] = $type;
        }

        $count = count($orders);
        $Page = new\Think\Page($count, 1);
        $show = $Page->show();
        $this->assign('page', $show);

        $this->assign('orders', $orders);
        $this->display();
    }

    public function lookorder()
    {

        $id = I('id');
        $order = M('order');
        $o = $order->where("id = $id")->find($id);
        $model = new Model();
        $sql =
            "
                    SELECT
            o.*, o.create_time,
            s. NAME AS shopname,
            u.address AS useraddress,
            u.sex AS usersex,
            u.username,
            u.email AS useremail,
            u.phone AS userphone,
            p.price,
            t.id AS tid
        FROM
            ers_order AS o,
            ers_shop AS s,
            ers_users AS u,
            ers_price AS p,
            ers_type AS t
        WHERE
            o.sid = s.id
        AND o.uid = u.id
        AND o.pid = p.id
        AND p.tid = t.id
        AND o.id=$id;
            ";
        $orders = $model->query($sql);
        for ($i = 0; $i < count($orders); $i++) {
//            var_dump(count($orders));
            $model = M('users');
            $businessphone = $model->where(array('sid' => $orders[$i]['sid']))->getField('phone');
            $orders [$i] ['businessphone'] = $businessphone;
            $model = new Model();
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$orders[$i]['tid']}))";
            $res = $model->query($sql);
            $type = $res[2]['type'] . $res[1]['type'];
            $orders[$i] ['type'] = $type;
        }
        $result = array_reduce($orders, 'array_merge', array());
        $this->assign('o', $o);
        $this->assign('result', $result);
        $this->display();
    }

    public function dolookorder()
    {
        $order = M('order');
        $data = $order->create();
        if ($data["status"] == 5) {
            $data['finish_time'] = time();
        }
        $result = $order->save($data);
        if ($result >= 0) {
            return response(1, '修改成功！', null, U('Business/OrderInfo/index'));
        } else {
            return response(2, '修改失败！');
        }
    }
}