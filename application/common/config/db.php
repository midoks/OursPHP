<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

$options = array(
	'default'=> [
		'deploy' => 0, 			// 数据库部署方式:0 集中式(单一服务器),1 分布式(多主多从配置(write|read))
		'driver' => 'mysql', 	//目前仅支持MySQL
		'option' => [
			'host'		=> '192.168.57.91',
			'port'		=> '3307',
			'database'	=> 'test',
			'username'	=> 'yoka',
			'password'	=> 'yoka.com',
			'charset'	=> 'utf8',
			'collation'	=> '',
			'prefix'	=> '',
			'option'	=> [],
		],
		'write'=> [[
			'host'		=> '192.168.57.91',
			'port'		=> '3307',
			'database'	=> 'test',
			'username'	=> 'yoka',
			'password'	=> 'yoka.com',
			'charset'	=> 'utf8',
			'collation'	=> '',
			'prefix'	=> '',
			'option'	=> [],
			'weight'	=> 3
		]],
		'read'=> [[
			'host'		=> '192.168.57.91',
			'port'		=> '3307',
			'database'	=> 'test',
			'username'	=> 'yoka',
			'password'	=> 'yoka.com',
			'charset'	=> 'utf8',
			'collation'	=> '',
			'prefix'	=> '',
			'option'	=> [],
			'weight'	=> 9
		]]
	],
	'test1'=> [
		'deploy' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(多主多从配置(write|read))
		'driver' => 'mysql', //目前仅支持MySQL
		'option' => [
			'host'		=> '192.168.51.16',
			'port'		=> '3306',
			'database'	=> 'NewsSum',
			'username'	=> 'root',
			'password'	=> 'yvch500M',
			'charset'	=> 'utf8',//latin1
			'collation'	=> '',
			'prefix'	=> '',
			'option'	=> [],
		]
	]
);
return $options;