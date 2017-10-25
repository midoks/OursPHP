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
use frame\Response;
use frame\Config;
use frame\Route;
use frame\Exception;
use frame\exception\HttpResponseException;


class App {

    private static $app_ns = 'app';
    public static $debug    = true;

    /**
     * 构造函数
     * 
     */
    public function __construct() {}

    public static function  start(){
        $data = '';
        try {
            $config     = self::initCommon();
            $request    = Request::instance();

            //var_dump($config);
            $dispatch   = self::routeCheck($request, $config);
            //$data       = $request->dispatch($dispatch);

            $data = self::exec($dispatch, $config);
        } catch(HttpResponseException $exception){
            $data = $exception->getResponse();
        }

        echo $data;   
    }

    /**
     * 路由检测
     * @throws Exception
     */
    public static function routeCheck($request, $config){
        //var_dump($request, $config);

        $path = $request->path();
        $result = Route::parseUrl($path);
        if ($config['app_multi_module']) {
            Loader::addNamespace([ 'app'=> APP_PATH.$result['route'][0] ]);
            return ['type'      => 'module', 
                'module'        => $result['route'][0], 
                'controller'    => $result['route'][1], 
                'action'        => $result['route'][2]];
        } else {
            Loader::addNamespace([ 'app'=> APP_PATH]);
            return ['type'  => 'mvc' , 
            'controller'    => $result['route'][0], 
            'action'        => $result['route'][1]];
        }
    }

    public static function exec($dispatch, $config){

        $className = '\\'.self::$app_ns.'\\controller\\'.$dispatch['controller'];
        $instance = new $className;
        $action = $dispatch['action'];

        define('APP_CONTROLLER_CALL', $dispatch['controller']);
        define('APP_METHOD_CALL', $action);

        $ret = $instance->$action(Request::instance(), Response::instance());

        return $ret;
    }



    /**
     * 初始化应用
     */
    public static function initCommon(){
        $app_config = APP_PATH.'config/config'.EXT;
        $config = Config::merge(include $app_config);

        $debug =  Config::get('app_debug');
        if ($debug) {
            ini_set('display_errors', 'On');
        }

        //设置时区
        date_default_timezone_set($config['default_timezone']);

        //设置公共匿名空间
        $common_ns = Config::get('common');
        if($common_ns){
            Loader::addNamespace([ 'common'=> $common_ns]);
        }
        

        return $config;
    }
}
