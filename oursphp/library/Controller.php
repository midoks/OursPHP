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

    private  $_sys_layout = 'layout/index';

    /**
     * 构造函数
     */
	public function __construct() {}

	/**
     * 设置默认的布局器
     * @param string $layout
     */
	protected function setLayout($layout){
        $this->_sys_layout = $layout;
    }
    
	/**
     * 输出JSON格式字符
     * @param array $data 数组数组
     * @return void
     */
	public function renderJson($data) {
        header('Content-type: application/json');
        echo json_encode($data);exit;
    }

    /**
     * 返回JSONP返回格式
     * @param string JSONP方法名
     * @param string|array 数据
     * @return void
     */
    public function renderJsonP($callName, $data){
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
	public function renderString($string) {
        echo $string;
    }

	/**
     * 渲染smarty模版文件
     * @param string $file 指定的模版文件
     */
	public function render($tpl_file = NULL) {
        
        $template_file = APP_CONTROLLER_CALL .DS. APP_METHOD_CALL;
        $view = View::getInstance();

        $view->render(strtolower($template_file));
    }

    /**
     * 模版变量传递方法
     * @param string $name 变量名
     * @param mixed  $value 变量值
     */
    public function assign($name, $value){
        $view = View::getInstance();
        $view->assign($name, $value);
    }

	/**
     * 渲染smarty模版布局文件
     * @param string $tpl_file 指定的布局文件
     */
	public function renderLayout($tpl_file = NULL) {

        $template_file = APP_CONTROLLER_CALL .DS. APP_METHOD_CALL;

        $view = View::getInstance();
        $template = Config::get('template');
        $template_file .= '.'.$template['view_suffix'];

        $view->assign('_layout_content',$template_file);
        $view->layout($this->_sys_layout);
    }


    /**
     * 跳转 $url
     * @param string $url HTTP地址
     * @return void
     */
    public function redirect($url) {
        header("Location: {$url}");
    }

    /**
     * 返回上一页
     * @return void
     */
    public function toBack() {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
