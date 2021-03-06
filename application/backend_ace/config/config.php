<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

$commonConf = include dirname(APP_PATH) . DS . 'common' . DS . 'config' . DS . 'config' . EXT;

//默认配置文件
$appConfig = [
    'app_id'           => 'backend',

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 是否支持多模块
    'app_multi_module' => false,
];

$conf = array_merge($commonConf, $appConfig);

return $conf;

?>