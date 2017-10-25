<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;

use frame\Request;

class Route {


    /**
     * 解析模块的URL地址 [模块/控制器/操作?]参数1=值1&参数2=值2...
     * @access public
     * @param string    $url URL地址
     * @param string    $depr URL分隔符
     * @return array
     */
    public static function parseUrl($url, $depr = '/'){

        $url              = str_replace($depr, '|', $url);
        list($path, $var) = self::parseUrlPath($url);

        $route            = [null, null, null];

        if ( Config::get('app_multi_module') ){

            $module = array_shift($path);
            $module = !empty($module) ? $module : Config::get('module_value');

            $controller = array_shift($path);
            $controller = !empty($controller) ? $controller : Config::get('controller_value');

            $action = array_shift($path);
            $action = !empty($action) ? $action : Config::get('action_value');
 
            $route = [strtolower($module), ucfirst($controller), strtolower($action)];
            return ['type' => 'module', 'route' => $route];
        } else {

            $controller = array_shift($path);
            $controller = !empty($controller) ? $controller : Config::get('controller_value');

            $action = array_shift($path);
            $action = !empty($action) ? $action : Config::get('action_value');

            $route = [ucfirst($controller), strtolower($action)];
            return ['type' => 'mvc', 'route' => $route];
        }        
    }

    /**
     * 解析URL的pathinfo参数和变量
     * @access private
     * @param string    $url URL地址
     * @return array
     */
    private static function parseUrlPath($url) {

        // 分隔符替换 确保路由定义使用统一的分隔符
        $url = str_replace('|', '/', $url);
        $url = trim($url, '/');
        $var = [];


        if (false !== strpos($url, '?')) {
            // [模块/控制器/操作?]参数1=值1&参数2=值2...
            $info = parse_url($url);
            $path = explode('/', $info['path']);
            parse_str($info['query'], $var);
        } elseif (strpos($url, '/')) {
            // [模块/控制器/操作]
            $path = explode('/', $url);
        } else {
            $path = [$url];
        }

        return [$path, $var];
    }

}