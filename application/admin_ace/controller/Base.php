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

use \common\service\SysSvc;

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
        
        $response->_controller = $request->controller();
        $response->_action = $request->action();

        $this->initTplVar();
        
        $cookie     = Cookie::getInstance();
        $response->me = $this->_user = $cookie->get('info');
        if (!$this->_user || $this->_user['status'] == 0) {
            $this->redirect('/login');
        }

        $roleid         = $this->_user['roleid'];
        $svc            = new SysSvc();

        $response->menulist = $this->_menu = $svc->getMenu();
        $role = $svc->getRole($roleid);

        // $this->_rolelist    = ( $role['status']==1 ) ? json_decode($role['functionlist'],true) : [];
        // $response->rolelist = $this->_rolelist;

        // if(!array_key_exists(strtolower($this->_controller), $this->_rolelist) 
        //     || !in_array(strtolower($this->_action), $this->_rolelist[strtolower($this->_controller)])) {

        //     $response->title    = "权限验证";
        //     $response->stitle   = "未授权";
        //     $this->layoutSmarty('../layout/nopower');
        //     exit;
        // }

        $this->pageLink = PageLink::getInstance();
    }

    //获取系统方法
    public function getSysFunc(){

    }

    //初始化基本变量
    protected function initTplVar(){

        $this->assign('_sys_name', 'ACE后台管理系统');
        $this->assign('_sys_version', self::ADMIN_VERSION);
        $this->assign('_sys_copyright', 'ACE后台管理系统');
    }

    
}