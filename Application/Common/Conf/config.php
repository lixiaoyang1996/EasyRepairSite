<?php

return array(
    /* 加载额外配置 */
    'LOAD_EXT_CONFIG' => 'dbConfig',// 数据库配置

    /* Auth权限认证配置 */
    'AUTH_ON' => true,                      // 认证开关
    'AUTH_TYPE' => 1,                         // 认证方式，1为实时认证；2为登录认证。
    'AUTH_GROUP' => 'auth_group',        // 用户组数据表名
    'AUTH_GROUP_ACCESS' => 'auth_group_access', // 用户-用户组关系表
    'AUTH_RULE' => 'auth_rule',         // 权限规则表
    'AUTH_USER' => 'users',            // 用户信息表

    /* 定义常用路径 */
    'TMPL_PARSE_STRING' => array(
        '__PICTURE__' => __ROOT__ . '/Upload/Pictures',// 图片上传路径
        '__COMPONENTS__' => __ROOT__ . '/Public/components',// 第三方组件路径
        '__PLUGINS__' => __ROOT__ . '/Public/plugins',// 第三方插件路径
        '__ADMINLTE__' => __ROOT__ . '/Tpl/Public/adminlte',// adminlte文件路径
    ),
);