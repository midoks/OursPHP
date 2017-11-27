<?php 

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


return  [
    'deploy' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(多主多从配置(write|read))
    'driver' => 'mysql', //目前仅支持MySQL
    'option' => [
        'host'      => 'SAE_MYSQL_HOST_M',
        'port'      => 'SAE_MYSQL_PORT',
        'database'  => 'SAE_MYSQL_DB',
        'username'  => 'SAE_MYSQL_USER',
        'password'  => 'SAE_MYSQL_PASS',
        'charset'   => 'utf8',
        'collation' => '',
        'prefix'    => '',
        'option'    => [],
    ],
    'write'=> [[
        'host'      => 'SAE_MYSQL_HOST_M',
        'port'      => 'SAE_MYSQL_PORT',
        'database'  => 'SAE_MYSQL_DB',
        'username'  => 'SAE_MYSQL_USER',
        'password'  => 'SAE_MYSQL_PASS',
        'charset'   => 'utf8',
        'collation' => '',
        'prefix'    => '',
        'option'    => [],
        'weight'    => 3
    ]],
    'read'=> [[
        'host'      => 'SAE_MYSQL_HOST_S',
        'port'      => 'SAE_MYSQL_PORT',
        'database'  => 'SAE_MYSQL_DB',
        'username'  => 'SAE_MYSQL_USER',
        'password'  => 'SAE_MYSQL_PASS',
        'charset'   => 'utf8',
        'collation' => '',
        'prefix'    => '',
        'option'    => [],
        'weight'    => 3
    ]],
];

?>