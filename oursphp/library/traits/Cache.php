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
use frame\Cache\Memcache;

trait Cache {

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


    private function cacheSqlStart($sql, $bind){

        if (!isset($this->__cache_open) || !$this->__cache_open){
            $this->__cache_open = false;
            return false;
        }

        $mem = Memcache::getInstance();

        if ( !isset($this->__cache_key)){
            $this->__cache_key = md5($sql.serialize($bind));
        }

        $key = $this->__cache_key;

        try {
            $cacheV = $mem->get($key);
        } catch (\Exception $e) {
            return false;
        }
        
       
        if ($cacheV){
            return $cacheV;
        }

        return false;
    }

    /**
     *
     */
    private function cacheSqlEnd($sql, $bind, $result){

        if (!isset($this->__cache_open) || !$this->__cache_open){
            $this->__cache_open = false;
            return false;
        }
        
        $mem = Memcache::getInstance();
    
        if (isset($this->__cache_key)){
            $cache = $mem->add($this->__cache_key, $result);
        }
    }


    // /**
    //  * @param $name
    //  * @param $params
    //  * @return mixed
    //  * @throws BizException
    //  */
    // public function __call($name, $params) {

    //     $options = Config::get('withcache', WEB_NAMESPACE);

    //     if (OURS_DEBUG) {
    //         echo '<!-- withcache: '.json_encode($options).'-->'."\r\n";
    //     }

    //     if (substr($name, -11) == '_with_cache') {

    //         $relFunc = substr($name, 0, strlen($name)-11);
    //         $key = md5($name.serialize($params));
    //         $cache_time_params = isset($params[0])? $params[0]:null;


    //         if($options == false || $options['used'] == false) {
    //             echo '<!-- withcache: nocache-->'."\r\n";
    //             return call_user_func_array(array($this, $relFunc), $params);
    //         }

    //         $cache_time = $options['cachetime'];
    //         $cache_type = $options['type'];
    //         $cache_nodename = $options['nodename'];
    //         if (is_string($cache_time_params) && substr($cache_time_params, 0, 11) == 'cache_time=') {
    //             $cache_time = intval(substr($cache_time_params, 11));
    //             array_shift($params); //第一个参数是缓存时间不是函数用的，去除
    //         }
    //         $cache_key_params = isset($params[0])? $params[0]: null;
    //         if (is_string($cache_key_params) && substr($cache_key_params, 0, 10) == 'cache_key=') {
    //             $key = substr($cache_key_params, 10);
    //             array_shift($params); //第一个参数是缓存时间不是函数用的，去除
    //         }
    //         if (OURS_DEBUG) {
    //             echo '<!-- $cache_nodename: '.$cache_nodename.'-->'."\r\n";
    //             echo '<!-- $cache_key: '.$key.'-->'."\r\n";
    //             echo '<!-- $cache_time: '.$cache_time.'-->'."\r\n";
    //         }
    //         switch ($cache_type) {
    //             case 'memcached':

    //                 $data = Memcached::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
    //                 return $data;
    //                 break;
    //             case 'redis':
    //                 $data = Redis::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
    //                 return $data;
    //                 break;
    //             default:
    //                 $data =  call_user_func_array(array($this, $relFunc), $params);
    //                 return $data;
    //                 break;
    //         }
    //     }
    // }






}