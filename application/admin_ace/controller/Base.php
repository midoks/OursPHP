<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


//ace:http://ace.jeka.by/

namespace  app\controller;

use \frame\Controller;
use \frame\utils\Cookie;
use \frame\utils\PageLink;

use \common\service\SysFuncSvc;
use \common\service\SysRoleSvc;

class Base extends Controller {

    const ADMIN_VERSION = '0.1.0';

    protected $_user;
    protected $_role = [];
    protected $_menu;

    //超级权限(方便开发及维护)
    private $super_authority = [
        'user'      => 'root',
        'password'  => 'root',
    ];

    /**
     * 后台初始化检查,是否登录
     */
    public function __construct($request, $response) {
        
        $this->_controller = $_controller = $response->_controller = $request->controller();
        $this->_action = $_action = $response->_action = $request->action();

        $this->initTplVar();
        
        $cookie     = Cookie::getInstance();
        $response->me = $this->_user = $cookie->get('info');
        if (!$this->_user || $this->_user['status'] == 0) {
            $this->redirect('/login');
        }

        $roleid     = $this->_user['roleid'];
        $funcSvc    = new SysFuncSvc();
        $roleSvc    = new SysRoleSvc();

        $_menu = $funcSvc->getMenu();
        $_role = $roleSvc->get($roleid);

        //var_dump($_menu, $_role);

        $_menu = $this->checkAuth($_menu, $_role);
        if(!$_menu){

            $response->title    = "权限验证";
            $response->stitle   = "未授权";
            $this->renderLayout('layout/nopower');
            exit;
        }


        //子菜单是否在父菜单内,父菜单便于打开
        foreach ($_menu as $key => $value) {
            $sub = $value['sub'];
            $_menu[$key]['open_menu'] = false;
            foreach ($sub as $subKey => $subVal) {
                if ($subVal['controller'] == $_controller && $subVal['action'] == $_action){
                    $_menu[$key]['open_menu'] = true;
                }
            }
        }

        $response->menulist = $_menu;

        $this->pageLink = PageLink::getInstance();
    }

    //权限检查
    private function checkAuth($menu, $role){
        var_dump($menu, $role);

        return false;
    }

    //日志
    protected function log($msg, $type = ''){

    }

    //初始化基本变量
    protected function initTplVar(){

        $this->assign('_sys_name', 'ACE后台管理系统');
        $this->assign('_sys_version', self::ADMIN_VERSION);
        $this->assign('_sys_copyright', 'ACE后台管理系统');
    }

    
}