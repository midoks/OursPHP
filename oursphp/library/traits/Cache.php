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
use frame\cache\driver\Memcache;
use frame\cache\driver\Memcached;
use frame\cache\driver\Redis;

use frame\exception\CommonException;

trait Cache {

    public static $__cacheInstance = NULL;

    public static function getCacheObj(){
        $option = Config::get('cache');

        if(isset(self::$__cacheInstance)){
           return self::$__cacheInstance;
        }

        if (in_array($option['type'], array('memcache', 'memcached','redis'))){
            $name = strtolower($option['type']);
            if ( 'memcache' == $name){
                self::$__cacheInstance =  Memcache::getInstance();
            } else if ('memcached' == $name) {
                self::$__cacheInstance =  Memcached::getInstance();
            } else {
                self::$__cacheInstance =  Redis::getInstance();
            }
            
            return  self::$__cacheInstance;
        }
        throw new CommonException('cache配置错误!!!');
    }

    /**
     * 清空缓存
     * @param string $key 键值
     * @param string $node 节点名称
     * @return bool
     */
    public function clear($key){
        return true;
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

        try {
            $cacheObj = self::getCacheObj();
            $cacheV = $cacheObj->get($key);
        } catch (\Exception $e) {
            return false;
        }
        
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
            $cacheObj = self::getCacheObj();
            $cache = $cacheObj->set($this->__cache_key, $result, 300);
        }
    }


    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws CommonException
     */
    public function __call($name, $params) {

        var_dump($name, $params);

        // $options = Config::get('withcache', WEB_NAMESPACE);

        // if (OURS_DEBUG) {
        //     echo '<!-- withcache: '.json_encode($options).'-->'."\r\n";
        // }

        // if (substr($name, -11) == '_with_cache') {

        //     $relFunc = substr($name, 0, strlen($name)-11);
        //     $key = md5($name.serialize($params));
        //     $cache_time_params = isset($params[0])? $params[0]:null;


        //     if($options == false || $options['used'] == false) {
        //         echo '<!-- withcache: nocache-->'."\r\n";
        //         return call_user_func_array(array($this, $relFunc), $params);
        //     }

        //     $cache_time = $options['cachetime'];
        //     $cache_type = $options['type'];
        //     $cache_nodename = $options['nodename'];
        //     if (is_string($cache_time_params) && substr($cache_time_params, 0, 11) == 'cache_time=') {
        //         $cache_time = intval(substr($cache_time_params, 11));
        //         array_shift($params); //第一个参数是缓存时间不是函数用的，去除
        //     }
        //     $cache_key_params = isset($params[0])? $params[0]: null;
        //     if (is_string($cache_key_params) && substr($cache_key_params, 0, 10) == 'cache_key=') {
        //         $key = substr($cache_key_params, 10);
        //         array_shift($params); //第一个参数是缓存时间不是函数用的，去除
        //     }
           
        //     switch ($cache_type) {
        //         case 'memcached':

        //             $data = Memcached::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
        //             return $data;
        //             break;
        //         case 'redis':
        //             $data = Redis::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
        //             return $data;
        //             break;
        //         default:
        //             $data =  call_user_func_array(array($this, $relFunc), $params);
        //             return $data;
        //             break;
        //     }
        // }
    }






}