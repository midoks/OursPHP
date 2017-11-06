<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\cache;

use \frame\Config;
use \frame\exception\CommonException;

class Memcache {

    private static $_cache;
    private static $_config = NULL;
    private static $_node   = NULL;

    private function __construct() {}

    /**
     * 注册memcached配置信息
     * @param string $config 配置文件KEY
     */
    public static function injectOption($opName){
        self::$_config = Config::get($opName);
        return true;
    }

    /**
     * 注册memcached配置信息
     * @param array $config 配置信息
     */
    public static function injectConfig($config){
        self::$_config = $config;
        return true;
    } 

    /**
     * 获取指定配置节点的memcache实例
     * @param string $node
     * @return \Memcached
     */
    public static function  getInstance() {

        if (!extension_loaded('memcache')) {
            throw new \BadFunctionCallException('not support: memcache');
        }

        $node = 'memcached';
        if (self::$_node){
            $node = self::$_node;
        }
        
        if(!isset(self::$_cache[$node])) {
            
            $_mem = new \Memcache();
            if ( !empty(self::$_config) ){
                $options    = self::$_config;
            } else {
                $options    = Config::get($node);
            }

            if( $options == false ) {
                throw new CommonException("memcache缓存相关节点未配置：".$node, 'memcache');
            }

            foreach ($options as $key => $value) {
                $_mem->addServer($value[0], $value[1]);
            }

            self::$_cache[$node] = $_mem;
        }
        return self::$_cache[$node];
    }
}