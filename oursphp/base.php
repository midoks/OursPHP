<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

//调式模式
defined('OURS_DEBUG') or define('OURS_DEBUG', false);

//常规值
define('FRAME_START_TIME', microtime(true));
define('OURS_VERSION', '3.0.0');
define('FRAME_START_MEM', memory_get_usage());
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);

// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

//框架密钥,cookie等涉及到安全的东西都使用这个key一旦设置切勿更改,验证码等需要从中取值
define('OURS_SECUREKEY','dfkasldkfjslakdfjeiwhfiwwioi23432kladf');
define('OURS_CHARSET','abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789');

//目录设置
defined('OURS_PATH')	or define('OURS_PATH', __DIR__.DS);
defined('FRAME_PATH') 	or define('FRAME_PATH',OURS_PATH.'library'.DS);
defined('VENDOR_PATH') 	or define('VENDOR_PATH', dirname(OURS_PATH).DS.'vendor'.DS);

defined('ROOT_PATH')    or define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DS);
defined('APP_PATH')		or define('APP_PATH', dirname(ROOT_PATH).DS);

require FRAME_PATH . 'Loader.php';
// 注册自动加载
\frame\Loader::register();

// 注册错误和异常处理机制
\frame\Error::register();

// 加载默认配置文件
\frame\Config::set(include OURS_PATH . 'config' . EXT);

?>