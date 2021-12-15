<?php
return array(
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'rm-m5e7bt8779foxe81iso.mysql.rds.aliyuncs.com', // 服务器地址
    'DB_NAME'   => 'nccloud', // 数据库名
    'DB_USER'   => 'jm', // 用户名
    'DB_PWD'    => 'eW5qQ7QiWRZTFdW', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_CHARSET'=> 'utf8', // 字符集

    'HIS_DB' => array(
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => 'rm-m5e7bt8779foxe81iso.mysql.rds.aliyuncs.com', // 服务器地址
        'DB_NAME'   => 'dakang_yunhis_20211018', // 数据库名
        'DB_USER'   => 'jm', // 用户名
        'DB_PWD'    => 'eW5qQ7QiWRZTFdW', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PARAMS' =>  array(), // 数据库连接参数
        'DB_CHARSET'=> 'utf8', // 字符集
        'DB_DEBUG'  =>  TRUE,
    ),


    //'DEFAULT_MODULE'     => 'Admin', //默认模块
    'URL_MODEL'          => '3', //URL模式 REWRITE
    'SESSION_AUTO_START' => true, //是否开启session
);