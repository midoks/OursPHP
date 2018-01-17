<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace app\controller;

use \common\service\SysFuncSvc;
use \common\service\SysRoleSvc;

class Sysrole extends Base {

    public function __construct($request, $response) {
        parent::__construct($request, $response);
        $response->title = '角色管理';
    }

    //角色列表
    public function index($request, $response) {
        $response->stitle = '列表';

        $roleSvc     = new SysRoleSvc();
        $response->p = $p = $request->p ? $request->p : 1;
        $pageSize    = 10;

        $rows  = $roleSvc->getPageData($p, $pageSize);
        $total = $roleSvc->getCount();

        $response->rows     = $rows;
        $response->pageLink = $this->pageLink->getPageLink("/{$this->_controller}/{$this->_action}?t=1", $p, $pageSize, $total, "");

        $this->renderLayout();
    }

    //添加,修改角色
    public function add($request, $response) {
        $response->stitle = '创建新角色';

        $roleSvc = new SysRoleSvc();
        $funcSvc = new SysFuncSvc();

        //编辑时
        if ($request->id) {

            $response->stitle = '编辑角色信息';

            $id   = $request->id;
            $item = $roleSvc->get($id);

            $item['list']   = explode(',', $item['list']);
            $response->item = $item;
        }

        //添加或修改数据
        if ($request->isPost()) {

            $vars         = $request->vars;
            $box          = $request->box;
            $vars['list'] = implode(',', $box);

            if (isset($id)) {
                $roleSvc->edit($id, $vars);
            } else {
                $roleSvc->add($vars);
            }

            $this->redirect('/' . $this->_controller);
        }

        $functions           = $funcSvc->getMenu();
        $response->functions = $functions;

        $this->renderLayout();
    }

    //删除角色
    public function del($request, $response) {
        $id = $request->id;
        if ($id) {
            $userSvc = new SysRoleSvc();
            $ret     = $userSvc->delete($id);
            if ($ret) {
                return $this->renderString('ok');
            }
        }
        return $this->renderString('非法操作');
    }

    //锁定或解锁角色
    public function lock($request, $response) {
        $id = $request->id;
        if ($id) {
            $dao = new SysRoleSvc();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }
}