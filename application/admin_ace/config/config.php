<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------
$app_debug = false;
if (isset($_GET['debug']) && $_GET['debug'] == 'ok'){
    $app_debug = true;
}
//默认配置文件
return [

	'app_id'           => 'admin_ace',
    'app_debug'         => $app_debug,
    'app_trace'         => $app_debug,

	// +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

	// 是否支持多模块
    'app_multi_module'       => false,


    'db'                => [
        'deploy' => 1, // 数据库部署方式:0 集中式(单一服务器),1 分布式(多主多从配置(write|read))
        'driver' => 'mysql', //目前仅支持MySQL
        'option' => [
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => '',
            'prefix'    => '',
            'option'    => [],
        ],
        'write'=> [[
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => '',
            'prefix'    => '',
            'option'    => [],
            'weight'    => 3
        ]],
        'read'=> [[
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => '',
            'prefix'    => '',
            'option'    => [],
            'weight'    => 3
        ],[
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => '',
            'prefix'    => '',
            'option'    => [],
            'weight'    => 6
        ]],
    ],

    'memcached' => [
        ['127.0.0.1', 11211]
    ],

    'redis'     => [
        ['127.0.0.1', 6379, '', '']
    ],
    
];

?>