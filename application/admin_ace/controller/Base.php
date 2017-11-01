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

//use \common\model\Base as BaseModel;

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

        $this->initTplVar();
        
        $cookie     = Cookie::getInstance();
        $this->_user = $cookie->get('info');
        if (!$this->_user || $this->_user['status'] == 0) {
            $this->redirect('/login');
        }

        //var_dump($this->_user);


        $response->me   = $this->_user;
        // $roleid         = $this->_manager['roleid'];
        // $svc            = new SystemSvc();
        // $response->menulist = $this->_menu = $svc->getFunctionMap();

        // $role = $svc->getRole($roleid);

        // //var_dump($role);

        // $this->_rolelist    = ( $role['status']==1 ) ? json_decode($role['functionlist'],true) : [];
        // $response->rolelist = $this->_rolelist;

        // if(!array_key_exists(strtolower($this->_controller), $this->_rolelist) 
        //     || !in_array(strtolower($this->_action), $this->_rolelist[strtolower($this->_controller)])) {

        //     $response->title    = "权限验证";
        //     $response->stitle   = "未授权";
        //     $this->layoutSmarty('../layout/nopower');
        //     exit;
        // }
    }

    //初始化基本变量
    protected function initTplVar(){

        $this->assign('_sys_name', 'ACE后台管理系统');
        $this->assign('_sys_version', self::ADMIN_VERSION);
        $this->assign('_sys_copyright', 'ACE后台管理系统');
    }

    //获取系统方法
    public function getSysFunc(){

    }
}