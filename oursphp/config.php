<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

//默认配置文件
return [
	

	// +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    'app_id'                 => 'app_oursphp',
    'app_debug'              => false,
    'app_trace'              => false,
    'app_multi_module'       => true,   // 是否支持多模块
    'default_path_access'    => true,   // 路径访问
    'default_timezone'       => 'PRC',  // 默认时区


    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    'url_model'              => 'pathinfo',//url,pathinfo
    'url_html_suffix'        => '', // url后缀
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    'pathinfo_depr'          => '/',// pathinfo分隔符
    

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认接手args参数
    'module_key'            =>  '_m',
    'controller_key'        =>  '_c',
    'action_key'            =>  '_a',
    
    'module_value'          => 'index', // 默认模块名
    'controller_value'      => 'Index', // 默认控制器名
    'action_value'          => 'index', // 默认操作名

    'deny_module_list'      => ['common'],// 禁止访问模块
    'empty_controller'      => 'Error',   // 默认的空控制器名


    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    'common'                => dirname(OURS_PATH).DS.'application'.DS.'common'.DS,


    // +----------------------------------------------------------------------
    // | 数据库及缓存默认设置
    // +----------------------------------------------------------------------
    'db'                => [
        'deploy' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(多主多从配置(write|read))
        'driver' => 'mysql', //目前仅支持MySQL
        'option' => [
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => '',
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
            'password'  => '',
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
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => '',
            'prefix'    => '',
            'option'    => [],
            'weight'    => 3,
        ]],
    ],
    
    'log'                    => [
        // 日志记录方式，内置 file none 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => dirname(OURS_PATH).DS.'runtime'.DS
    ],



    'memcached' => [
        [
            'host'       => '127.0.0.1',
            'port'       => 11211,    
            'expire'     => 30,
            'timeout'    => 0,  // 超时时间（单位：毫秒）
            'username'   => '', //账号
            'password'   => '', //密码
            'persistent' => true,
            'option'     => [],
        ]
    ],


    'redis'     => [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
    ],

    'cache'     => [
        // 驱动方式 支持 redis memcache memcached
        'type'   => '',
        // 缓存保存目录
        'path'   => '',
        // 缓存前缀
        'prefix' => 'oursphp_',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'oursphp_session_',
        // 驱动方式 支持redis memcache memcached
        'type'           => 'memcache',
        // 是否自动开启 SESSION
        'auto_start'     => true,
        'httponly'       => true,
        'secure'         => false,
    ],


    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    //默认目录设置
    'runtime_cache'    => dirname(OURS_PATH).DS.'runtime'.DS,

    //模版设置
    'template'         => [
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}}'
    ],


    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => OURS_PATH . 'tpl' . DS . 'frame_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \frame\exception\Handle
    'exception_handle'       => '',

    
    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'record_trace'          => true,
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],
];

?>