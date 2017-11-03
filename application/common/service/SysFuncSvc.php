<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\SysFunc;

class SysFuncSvc {


    /**
     * 获取子功能列表
     * @param $pid
     * @return mixed
     */
    public function getFuncs($pid = 0, $status = null) {
        
        $dao            = new SysFunc();
        $query['pid']   = $pid;
        $where          = "pid=:pid";

        if($status !== null) {
            $query['status'] = $status;
            $where = $where." and status=:status ";
        }

        $field = ['id','`name`','pid','icon','type','controller', 'action', '`desc`','is_menu','`status`'];

        return $dao->cache()->findAll($query, $where,0, $field,'','','id asc');
    }


    public function get($id) {
        if($id) {
            $dao = new SysFunc();
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
            $dao = new SysFunc();
            return $dao->add($item);
        }
        return false;
    }


    public function edit($id, $vars) {
        if(!empty($vars)) {
            $dao = new SysFunc();
            return $dao->edit($id,$vars);
        }
        return false;
    }
}