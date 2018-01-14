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
        $shop = M("shop");
        $stid = $shop->where("id=$sid")->getField('tid');

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

        $type = M("type");
        $ptid = $type->where("name='$Type'")->getField('id');

        $this->assign('p', $p);
        $this->display();
    }

    public function add()
    {
        $result = $_SESSION['userId'];
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');

        $shop = M("shop");
        $tid = $shop->where("id=$sid")->getField('tid'); //2

        $type = M("type");
        $t = $type->where("pid=$tid")->select();

        $ctypes = $type->where(array(
                'pid' => array('neq', 0),
                "pid!=1",
                "pid!=$tid"
            )
        )->select();

        $price = M('price');
        $p = $price->select();

//        $id = array_column($ctypes, id);
//        for ($i = 0; $i < count($id); $i++) {
//            $a = $id[$i];
//            for ($j = 0; $j < count($p); $j++) {
//                $b = $p[$j]["tid"];
//            }
//            var_dump($a);
////            var_dump($b);
//        }

        $this->assign('ctypes', $ctypes);
        $this->assign('t', $t);
        $this->display();
    }

    public function doadd()
    {
        $result = $_SESSION['userId'];
        $user = M("users");
        $sid = $user->where("id=$result")->getField('sid');


        $shop = M("shop");
        $tid = $shop->where("id=$sid")->getField('tid'); //2

        $type = M("type");
        $type->where("pid=$tid")->select();
        $ctypes = $type->where(array(
            'pid' => array('neq', 0),
            "pid!=1",
            "pid!=$tid"
        ))->select();

        $cid = I["tid"];
        $price = M('price');
        $p = $price->select();
        $data = $price->create();
        $data['sid'] = $sid;


        for ($j = 0; $j < count($p); $j++) {
            $a = $p[$j]["tid"];
        }
        if ($cid == $a) {
            response(2, '添加失败！');
        } else {
            $result = $price->add($data);
            if ($result > 0) {
                return response(1, '添加成功！', null, U('Business/Price/index'));
            } else {
                response(2, '添加失败！');
            }
        }


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