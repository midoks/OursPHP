<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;


class Logs {

	const LOG    = 'log';
    const ERROR  = 'error';
    const INFO   = 'info';
    const SQL    = 'sql';
    const NOTICE = 'notice';
    const ALERT  = 'alert';
    const DEBUG  = 'debug';

	// 日志信息
    protected static $log = [];
    // 配置参数
    protected static $config = [];
    // 日志类型
    protected static $type = ['log', 'error', 'info', 'sql', 'notice', 'alert', 'debug'];
    // 日志写入驱动
    protected static $driver;

    // 当前日志授权key
    protected static $key;

	/**
     * 获取日志信息
     * @param string $type 信息类型
     * @return array
     */
    public static function getLog($type = '') {
        return $type ? self::$log[$type] : self::$log;
    }

    /**
     * 记录调试信息
     * @param mixed  $msg  调试信息
     * @param string $type 信息类型
     * @return void
     */
    public static function record($msg, $type = 'debug') {
        self::$log[$type][] = $msg;
        if (IS_CLI) {
            // 命令行下面日志写入改进
            self::save();
        }
    }

    /**
     * 清空日志信息
     * @return void
     */
    public static function clear() {
        self::$log = [];
    }

    /**
     * 保存日志
     * @return void
     */
	public static function save(){
		// var_dump(self::$log);
	}

}



?>