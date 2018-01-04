<?php
/**
 * User: njzy
 * Date: 2018/1/3
 * Time: 下午9:37
 */

namespace Admin\Controller;

use Common\Controller;
use Think\Model;

class TypeController extends Controller\AdminBaseController
{
    public function index()
    {
        $model = M('type');
        $ptypes = $model->where(array('pid' => 0))->order('sort')->select();
        $ctypes = $model->where(array('pid' => array('neq', 0)))->order('sort')->select();
        $this->assign('ptypes', $ptypes);
        $this->assign('ctypes', $ctypes);
        $this->display();
    }

    public function add()
    {
        $model = M('type');
        $ptypes = $model->where(array('pid' => 0, 'show' => array('neq', 0)))->select();
        $ctypes = $model->where(array('pid' => array('neq', 0), 'show' => array('neq', 0)))->select();
        $this->assign('ptypes', $ptypes);
        $this->assign('ctypes', $ctypes);
        $this->display();
    }

    public function cadd()
    {
        $id = I('id');
        $model = M('type');
        $type = $model->where(array('id' => $id))->find();
        $ptypes = $model->where(array('pid' => 0, 'show' => array('neq', 0)))->select();
        $this->assign('ptypes', $ptypes);
        $this->assign('type', $type);
        $this->display();
    }

    public function edit()
    {
        $id = I('id');
        $model = M('type');
        $type = $model->where(array('id' => $id))->find();
        $ptypes = $model->where(array('pid' => 0, 'show' => array('neq', 0)))->select();
        $ctypes = $model->where(array('pid' => array('neq', 0), 'show' => array('neq', 0)))->select();
        $this->assign('ptypes', $ptypes);
        $this->assign('ctypes', $ctypes);
        $this->assign('type', $type);
        $this->display();
    }

    public function save()
    {
        $model = M('type');
        $data = $model->create();
        if ($data['id']) {
            $res = $model->save($data);
        } else {
            $res = $model->add($data);
        }
        if ($res >= 0) {
            response(1, '保存成功！', null, U('Admin/Type/index'));
        } else {
            response(2, '保存失败！');
        }
    }

    public function del()
    {
        $id = I('id');
        $model = new Model();
        $sql = "select id from ers_type where FIND_IN_SET(id,getChildList({$id}))";
        $arr = $model->query($sql);
        for ($i = 0; $i < count($arr); $i++) {
            $id = $arr[$i]['id'];
            $model = M('type');
            $res = $model->where(array('id' => $id))->delete();
        }
        if ($res) {
            response(1, '删除成功！', null, U('Admin/Type/index'));
        } else {
            response(2, '删除失败！');
        }
    }
}