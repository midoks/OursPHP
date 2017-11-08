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
     * @param string $name 配置名
     * @return mixed
     */
    public static function get($name = null) {

        if(empty($name) ) {
            return self::$_config;
        }

        return isset(self::$_config[$name])? self::$_config[$name] : null;
    }

    /**
     * 合并配置信息
     * @param array $config
     * @return array
     */
    public static function merge($config, $name = null){
        if (is_null($name)){
            self::$_config = array_merge(self::$_config, $config);
        } else {
            self::$_config[$name] = array_merge(self::$_config[$name], $config);
        }
        return self::$_config;
    }

    /**
     * 设置配置信息
     * @param $config array
     * @return void
     */
    public static function set($config, $name = null){
        
        if ( is_null($name) ){
            self::$_config = $config;
        } else {
            self::$_config[$name] = $config;
        }
    }
}