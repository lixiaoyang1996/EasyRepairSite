<?php

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class IndexController extends Controller
{
    public function index()
    {
        $model = M('shop');
        if ($sid = I('sid')) {
            $count=$model->where(array('status' => 1, 'check' => 1, 'tid' => $sid))->count();
            $Page=new \Think\Page($count,5);
            $shops = $model->where(array('status' => 1, 'check' => 1, 'tid' => $sid))->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $model = M('type');
            $scopes = $model->where(array('pid' => 0))->select();
            $types = $model->where(array('pid' => $sid))->select();
            for ($j = 0; $j < count($types); $j++) {
                $brands = $model->where(array('pid' => $types[$j]['id']))->select();
            }
        } else if ($tid = I('tid')) {
            $model = new Model();
            $sql = "select id from ers_type where FIND_IN_SET(id,getParList($tid))";
            $res = $model->query($sql);
            $sid = $res[0]['id'];
            $model = M('shop');
            $count=$model->where(array('status' => 1, 'check' => 1, 'tid' => $sid))->count();
            $Page=new \Think\Page($count,5);
            $shops = $model->where(array('status' => 1, 'check' => 1, 'tid' => $sid))->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $model = M('type');
            $scopes = $model->where(array('pid' => 0))->select();
            for ($i = 0; $i < count($scopes); $i++) {
                $types = $model->where(array('pid' => $scopes[$i]['id']))->select();
            }
            $brands = $model->where(array('pid' => $tid))->select();
        } else if ($bid = I('bid')) {
            $model = new Model();
            $sql="select count(*) as count from ers_shop as s,ers_price as p where s.status=1 and s.check=1 and p.sid=s.id and p.tid={$bid}";
            $res=$model->query($sql);
            $count=$res[0]['count'];
            $Page=new \Think\Page($count,5);
            $sql = "select s.* from ers_shop as s,ers_price as p where s.status=1 and s.check=1 and p.sid=s.id and p.tid={$bid} limit {$Page->firstRow},{$Page->listRows}";
            $shops = $model->query($sql);
            $model = M('type');
            $scopes = $model->where(array('pid' => 0))->select();
            for ($i = 0; $i < count($scopes); $i++) {
                $types = $model->where(array('pid' => $scopes[$i]['id']))->select();
                for ($j = 0; $j < count($types); $j++) {
                    $brands = $model->where(array('pid' => $types[$j]['id']))->select();
                }
            }
        } else {
            $count = $model->where(array('status' => 1, 'check' => 1))->count();
            $Page = new \Think\Page($count, 5);
            $shops = $model->where(array('status' => 1, 'check' => 1))->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $model = M('type');
            $scopes = $model->where(array('pid' => 0))->select();
            for ($i = 0; $i < count($scopes); $i++) {
                $types = $model->where(array('pid' => $scopes[$i]['id']))->select();
                for ($j = 0; $j < count($types); $j++) {
                    $brands = $model->where(array('pid' => $types[$j]['id']))->select();
                }
            }
        }
        for ($i = 0; $i < count($shops); $i++) {
            $model = new Model();
            $sql = "select u.address,t.name as type from ers_users as u,ers_type as t,ers_shop as s where u.sid=s.id and s.tid=t.id and s.id={$shops[$i]['id']}";
            $res = $model->query($sql);
            $shops[$i]['address'] = $res[0]['address'];
            $shops[$i]['type'] = $res[0]['type'];
            $sql = "select price from ers_price where sid={$shops[$i]['id']} ORDER BY price";
            $res = $model->query($sql);
            $shops[$i]['minprice'] = $res[0]['price'];
        }
        $show = $Page->show();
        $this->assign('scopes', $scopes);
        $this->assign('types', $types);
        $this->assign('brands', $brands);
        $this->assign('shops', $shops);
        $this->assign('page', $show);
        $this->display();
    }

    public function view()
    {
        $id = I('id');
        $model = M('shop');
        $shop = $model->where(array('id' => $id, 'status' => 1, 'check' => 1))->find();
        $model = new Model();
        $sql = "select u.address,t.name as type from ers_users as u,ers_type as t,ers_shop as s where u.sid=s.id and s.tid=t.id and s.id={$id}";
        $res = $model->query($sql);
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
        $model = M('order');
        $data = $model->create();
        $userId = $_SESSION['userId'];
        $arr = $data['pid'];
        for ($i = 0; $i < count($arr); $i++) {
            $create_time = time();
            $model->add(array(
                'uid' => $userId,
                'sid' => $data['sid'],
                'pid' => $arr[$i],
                'create_time' => $create_time
            ));
        }
        response('1', '订单提交成功，请到后台进行确认！', null, U('User/EnsureOrder/EnsureOrder'));
    }

    public function search(){
        $key=I('key');
        $model=M('shop');
        $res=$model->where(array('name'=>array('like','%'.$key.'%')))->field('name,id')->select();
        response(1,'',$res,'');
    }

    public function find(){
        $id=I('id');
        $url=U('Home/Index/view',array('id'=>$id));
        response(1,'',null,$url);
    }
}