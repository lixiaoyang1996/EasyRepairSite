<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3
 * Time: 8:10
 */

namespace Business\Controller;

use Common\Controller;
use Think\Model;

class PriceController extends Controller\BusinessBaseController
{
    public function index()
    {
        $result = $_SESSION['userId'];
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');

//      $price = M('price');
//      $p = $price->where("sid = $sid")->select();

        $model = new Model();
        $sql = "SELECT p.*,t.name AS brand FROM  ers_shop AS s,ers_type AS t,ers_price AS p WHERE p.tid=t.id AND p.sid=s.id AND s.id={$sid}";
        $p = $model->query($sql);
        for ($i = 0; $i < count($p); $i++) {
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$p[$i]['tid']}))";
            $res = $model->query($sql);
            $Type = $res[0]['type']; //最高节点
            $type = $res[1]['type'];//次级节点
            $p[$i]['type'] = $type;
            $p[$i]['Type'] = $Type;
        }

        $this->assign('p', $p);
        $this->display();
    }

    public function add()
    {
        $type = M("type");
        $t = $type->where("pid=2")->select();
        $this->assign('t', $t);
        $this->display();
    }

    public function edit($id)
    {
        $price = M("price");
        $n = $price->where("id = $id")->find();

        $result = $_SESSION['userId'];
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');

//      $price = M('price');
//      $p = $price->where("sid = $sid")->select();

        $model = new Model();
        $sql = "SELECT p.*,t.name AS brand FROM  ers_shop AS s,ers_type AS t,ers_price AS p WHERE p.tid=t.id AND p.sid=s.id AND s.id={$sid} AND p.id = $id";
        $p = $model->query($sql);
        for ($i = 0; $i < count($p); $i++) {
            $sql = "select name as type from ers_type where FIND_IN_SET(id,getParList({$p[$i]['tid']}))";
            $res = $model->query($sql);
            $Type = $res[0]['type']; //最高节点
            $type = $res[1]['type'];//次级节点
            $p[$i]['type'] = $type;
            $p[$i]['Type'] = $Type;
        }
        $result = array_reduce($p, 'array_merge', array());
//        var_dump($result);
        $this->assign('result', $result);
        $this->assign('n', $n);
        $this->display();
    }

    public function doedit()
    {
        $price = M('price');
        $data = $price->create();
        $result = $price->save($data);
        if ($result >= 0) {
            return response(1, '修改成功！', null, U('Business/Price/index'));
        } else {
            response(2, '修改失败！');
        }
    }

    public function delete($id)
    {
        $price = M("price");
        $result = $price->delete($id);
        if ($result > 0) {
            return response(1, '删除成功!', null, U('Business/Price/index'));
        } else {
            return response(2, '删除失败');
        }
    }
}