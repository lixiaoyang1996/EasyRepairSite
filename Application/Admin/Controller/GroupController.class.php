<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 下午7:57
 */

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

class GroupController extends AdminBaseController
{
    public function index()
    {
        $model = M('auth_group');
        $groups = $model->select();
        $this->assign('groups', $groups);
        $this->display();
    }

    /**
     *用户组修改界面
     */
    public function edit()
    {
        $id = I('id');
        $model = M('auth_group');
        $group = $model->where(array('id' => $id))->find();
        $rules = $group['rules'];
        $arr = explode(",", $rules);
        $model = M('auth_rule');
        $rules = $model->select();
        for ($i = 0; $i < count($rules); $i++) {
            for ($j = 0; $j < count($arr); $j++) {
                if ($arr[$j] == $rules[$i]['id']) {
                    $rules[$i]['checked'] = 1;
                    break;
                }
                $rules[$i]['checked'] = 0;
            }
        }
        $this->assign('group', $group);
        $this->assign('rules', $rules);
        $this->display();
    }

    /**
     *用户组新建界面
     */
    public function add()
    {
        $model = M('auth_rule');
        $rules = $model->select();
        $this->assign('rules', $rules);
        $this->display();
    }

    /**
     *对用户组数据进行保存
     */
    public function save()
    {
        $model = M('auth_group');
        $data = $model->create();
        $data['rules'] = implode(',', $data[rules]);
        if ($data['id']) {
            $res = $model->save($data);
        } else {
            $res = $model->add($data);
        }
        if ($res >= 0) {
            response(1, '保存成功！', null, U('Admin/Group/index'));
        } else {
            response(2, '保存失败！');
        }
    }

    /**
     *删除用户组
     */
    public function del()
    {
        $id = I('id');
        $model = M('auth_group');
        $res = $model->where(array('id' => $id))->delete();
        if ($res) {
            response(1, '删除成功！', null, U('Admin/Group/index'));
        } else {
            response(2, '删除失败！');
        }
    }
}