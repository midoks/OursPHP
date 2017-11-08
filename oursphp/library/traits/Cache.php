<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\traits;

use frame\Config;
use frame\Logs;
use frame\App;

use frame\cache\driver\Memcache;
use frame\cache\driver\Memcached;
use frame\cache\driver\Redis;
use frame\cache\driver\None;

use frame\exception\CommonException;

trait Cache {

    public static $__cacheInstance = NULL;

    public static function getCacheObject(){
        
        if(isset(self::$__cacheInstance)){
           return self::$__cacheInstance;
        }

        $option = Config::get('cache');
        $name = strtolower($option['type']);
        
        App::$debug && Logs::record('[ CACHE ] INIT ' . $name, 'info');

        try {
            if (in_array($name, array('memcache', 'memcached','redis'))){
                if ( 'memcache' == $name){
                    self::$__cacheInstance =  Memcache::getInstance();
                } else if ('memcached' == $name) {
                    self::$__cacheInstance =  Memcached::getInstance();
                } else if( 'redis' == $name){ //Redis
                    self::$__cacheInstance =  Redis::getInstance();
                }
            } else {
                self::$__cacheInstance =  None::getInstance();
            }  
        } catch (\Exception $e) {
            $option['type'] = '';
            Config::set( $option, 'cache' );
            self::$__cacheInstance =  None::getInstance();
        }

        return  self::$__cacheInstance;
    }

    /**
     * 清空缓存
     * @param string $key 键值
     * @param string $node 节点名称
     * @return bool
     */
    public function cacheClear($key){
        $cacheObj = self::getCacheObject();
        $cacheObj->clear($key);
        return $this;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function cacheGet($name, $default = false){
        $cacheObj = self::getCacheObject();
        return $cacheObj->get($name, $default);
    }

    /**
     * 写入缓存
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param int       $expire  有效时间 0为永久
     * @return boolean
     */
    public function cacheSet($name, $value, $expire = null){
        $cacheObj = self::getCacheObject();
        return $cacheObj->set($name, $value, $expire);
    }   

    /** 
     * 写入缓存(如何已经存在返回false)
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param int       $expire  有效时间 0为永久
     * @return boolean
     */
    public function cacheAdd($name, $value, $expire = null){
        $cacheObj = self::getCacheObject();
        return $cacheObj->add($name, $value, $expire);
    }

    /**
     * 缓存
     * @param int 缓存时间
     * @param string $key 键值
     * @param string $node 节点名称
     * @return bool
     */
    public function cache($time = 30, $key = NULL){

        $this->__cache_open = true;

        $this->__cache_key = $key;
        $this->__cache_time = $time;
        return $this;
    }

    /**
     * 监听SQL查询前
     * @param string $sql SQL语句
     * @param array $bind 数组
     */
    private function cacheSqlStart($sql, $bind){

        if (!isset($this->__cache_open) || !$this->__cache_open){
            $this->__cache_open = false;
            return false;
        }

        if ( !isset($this->__cache_key)){
            $this->__cache_key = md5($sql.serialize($bind));
        }

        $key = $this->__cache_key;

      
        $cacheObj = self::getCacheObject();
        $cacheV = $cacheObj->get($key);
        
        if ($cacheV){
            return $cacheV;
        }

        return false;
    }

    /**
     * 监听SQL查询后台
     * @param string $sql SQL语句
     * @param array $bind 数组
     * @param $mixed 结果集
     */
    private function cacheSqlEnd($sql, $bind, $result){

        if (!isset($this->__cache_open) || !$this->__cache_open){
            $this->__cache_open = false;
            return false;
        }

        if (isset($this->__cache_key)){
            $cacheObj = self::getCacheObject();
            $cache = $cacheObj->add($this->__cache_key, $result, $this->__cache_time);
        }
    }

    /**
     * 魔术方法
     * @param $name
     * @param $params
     * @return mixed
     * @throws CommonException
     */
    public function __call($name, $params) {

        if (substr($name, -11) == '_with_cache') {

            $cacheObj = self::getCacheObject();
            $options = Config::get('cache');
            $type = strtolower($options['type']);


            $relFunc = substr($name, 0, strlen($name)-11);
            $key = md5($name.serialize($params));


            $cacheParam = isset($params[0]) ? $params[0]: null;
            $cacheTime = $options['expire'];

            if ( $cacheParam ){
                 parse_str($cacheParam, $cacheParse);

                 if (isset($cacheParse['time'])){
                    $cacheTime = $cacheParse['time'];
                 }

                 if (isset($cacheParse['key'])){
                    $key = $cacheParse['key'];
                 }
            }

            //第一个参数不是原方法参数,去除
            array_shift($params);

            if ( empty( $type ) ) {
                $data = call_user_func_array(array($this, $relFunc), $params);
                return $data;
            }

            //获取值
            $data = $cacheObj->get($key);
            if ($data) {
                return $data;
            }

            //保存值
            if( $cacheObj->add($key, null, $cacheTime) ){
                $data = call_user_func_array(array($this, $relFunc), $params);
                $cacheObj->set($key, $data, $cacheTime);
            } else {

                for( $i=0; $i < 3; $i++ ) { //0.3秒没有反应，就出白页吧，系统貌似已经不行了
                    sleep(0.1);
                    $data = $cacheObj->get($key);
                    if ($data !== false){ break; }
                }
            }

            return $data;
        }
    }






}