<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;


use common\dao\SysFunc;
use common\dao\SysLogs;
use common\dao\SysUser;

use \common\service\SysSvc;

class SysRole  extends Base {

    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '角色管理';
    }

    //角色列表
    public function index($request, $response){
        $response->stitle = '列表';

        if(!isset($svc)) {
            $svc = new SysSvc();
        }
        $rows = $svc->getRoles();
        $_rows[] = $rows;

        $response->stitle   = '角色管理';
        $response->rows     = $_rows;

        $this->renderLayout();
    }

    //添加,修改角色
    public function add($request,$response) {
        $svc    = new SysSvc();

        $response->stitle = '创建新角色';

        if($request->id) {

            $response->stitle   = '编辑角色信息';
            $id     = $request->id;
            
            $item   = $svc->getRole($id);

            $item['list'] = explode(',', $item['list']);
            $response->item = $item;
        }

        if($request->isPost()) {

            $vars       = $request->vars;
            $funarray   = [];
            $svc        = new SysSvc();
            $functions = $svc->getFunctions();
            if(!empty($functions)) {
                foreach ($functions as $fun) {
                    $powers = $request->$fun['uri'];
                    if(!empty($powers)) {
                        if(is_string($powers)) {
                            $powers = [$powers];
                        }
                        $funarray[strtolower($fun['uri'])] = $powers;
                    }

                }
            }
            $vars['functionlist'] = json_encode($funarray);
            //dump($vars);exit;
            if(isset($id)) {
                $svc->editRole($id,$vars);
            } else {
                $svc->addRole($vars);
            }

            $this->redirect('/sysrole');
        }

        $rows       = $svc->getRoles();
        $functions  = $svc->getMenu();

        $rows['list'] = explode(',', $rows['list']);
        $response->functions    = $functions;
        $response->rows         = $rows;

        $this->renderLayout();
    }
}