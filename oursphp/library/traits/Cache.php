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

trait Cache {


    /**
     * 清空缓存
     *
     */
    public function clear($key){

    }

    public function cache($key, $time){

        $this->__cache_key = $key;

        $mem = new \Memcached;
        $mem->addServer("127.0.0.1", 11211);

        $cacheV = $mem->get($key);
        var_dump('cache:'.$key,$cacheV);
        return $this;
    }

    /**
     *
     */
    private function cacheEnd($sql, $bind, $result){
        $mem = new \Memcached;
        $mem->addServer("127.0.0.1", 11211);

        if ($this->__cache_key){
            $cache = $mem->add($this->__cache_key, $result);
        }

        var_dump($result);
    }


    /**
     * 获取缓存
     * @param $sql string SQL语句
     * @param $bind $array 
     * @return $array
     */
    public function getCache($key){
        $this->our_cache_key    = '';
        $this->our_cache_time   = 0;
        //return false;

        $options = Config::getConfig('withcache', WEB_NAMESPACE);   


        $cache_time     = $options['cachetime'];
        $cache_type     = $options['type'];
        $cache_nodename = $options['nodename'];

        $key = $cache_nodename.'_'.$key;

        if (OURS_DEBUG) {
            echo '<!-- get $cache_nodename: '.$cache_nodename.'-->'."\r\n";
            echo '<!-- get $cache_key: '.$key.'-->'."\r\n";
            echo '<!-- get $cache_time: '.$cache_time.'-->'."\r\n";
        }

        switch ($cache_type) {
            case 'memcached':
                $mObj = Memcached::getInstance($cache_nodename);
                $data = $mObj->get($key);
                return $data;
                break;
            case 'redis':
                $rObj = Redis::getInstance($cache_nodename);
                $data = $rObj->get($key);
                $data = unserialize($data);
                return $data;
                break;
            default:return false;break;
        }
        return false;
    }

   /**
     * 设置缓存
     * @param $sql string SQL语句
     * @param $bind $array 
     * @param $data $array 保存的数据
     * @return $array
     */
    public function setCache($key, $data, $time = 30){
        $options = Config::getConfig('withcache', WEB_NAMESPACE);

        $cache_time     = $options['cachetime'];
        $cache_type     = $options['type'];
        $cache_nodename = $options['nodename'];

        $key = $cache_nodename.'_'.$key;

        if (OURS_DEBUG) {
            echo '<!-- set $cache_nodename: '.$cache_nodename.'-->'."\r\n";
            echo '<!-- set $cache_key: '.$key.'-->'."\r\n";
            echo '<!-- set $cache_time: '.$cache_time.'-->'."\r\n";
        }
        switch ($cache_type) {
            case 'memcached':
                $mObj = Memcached::getInstance($cache_nodename);
                $ret = $mObj->set($key, $cache_time, $data);
                return $ret;
                break;
            case 'redis':
                $rObj = Redis::getInstance($cache_nodename);
                $ret = $rObj->setex($key, $cache_time, serialize($data));
                return $ret;
                break;
            default:break;
        }
    }


    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws BizException
     */
    public function __call($name, $params) {

        $options = Config::getConfig('withcache', WEB_NAMESPACE);

        if (OURS_DEBUG) {
            echo '<!-- withcache: '.json_encode($options).'-->'."\r\n";
        }

        if (substr($name, -11) == '_with_cache') {

            $relFunc = substr($name, 0, strlen($name)-11);
            $key = md5($name.serialize($params));
            $cache_time_params = isset($params[0])? $params[0]:null;


            if($options == false || $options['used'] == false) {
                echo '<!-- withcache: nocache-->'."\r\n";
                return call_user_func_array(array($this, $relFunc), $params);
            }

            $cache_time = $options['cachetime'];
            $cache_type = $options['type'];
            $cache_nodename = $options['nodename'];
            if (is_string($cache_time_params) && substr($cache_time_params, 0, 11) == 'cache_time=') {
                $cache_time = intval(substr($cache_time_params, 11));
                array_shift($params); //第一个参数是缓存时间不是函数用的，去除
            }
            $cache_key_params = isset($params[0])? $params[0]: null;
            if (is_string($cache_key_params) && substr($cache_key_params, 0, 10) == 'cache_key=') {
                $key = substr($cache_key_params, 10);
                array_shift($params); //第一个参数是缓存时间不是函数用的，去除
            }
            if (OURS_DEBUG) {
                echo '<!-- $cache_nodename: '.$cache_nodename.'-->'."\r\n";
                echo '<!-- $cache_key: '.$key.'-->'."\r\n";
                echo '<!-- $cache_time: '.$cache_time.'-->'."\r\n";
            }
            switch ($cache_type) {
                case 'memcached':

                    $data = Memcached::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
                    return $data;
                    break;
                case 'redis':
                    $data = Redis::accessCache($key, $cache_time, array($this, $relFunc), $params,$cache_nodename);
                    return $data;
                    break;
                default:
                    $data =  call_user_func_array(array($this, $relFunc), $params);
                    return $data;
                    break;
            }
        }
    }






}