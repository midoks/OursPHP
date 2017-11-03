<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\SysFuncDao;
use common\dao\SysLogsDao;
use common\dao\SysRoleDao;
use common\dao\SysUserDao;

class SysSvc {


    public function getUsers($status = null) {
        $dao    = new SysUserDao();
        $where  = '';
        $query  = [];
        if($status !== null) {
            $query['status'] = $status;
            $where = "status=:status";
        }
        return $dao->findAll( $query, $where, 0, [], '' , '' , 'id asc');
    }


    public function getUser($id) {
        if($id) {
            $dao=new SysUserDao();
            return $dao->findByPkey($id);
        }
        return false;
    }

    public function addUser($item) {
        if(!empty($item)) {
            $dao = new SysUserDao();
            return $dao->add($item);
        }
        return false;
    }

    public function editUser($id,$vars) {
        if(!empty($vars)) {
            $dao = new SysUserDao();
            return $dao->edit($id,$vars);
        }
        return false;
    }


    /**
     * 获取全部角色
     * @param null $status
     * @return mixed
     */
    public function getRoles($status = null) {
        $dao    = new SysRoleDao();
        $where  = '';
        $query  = [];
        if($status !== null) {
            $query['status'] = $status;
            $where = "status=:status";
        }
        return $dao->findOne( $query, $where, 0, [], '', '', 'id asc');
    }


    /**
     * 获取用户权限信息
     * @param int $id 用户UID
     * @return array|false 
     */
    public function getRole($id) {
        if($id) {
            $dao = new SysRoleDao();
            return $dao->findByPkey($id);
        }
        return false;
    }

    public function addRole($item) {
        if(!empty($item)) {
            $dao = new SysRoleDao();
            return $dao->add($item);
        }
        return false;
    }

    public function editRole($id,$vars) {
        if(!empty($vars)) {
            $dao=new SysRoleDao();
            return $dao->edit($id,$vars);
        }
        return false;
    }


    /**
     * 获取子功能列表
     * @param $pid
     * @return mixed
     */
    public function getFuncs($pid = 0, $status = null) {
        
        $dao            = new SysFuncDao();
        $query['pid']   = $pid;
        $where          = "pid=:pid";

        if($status !== null) {
            $query['status'] = $status;
            $where = $where." and status=:status ";
        }

        $field = ['id','`name`','pid','icon','type','controller', 'action', '`desc`','is_menu','`status`'];

        return $dao->cache()->findAll($query, $where, 0 , $field,'','','id asc');
    }


    public function getFunc($id) {
        if($id) {
            $dao = new SysFuncDao();
            return $dao->findByPkey($id);
        }
        return false;
    }

    /**
     * 添加系统功能
     * @param $item
     * @return bool|mixed
     */
    public function addFunc($item) {
        if(!empty($item)) {
            $dao = new SysFuncDao();
            return $dao->add($item);
        }
        return false;
    }


    public function editFunc($id, $vars) {
        if(!empty($vars)) {
            $dao = new SysFuncDao();
            return $dao->edit($id,$vars);
        }
        return false;
    }

    /**
     * 获取所有菜单
     * @return array
     */
    public function getMenu() {
        $functions = self::getFuncs(0,1);
        if(!empty($functions)) {
            foreach ($functions as &$fun) {
                $fun['sub'] = self::getFuncs($fun['id'], 1);
            }
        }
        return $functions;
    }
}