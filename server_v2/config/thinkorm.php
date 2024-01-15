<?php

return [
    'default' => 'mysql',
//    'connections' => [
//        'mysql' => [
//            // 数据库类型
//            'type' => 'mysql',
//            // 服务器地址
//            'hostname' => '127.0.0.1',
//            // 数据库名
//            'database' => 'test',
//            // 数据库用户名
//            'username' => 'root',
//            // 数据库密码
//            'password' => '123456',
//            // 数据库连接端口
//            'hostport' => '3306',
//            // 数据库连接参数
//            'params' => [
//                // 连接超时3秒
//                \PDO::ATTR_TIMEOUT => 3,
//            ],
//            // 数据库编码默认采用utf8
//            'charset' => 'utf8',
//            // 数据库表前缀
//            'prefix' => '',
//            // 断线重连
//            'break_reconnect' => true,
//            // 关闭SQL监听日志
//            'trigger_sql' => true,
//            // 自定义分页类
//            'bootstrap' =>  ''
//        ],
//    ],

    //数据库配置
    'connections' => [
        //数据库配置
        'auth' => [
            // 数据库类型
            'type'            =>  'mysql',
            // 服务器地址
            'hostname'    => getenv('AUTH_HOSTNAME')?:'127.0.0.1',
            // 数据库名
            'database'    => getenv('AUTH_DATABASE')?:'',
            // 数据库用户名
            'username'    => getenv('AUTH_USERNAME')?:'root',
            // 数据库密码
            'password'    => getenv('AUTH_PASSWORD')?:'',
            // 端口
            'hostport'    => getenv('AUTH_PORT')?:'',
            //默认编码
            'charset'     => 'utf8'
        ],
        'characters' => [
            // 数据库类型
            'type'            =>  'mysql',
            // 服务器地址
            'hostname'    => getenv('CHARACTERS_HOSTNAME')?:'127.0.0.1',
            // 数据库名
            'database'    => getenv('CHARACTERS_DATABASE')?:'',
            // 数据库用户名
            'username'    => getenv('CHARACTERS_USERNAME')?:'root',
            // 数据库密码
            'password'    => getenv('CHARACTERS_PASSWORD')?:'',
            // 端口
            'hostport'    => getenv('CHARACTERS_PORT')?:'3306',
            //默认编码
            'charset'     => 'utf8'
        ],
        'world' => [
            // 数据库类型
            'type'            =>  'mysql',
            // 服务器地址
            'hostname'    => getenv('WORLD_HOSTNAME')?:'127.0.0.1',
            // 数据库名
            'database'    => getenv('WORLD_DATABASE')?:'',
            // 数据库用户名
            'username'    => getenv('WORLD_USERNAME')?:'root',
            // 数据库密码
            'password'    => getenv('WORLD_PASSWORD')?:'',
            // 端口
            'hostport'    => getenv('WORLD_PORT')?:'3306',
            //默认编码
            'charset'     => 'utf8'
        ],
    ]
];
