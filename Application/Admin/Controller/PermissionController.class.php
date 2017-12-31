<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 下午7:53
 */

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * Class PermissionController
 * @package Admin\Controller
 */
class PermissionController extends AdminBaseController
{
    /**
     *页面初始化
     */
    public function index()
    {
        $model = M('auth_rule');
        $rules = $model->select();
        $this->assign('rules', $rules);
        $this->display();
    }

    /**
     *权限修改界面
     */
    public function edit()
    {
        $id = I('id');
        $model = M('auth_rule');
        $rule = $model->where(array('id' => $id))->find();
        $this->assign('rule', $rule);
        $this->display();
    }

    /**
     *权限新建界面
     */
    public function add()
    {
        $this->display();
    }

    /**
     *对权限数据进行保存
     */
    public function save()
    {
        $model = M('auth_rule');
        $data = $model->create();
        if ($data['id']) {
            $res = $model->save($data);
        } else {
            $res = $model->add($data);
        }
        if ($res >= 0) {
            response(1, '保存成功！', null, U('Admin/Permission/index'));
        } else {
            response(2, '保存失败！');
        }
    }

    /**
     *删除权限
     */
    public function del()
    {
        $id = I('id');
        $model = M('auth_rule');
        $res = $model->where(array('id' => $id))->delete();
        if ($res) {
            response(1, '删除成功！', null, U('Admin/Permission/index'));
        } else {
            response(2, '删除失败！');
        }
    }
}