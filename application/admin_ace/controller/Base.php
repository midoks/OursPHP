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

use \common\model\Base as BaseModel;

class Base extends Controller {

    const ADMIN_VERSION = '0.1.0';

    protected $_manager;
    protected $_rolelist = [];
    protected $_menu;

    //超级权限(方便开发及维护)
    private $super_authority = [
        'user'      => 'root',
        'password'  => 'root',
    ];


    /**
     * 后台初始化检查,是否登录
     */
    public function __construct() {

        $baseM = new BaseModel();

        //$ee = $baseM->cache('mmm', 60)->getOne('select * from system_function limit 1');
        //$ee = $baseM->cache('mmm2', 60)->getOne('select * from system_function limit 2');
        //var_dump($ee);

        $this->initTplVar();
        
        $cookie = new Cookie();
        $this->_manager = $cookie->get('info');
        if (!$this->_manager || $this->_manager['status'] == 0) {
            $this->redirect('/login');
        }


        // $response->me   = $this->_manager;
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



    protected function initTplVar(){

        $this->assign('_sys_name', 'ACE后台管理系统');
        $this->assign('_sys_version', self::ADMIN_VERSION);
        $this->assign('_sys_copyright', 'ACE后台管理系统');
    }
}