<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\dao;

class SysUserDao extends Base {

    public function getTableName(){
        return 'sys_user';
    }

    public function getPKey(){
        return 'id';
    }

    
}