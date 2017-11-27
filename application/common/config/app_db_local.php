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
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'database'  => 'test',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => '',
        'prefix'    => '',
        'option'    => [],
    ]
];

?>