<?php
/**
 * User: njzy
 * Date: 2017/12/27
 * Time: 上午8:18
 */


function response($code = '', $message = '', $data = array())
{
    $res = array(
        'code' => $code,
        'message' => $message,
        'data' => $data
    );
    exit(json_encode($res));
}