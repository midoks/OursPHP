<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;


use \common\service\SysUserSvc;
use \common\service\SysRoleSvc;

class SysUser  extends Base {

    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '管理员管理';
    }

    //列表展示
    public function index( $request, $response ) {

        $response->stitle = '列表';

        $userSvc    = new SysUserSvc();
        $roleSvc    = new SysRoleSvc();

        $response->p        = $p = $request->p ? $request->p : 1;
        $pageSize           = 10;

        $rows   = $userSvc->getPageData($p, $pageSize);
        $total  = $userSvc->getCount();

        foreach ($rows as &$row) {
            $row['rolename'] = $roleSvc->get($row['roleid'])['name'];
        }

        $response->rows = $rows;
        $response->pageLink = $this->pageLink->getPageLink("/{$this->_controller}/{$this->_action}?t=1", $p, $pageSize, $total,"");

        $this->renderLayout();
    }

    //添加修改用户信息
    public function add( $request, $response ) {
        $response->stitle = '新增';

        $userSvc    = new SysUserSvc();
        $roleSvc    = new SysRoleSvc();

        if($request->id) {
            $response->stitle = '编辑管理员信息';
            $id     = $request->id;
            $item   = $userSvc->get($id);

            if(!$item) {
                $this->redirect('/'.$this->_controller);
            }
            $response->user = $item;
        }

        if( $request->isPost() ){
            $vars   = $request->vars;

            if(empty($vars['password'])) {
                unset($vars['password']);
            } else {
                $vars['password'] = md5($vars['password']);
            }

            if(isset($id)) {
                $userSvc->edit($id,$vars);
            } else {
                $userSvc->add($vars);
            }

            $this->redirect('/'.$this->_controller);
        }

        $roles = $roleSvc->gets(1);
        $response->roles = $roles;

        $this->renderLayout();
    }

    //修改密码
    public function repwd($request, $response){

        $response->stitle = '信息修改';
        $id = $this->_user['id'];

        if( $request->isPost() ){
            $vars   = $request->vars;

            if(empty($vars['password'])) {
                unset($vars['password']);
            } else {
                $vars['password'] = md5($vars['password']);
            }

            $userSvc    = new SysUserSvc();

            if(isset($id)) {
                $userSvc->edit($id,$vars);
            }
            $this->redirect('/');
        }

        $this->renderLayout();
    }


    //删除系统用户
    public function del($request, $response) {
        $id = $request->id;
        if( $id ) {
            $userSvc  = new SysUserSvc();
            $ret = $userSvc->delete($id);
            if ($ret){
                return $this->renderString('ok');
            }
        }
        return $this->renderString('非法操作');
    }

    //锁定或解锁用户
    public function lock($request,$response) {
        $id = $request->id;
        if($id) {
            $dao = new SysUserSvc();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }
}