<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace frame;

use frame\Config;
use \Smarty;

class View {

    public static $_instance = NULL;
    private static $_smarty;
    private $template = NULL;

    private function __construct() {

        $cache_dir  = Config::get('runtime_cache');
        $template   = $this->template =  Config::get('template');
        $app_id     = Config::get('app_id');

        //todo compiler_dir config
        
        self::$_smarty                  = self::getSmarty();
        self::$_smarty->left_delimiter  = $template['tpl_begin'];   //'{{'
        self::$_smarty->right_delimiter = $template['tpl_end'];     //'}}'
        self::$_smarty->template_dir    = APP_PATH.'view'.DS;
        self::$_smarty->cache_dir       = $cache_dir."cache";
        self::$_smarty->compile_dir     = $cache_dir.$app_id;
        self::$_smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;

        if(!file_exists($cache_dir)) {
            mkdir($cache_dir, 0755);
        }
    }

    /**
     * 获取View单例
     */
    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 取得当前的smary
     * @return \Smarty
     */
    public static function getSmarty() {
        if (!self::$_smarty) {
            include FRAME_PATH.'view/smarty/Smarty.class.php';
            self::$_smarty = new Smarty();
        }
        return self::$_smarty;
    }

    /**
     * smary assign
     * @param string $varname
     * @param array $var
     */
    public function assign($varname, $var)	{
        self::$_smarty->assign($varname,$var);
    }

    /**
     * 渲染smarty模版文件
     * @param string $file 指定的模版文件
     */
    public function render($file) {
        $tpl_file = $file.'.'.$this->template['view_suffix'];
        //要用ob_start不能用display
        //self::$_smarty->display($file);
        echo self::$_smarty->fetch($tpl_file);
    }

    /**
     * 渲染smarty模版布局文件
     * @param string $action_file 指定的布局文件
     */
    public function layout($file) {
        $layout_template = $file.'.'.$this->template['view_suffix'];
        //要用ob_start不能用display
        //self::$_smarty->display($layout_template);
        echo self::$_smarty->fetch($layout_template);
    }
}
