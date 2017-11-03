<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace common\dao;

class SysRoleDao extends Base {
    public function getTableName(){
        return 'sys_role';
    }

    public function getPKey(){
        return 'id';
    }

    public function lock($id) {
        $fun = self::findByPkey($id);
        if($fun) {
            $vars['status']=$fun['status']==1?0:1;
            //dump($vars['status'],$fun['status']);
            return self::edit($id,$vars);
        }
        return false;
    }
}