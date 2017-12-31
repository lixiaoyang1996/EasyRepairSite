<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 上午8:18
 */

/**
 * ajax请求响应函数
 *
 * @param string $code 响应编号，用户layer弹出层样式变化 1：成功 2：失败
 * @param string $message 响应信息
 * @param array $data 返回的响应数据
 * @param string $url 跳转url
 */
function response($code = '', $message = '', $data = array(), $url = '')
{
    $res = array(
        'code' => $code,
        'message' => $message,
        'data' => $data,
        'url' => $url
    );
    exit(json_encode($res));
}