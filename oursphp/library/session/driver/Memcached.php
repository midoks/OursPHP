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

use \frame\cache\driver\Memcache as Memd;

class Memcached extends SessionHandler {
    protected $handler = null;
    protected $config  = [
        'expire'       => 3600, // session有效期
        'session_name' => '', // memcache key前缀
    ];

    public function __construct($config = []) {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 打开Session
     * @access public
     * @param string    $savePath
     * @param mixed     $sessName
     */
    public function open($savePath, $sessName) {
        $this->handler = Memd::getInstance()->handler();
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close() {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handler->quit();
        $this->handler = null;
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
    public function read($sessID) {
        return (string) $this->handler->get($this->config['session_name'] . $sessID);
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     * @return bool
     */
    public function write($sessID, $sessData) {
        return $this->handler->set($this->config['session_name'] . $sessID, $sessData, $this->config['expire']);
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     * @return bool
     */
    public function destroy($sessID) {
        return $this->handler->delete($this->config['session_name'] . $sessID);
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     * @return true
     */
    public function gc($sessMaxLifeTime) {
        return true;
    }
}
