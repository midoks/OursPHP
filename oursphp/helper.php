<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

//------------------------
// OURSPHP 助手函数
//-------------------------
use frame\Cookie;
use frame\Session;

if (!function_exists('session')) {
    /**
     * Session管理
     * @param string|array  $name session名称，如果为数组表示进行session设置
     * @param mixed         $value session值
     * @param string        $prefix 前缀
     * @return mixed
     */
    function session($name, $value = '', $prefix = null) {
        if (is_array($name)) {
            // 初始化
            Session::init($name);
        } elseif (is_null($name)) {
            // 清除
            Session::clear('' === $value ? null : $value);
        } elseif ('' === $value) {
            // 判断或获取
            return 0 === strpos($name, '?') ? Session::has(substr($name, 1), $prefix) : Session::get($name, $prefix);
        } elseif (is_null($value)) {
            // 删除
            return Session::delete($name, $prefix);
        } else {
            // 设置
            return Session::set($name, $value, $prefix);
        }
    }
}

if (!function_exists('cookie')) {
    /**
     * Cookie管理
     * @param string|array  $name cookie名称，如果为数组表示进行cookie设置
     * @param mixed         $value cookie值
     * @param mixed         $option 参数
     * @return mixed
     */
    function cookie($name, $value = '', $option = null) {
        if (is_array($name)) {
            // 初始化
            Cookie::init($name);
        } elseif (is_null($name)) {
            // 清除
            Cookie::clear($value);
        } elseif ('' === $value) {
            // 获取
            return 0 === strpos($name, '?') ? Cookie::has(substr($name, 1), $option) : Cookie::get($name, $option);
        } elseif (is_null($value)) {
            // 删除
            return Cookie::delete($name);
        } else {
            // 设置
            return Cookie::set($name, $value, $option);
        }
    }
}
?>