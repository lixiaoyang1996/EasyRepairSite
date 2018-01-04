<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 9:41
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