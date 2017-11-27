<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\dao;

class AppDebugDao extends Base {

	//日志类型定义
	public $logType = array(
		0 => '调试',
		1 => '退款',
		2 => '排除'
	);

    public function getTableName(){
        return 'app_debugs';
    }

    public function getPKey(){
        return 'id';
    }
}