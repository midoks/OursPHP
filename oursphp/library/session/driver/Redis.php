<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace frame\session\driver;

use SessionHandler;
use frame\Exception;

use frame\cache\driver\Redis as RedisCache;


class Redis extends SessionHandler
{
    /** @var \Redis */
    protected $handler = null;
    protected $config  = [
        'expire'       => 3600, // 有效期(秒)
        'session_name' => '', // sessionkey前缀
    ];

    public function __construct($config = []) {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed  $sessName
     * @return bool
     * @throws Exception
     */
    public function open($savePath, $sessName) {

        $this->handler = RedisCache::getInstance()->handler();

        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handler->close();
        $this->handler = null;
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     * @return string
     */
    public function read($sessID) {
        $key = $this->config['session_name'] . $sessID;
        return (string) $this->handler->get($key);
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     * @return bool
     */
    public function write($sessID, $sessData) {
        if ($this->config['expire'] > 0) {
            return $this->handler->setex($this->config['session_name'] . $sessID, $this->config['expire'], $sessData);
        } else {
            return $this->handler->set($this->config['session_name'] . $sessID, $sessData);
        }
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     * @return bool
     */
    public function destroy($sessID) {
        return $this->handler->delete($this->config['session_name'] . $sessID) > 0;
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     * @return bool
     */
    public function gc($sessMaxLifeTime) {
        return true;
    }
}
