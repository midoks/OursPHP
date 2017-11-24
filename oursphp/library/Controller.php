<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;

use frame\View;

class Controller {

    private $_sys_layout = 'layout/index';
    private static $_instance = NULL;

    /**
     * 构造函数
     */
	public function __construct() {}


    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
     * 设置默认的布局器
     * @param string $layout
     */
	protected function setLayout($layout){
        $this->_sys_layout = $layout;
    }

    /**
     * 模版变量传递方法
     * @param string $name 变量名
     * @param mixed  $value 变量值
     */
    protected function assign($name, $value){
        $view = View::getInstance();
        $view->assign($name, $value);
    }
    
	/**
     * 输出JSON格式字符
     * @param array $data 数组数组
     * @return void
     */
	protected function renderJson($data) {
        header('Content-type: application/json');
        echo json_encode($data);exit;
    }

    /**
     * 返回JSONP返回格式
     * @param string JSONP方法名
     * @param string|array 数据
     * @return void
     */
    protected function renderJsonP($callName, $data){
        $ret = '';
        if (is_string($data)){
            $ret = $data;
        } else {
            $ret = json_encode($data);
        }
        echo $callName .'('.$ret.');';
    }

	/**
     * 输出字符串
     * @param string $string
     * @return void
     */
	protected function renderString($string) {
        echo $string;
    }

    /**
     * 渲染smarty模版文件
     * @param string $file 指定的模版文件
     */
    protected function render($view = NULL) {
        $tplFile = self::parsePath($view);
        $view = View::getInstance();
        
        $view->render(strtolower($tplFile));
    }

	/**
     * 渲染smarty模版布局文件
     * @param string $view 指定的模版文件
     * @param string $tplVarName 模版变量名称
     */
	protected function renderLayout($view = NULL, $tplVarName = '_layout_content') {

        $tplFile = self::parsePath($view);

        $view = View::getInstance();
        $tplConf = Config::get('template');
        $tplFile .= '.'.$tplConf['view_suffix'];

        $view->assign($tplVarName, strtolower($tplFile));
        $view->layout($this->_sys_layout);
    }

    /**
     * 解析模版
     * @param string $view 模版名称
     * @return string | false
     */
    private static function parsePath($view = NULL){

        $tplFile = '';
        if( !$view ){
            $tplFile = APP_CONTROLLER_CALL.DS.APP_METHOD_CALL;
        } else {
            $view = str_ireplace(array('.','/','|','>','>>','@'), DS, $view);
            $tplFile = trim($view, DS);
        }

        $list = explode(DS, $tplFile);
        $listNum = count($list);

        if ($listNum == 1) {
            $tplFile = APP_CONTROLLER_CALL.DS.$tplFile;

            $multiModule = Config::get('app_multi_module');
            if ($multiModule) {
                $tplFile = APP_MODULE_CALL.DS.$tplFile;
            }
        } else if ($listNum == 2){
            $multiModule = Config::get('app_multi_module');
            if ($multiModule) {
                $tplFile = APP_MODULE_CALL.DS.$tplFile;
            }
        }
        
        return $tplFile;
    }

    /**
     * 解析URL
     * @param string $url 模版名称
     * @return string | false
     */
    private static function parseUrl($url = NULL){

        $resUrl = '';
        if( !$url ){
            $resUrl = APP_CONTROLLER_CALL.'/'.APP_METHOD_CALL;
        } else {
            $url = str_ireplace(array('.','/','|','>','>>','@'), '/', $url);
            $resUrl = trim($url, '/');
        }

        $list = explode('/', $resUrl);
        $listNum = count($list);

        if ($listNum == 1) {
            $resUrl = APP_CONTROLLER_CALL.'/'.$resUrl;

            $multiModule = Config::get('app_multi_module');
            if ($multiModule) {
                $resUrl = APP_MODULE_CALL.'/'.$resUrl;
            }
        } else if ($listNum == 2){
            $multiModule = Config::get('app_multi_module');
            if ($multiModule) {
                $resUrl = APP_MODULE_CALL.'/'.$resUrl;
            }
        }
        
        return $resUrl;
    }


    /**
     * 跳转 $url
     * @param string $url HTTP地址
     * @return void
     */
    public function redirect($url) {
        if ($url == '/'){
            header("Location: {$url}");exit;
        } else {
            $url = self::parseUrl($url);
            $urlSuffix = Config::get('url_html_suffix');
            if (!empty($urlSuffix)){
                $url .= '.'.$urlSuffix;
            }
            header("Location: /{$url}");exit;
        }
        
    }

    /**
     * 返回上一页
     * @return void
     */
    public function toBack() {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
