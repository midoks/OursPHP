<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\cache\driver;

use frame\cache\Driver;
use frame\Config;

class Memcached extends Driver {
    
    protected $options = [
        [
            'host'     => '127.0.0.1',
            'port'     => 11211,
            'expire'   => 0,
            'timeout'  => 0, // 超时时间（单位：毫秒）
            'prefix'   => '',
            'username' => '', //账号
            'password' => '', //密码
            'option'   => [],
        ]
    ];

    private static $_instance = [];


    /**
     * 构造函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = []) {
        if (!extension_loaded('memcached')) {
            throw new \BadFunctionCallException('not support: memcached');
        }
        if (!empty($options)) {
            $this->options =  $options;
        }

        $this->prefix = Config::get('cache')['prefix'];

        $this->handler = new \Memcached;
        if (!empty($this->options[0]['option'])) {
            $this->handler->setOptions($this->options[0]['option']);
        }
        // 设置连接超时时间（单位：毫秒）
        if ($this->options[0]['timeout'] > 0) {
            $this->handler->setOption(\Memcached::OPT_CONNECT_TIMEOUT, $this->options[0]['timeout']);
        }
        // 建立连接
        $servers = [];
        foreach ((array) $this->options as $i => $s) {
            $servers[] = [$s['host'], (isset($s['port']) ? $s['port'] : 11211), 1];
        }
        $this->handler->addServers($servers);

        if ('' != $this->options[0]['username']) {
            $this->handler->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
            $this->handler->setSaslAuthData($this->options[0]['username'], $this->options[0]['password']);
        }
    }

    public static function getInstance( $option = 'memcached' ){
        $op = Config::get($option);

        if (!isset(self::$_instance[$option])){
            self::$_instance[$option] = new static($op);
        }

        return self::$_instance[$option];
    }

    /**
     * 判断缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name) {
        $key = $this->getCacheKey($name);
        return $this->handler->get($key) ? true : false;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = false) {
        $result = $this->handler->get($this->getCacheKey($name));
        return false !== $result ? $result : $default;
    }

    /**
     * 写入缓存
     * @access public
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
     * @return bool
     */
    public function set($name, $value, $expire = null) {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }

        $key    = $this->getCacheKey($name);
        $expire = 0 == $expire ? 0 : $_SERVER['REQUEST_TIME'] + $expire;
        if ($this->handler->set($key, $value, $expire)) {
            return true;
        }
        return false;
    }

    /**
     * 写入缓存(如何已经存在返回false)
     * @access public
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
     * @return bool
     */
    public function add($name, $value, $expire = null) {

        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
    
        $key = $this->getCacheKey($name);
        $expire = 0 == $expire ? 0 : $_SERVER['REQUEST_TIME'] + $expire;

        if ($this->handler->add($key, $value, $expire)) {
            return true;
        }
        return false;
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1) {
        $key = $this->getCacheKey($name);
        if ($this->handler->get($key)) {
            return $this->handler->increment($key, $step);
        }
        return $this->handler->set($key, $step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1) {
        $key   = $this->getCacheKey($name);
        $value = $this->handler->get($key) - $step;
        $res   = $this->handler->set($key, $value);
        if (!$res) {
            return false;
        } else {
            return $value;
        }
    }

    /**
     * 删除缓存
     * @param    string  $name 缓存变量名
     * @param bool|false $ttl
     * @return bool
     */
    public function rm($name, $ttl = false) {
        $key = $this->getCacheKey($name);
        return false === $ttl ?
        $this->handler->delete($key) :
        $this->handler->delete($key, $ttl);
    }

    /**
     * 清除缓存
     * @access public
     * @param  string  $name 缓存变量名
     * @return bool
     */
    public function clear($name) {
        $key = $this->getCacheKey($name);
        return $this->handler->delete($key);
    }
}
