<?php
/**
 * User: njzy
 * Date: 2017/12/25
 * Time: 上午11:09
 */

namespace Common\Controller;

use Think\Controller;

class AdminBaseController extends Controller
{
    public function _initialize()
    {
        // 判断用户是否有权限登录管理员后台
        $auth = new \Think\Auth();
        $result = $auth->check('Admin/*', $_SESSION['userId']);
        if (!$result) {
            header('location:' . U('Admin/Login/index'));
        }
    }

    /**
     *判断是否支持pjax
     */
    public function display()
    {
        if (array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX']) {
            //浏览器支持pjax，直接输出模板
            parent::display();
        } else {
            //浏览器不支持pjax，开启模板布局功能，拼接html
            layout(true);
            parent::display();
        }
    }
}