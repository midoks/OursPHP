<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

//ace:http://ace.jeka.by/

namespace app\controller;

use \common\service\SysFuncSvc;
use \common\service\SysLogsSvc;
use \common\service\SysRoleSvc;
use \frame\Controller;
use \frame\utils\PageLink;

class Base extends Controller {

    const ADMIN_VERSION = '0.1.0';

    protected $_user = []; //用户信息
    protected $_role = []; //规则信息
    protected $_menu = []; //菜单信息

    private $_logsObj = NULL; //日志对象

    //超级权限(方便开发及维护)
    private $super_authority = [
        'user'     => 'root',
        'password' => 'root',
    ];

    /**
     * 后台初始化检查,是否登录
     */
    public function __construct($request, $response) {

        $this->_logsObj = new SysLogsSvc();

        $response->me = $this->_user = cookie('info');

        if (!$this->_user || $this->_user['status'] == 0) {
            $this->redirect('/login/index');
        }

        $this->_controller = $_controller = $response->_controller = $request->controller();
        $this->_action     = $_action     = $response->_action     = $request->action();

        $this->initTplVar();

        $funcSvc = new SysFuncSvc();
        $roleSvc = new SysRoleSvc();

        $this->_menu = $_menu = $funcSvc->getMenu_with_cache('key=admin_func_list&time=1000'); //ok
        // $this->_menu = $_menu = $funcSvc->getMenu();

        $roleid = $this->_user['roleid'];
        $_role  = $roleSvc->get($roleid);

        $_menu = $this->checkAuth($_menu, $_role);
        if (!$_menu) {

            //ajax请求特殊处理
            $reqWith = $request->header('X-Requested-With');
            if ('XMLHttpRequest' == $reqWith || !empty($_FILES)) {
                echo "无权限使用!";
                exit;
            }

            $response->title  = "权限验证";
            $response->stitle = "未授权";
            $this->renderLayout('layout/nopower');
            exit;
        }

        //子菜单是否在父菜单内,父菜单便于打开
        foreach ($_menu as $key => $value) {
            $sub                      = $value['sub'];
            $_menu[$key]['open_menu'] = false;
            foreach ($sub as $subKey => $subVal) {
                if ($subVal['controller'] == $_controller && $subVal['action'] == $_action) {
                    $_menu[$key]['open_menu'] = true;
                }
            }
        }

        $response->menulist = $_menu;

        $this->pageLink = PageLink::getInstance();
    }

    //权限检查
    private function checkAuth($menu, $role) {

        //获取用户所有权限
        $role['list'] = explode(',', $role['list']);
        $roleMenu     = [];

        foreach ($menu as $key => $value) {
            $sub  = $value['sub'];
            $_sub = array();
            foreach ($sub as $subKey => $subVal) {
                if (in_array($subVal['id'], $role['list'])) {
                    $_sub[] = $subVal;
                }
            }

            if (!empty($_sub)) {
                $value['sub']   = $_sub;
                $roleMenu[$key] = $value;
            }
        }

        if (empty($roleMenu)) {
            return false;
        }

        //判断当前操作是否有权限
        $_hasAuth = false;
        foreach ($roleMenu as $kr => $vr) {
            $sub = $vr['sub'];
            foreach ($sub as $subKey => $subVal) {
                if ($subVal['controller'] == $this->_controller
                    && $subVal['action'] == $this->_action) {
                    $_hasAuth = true;
                }
            }
        }

        //return($roleMenu);
        //var_dump($this->_controller,$this->_action);
        if ($_hasAuth ||
            (strtolower($this->_controller) == 'index' && strtolower($this->_action) == 'index')) {
            return $roleMenu;
        }
        return false;
    }

    //初始化基本变量
    private function initTplVar() {

        $this->assign('_sys_name', 'ACE后台管理系统');
        $this->assign('_sys_version', self::ADMIN_VERSION);
        $this->assign('_sys_copyright', 'Copyright 2017 - ∞ oursphp. All Rights Reserved');
    }

    //系统操作日志
    protected function sysLog($msg) {
        $data = [
            'uid'      => $this->_user['id'],
            'type'     => '1',
            'msg'      => $msg,
            'add_time' => time(),
        ];
        $this->_logsObj->add($data);
    }

}