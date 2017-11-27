<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

if (isset($_GET['phpinfo']) && $_GET['phpinfo'] == 'ok'){
    phpinfo();exit;
}


$app_debug = false;
if (isset($_GET['debug']) && $_GET['debug'] == 'ok'){
    $app_debug = true;
}

$app_trace = false;
if (isset($_GET['trace']) && $_GET['trace'] == 'ok'){
    $app_trace = true;
}



$dbConfName = 'app_db_local';
if (defined('SAE_ACCESSKEY')){
    $dbConfName = 'app_db_sae';
}

$config = [

    //第三放登录配置
    'app_sdk' => include( dirname(__FILE__).DS.'app_sdk'.EXT),

    
    'url_html_suffix'        => 'html', // url后缀

    'app_debug'         => true,//$app_debug, true
    'app_trace'         => $app_trace,//$app_trace, true

    'db'   => include( dirname(__FILE__).DS.$dbConfName.EXT),
    

    'memcached' => [
        [
            'host'       => '127.0.0.1',
            'port'       => 11211,
            'expire'     => 30, // {以第一个为准}
            'timeout'    => 0, // 超时时间（单位：毫秒） {以第一个为准}
            'username'   => '', //账号 {以第一个为准}
            'password'   => '', //密码  {以第一个为准}
            'persistent' => false,
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

    'cache'                  => [
        // 驱动方式 支持 redis memcache memcached
        'type'   => 'memcache',
        // 缓存保存目录
        'path'   => '',
        // 缓存前缀
        'prefix' => 'oursphp_',
        // 缓存有效期 0表示永久缓存
        'expire' => 30,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        'session_name'   => 'oursphp_session_',
        // SESSION 前缀
        'prefix'         => 'oursphp_session_',
        // 驱动方式 支持 redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
        'httponly'       => true,
        'secure'         => false,
    ],
];

if (defined('SAE_TMP_PATH')){
    $run = ['runtime_cache' => SAE_TMP_PATH];
    $config = array_merge($config, $run);
}

return $config;

?>

