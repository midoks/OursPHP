<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;

class Config {

    private static $_config;
    private static $range = '_sys_';

    /**
     * 获取配置信息
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function get($name = NULL, $range = '') {
        //var_dump($name);
        $range = $range ?: self::$range;

        if (empty($name) && isset(self::$_config[$range])) {
            return self::$_config[$range];
        }
        return isset(self::$_config[$name])? self::$_config[$name] : NULL;
    }

    /**
     * 合并配置信息
     * @param array $config
     * @return array
     */
    public static function merge($config){
        self::$_config = array_merge(self::$_config, $config);
        return self::$_config;
    }

    /**
     * 设置配置信息
     * @param $config array
     * @return void
     */
    public static function set($config){
        self::$_config = $config;
    }
}