<?php
/**
 * User: njzy
 * Date: 2018/1/3
 * Time: 下午3:28
 */

namespace Admin\Controller;

use Common\Controller;
use Think\Model;

class OrderController extends Controller\AdminBaseController
{
    public function index()
    {
        $model = new Model();
        $sql = "
SELECT
  o.*,
  o.create_time,
  s.name  AS shopname,
  u.username,
  u.phone AS userphone,
  p.price,
  t.id    AS tid
FROM ers_order AS o, ers_shop AS s, ers_users AS u, ers_price AS p, ers_type AS t
WHERE o.sid = s.id AND o.uid = u.id AND o.pid = p.id AND p.tid = t.id";
        $orders = $model->query($sql);
        for ($i = 0; $i < count($orders); $i++) {
            $model = M('users');
            $businessphone = $model->where(array('sid' => $orders[$i]['sid']))->getField('phone');
            $orders[$i]['businessphone'] = $businessphone;
            $model = new Model();
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$orders[$i]['tid']}))";
            $res = $model->query($sql);
            $type = $res[2]['type'] . $res[1]['type'];
            $orders[$i]['type'] = $type;
        }
        $this->assign('orders', $orders);
        $this->display();
    }

    public function view()
    {
        $id = I('id');
        $model = new Model();
        $sql = "
SELECT
  o.*,
  o.create_time,
  s.name  AS shopname,
  u.username,
  u.phone AS userphone,
  u.email AS useremail,
  u.address AS useraddress,
  p.price,
  t.id    AS tid
FROM ers_order AS o, ers_shop AS s, ers_users AS u, ers_price AS p, ers_type AS t
WHERE o.sid = s.id AND o.uid = u.id AND o.pid = p.id AND p.tid = t.id AND s.id={$id}";
        $res = $model->query($sql);
        $order = $res[0];
        $model = M('users');
        $res = $model->where(array('sid' => $order['sid']))->find();
        $order['businessphone'] = $res['phone'];
        $order['businessaddress'] = $res['address'];
        $order['businessemail'] = $res['email'];
        $model = new Model();
        $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$order['tid']}))";
        $res = $model->query($sql);
        $order['type'] = $res[1]['type'];
        $order['brand'] = $res[2]['type'];
        $this->assign('order', $order);
        $this->display();
    }
}