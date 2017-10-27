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

class Memcached {

    private static $_cache;
    private static $_config = NULL;
    private static $_node   = NULL;

    private function __construct() {}

     /**
     * 注册memcached配置信息
     * @param string $config 配置文件KEY
     */
    public static function registerOption($option_name){
        self::$_config = Config::get($options);
        return true;
    }

    /**
     * 注册memcached配置信息
     * @param array $config 配置信息
     */
    public static function registerConfig($config){
        self::$_config = $config;
        return true;
    } 

    /**
     * 获取指定配置节点的memcached实例
     * @param string $node
     * @return \Memcached
     */
    public static function  getInstance() {
        
        if(!isset(self::$_cache[$node])) {
            
            $_memd = new \Memcached();
            if ( !empty(self::$_config) ){
                $options    = self::$_config;
            } else {
                $options    = Config::get('memcached');
            }

            if( $options == false ) {
                throw new BizException("memcached缓存相关节点未配置：".$node);
            }

            $_memd->addServers($options);
            self::$_cache[$node] = $_memd;
        }
        return self::$_cache[$node];
    }


    public static function accessCache($key, $time, $get_data_func, $func_params=array(),$nodeName='default') {
        $_memcached=self::getInstance($nodeName);
        $data=$_memcached->get($key);
        if (empty($data) || isset($_GET['_refresh'])) {
            $data = call_user_func_array($get_data_func, $func_params);
            $_memcached->set($key, $data, $time);
        }
        return $data;
    }

    /**
     * 本缓存不会存 [0 false null] 加锁
     * @param $key
     * @param $time
     * @param $get_data_func
     * @param array $func_params
     * @return mixed
     */
    public static function accessCacheWithLock($key, $time, $get_data_func, $func_params=array(),$nodeName='default') {
        $_memcached=self::getInstance($nodeName);
        $data = $_memcached->get($key);


        if (empty($data) || isset($_GET['_refresh'])) {
            if($_memcached->add($key, null)) {
                $data = call_user_func_array($get_data_func, $func_params);
                if (!empty($data))
                    $_memcached->set($key, $data, $time);

            } else {
                for($i=0; $i<10; $i++) { //5秒没有反应，就出白页吧，系统貌似已经不行了
                    sleep(0.5);
                    $data = $_memcached->get($key);
                    if ($data !== false)
                        break;

                }
            }
        }
        return $data;
    }
}