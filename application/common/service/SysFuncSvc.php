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

class SysFuncSvc {

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

        return $dao->findAll($query, $where,0, $field,'','','id asc');
    }


    public function get($id) {
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
    public function add($item) {
        if(!empty($item)) {
            $dao = new SysFuncDao();
            return $dao->add($item);
        }
        return false;
    }


    public function edit($id, $vars) {
        if(!empty($vars)) {
            $dao = new SysFuncDao();
            return $dao->edit($id,$vars);
        }
        return false;
    }
}