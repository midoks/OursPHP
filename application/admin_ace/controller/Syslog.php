<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;

use \common\service\SysLogsSvc;
use \common\service\SysUserSvc;

class Syslog  extends Base {

    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '日志管理';
    }

    //列表展示
    public function index( $request, $response ) {

        $response->stitle    = '列表';

        $response->p        = $p = $request->p ? $request->p : 1;
        $pageSize           = 10;

        //字段关键字
        $response->search_type =  $searchType  = $request->search_type;
        $response->search_value = $searchValue = $request->search_value;

        $where = [];
        if(!empty($searchType) && !empty($searchValue) ){
            $where = [ "{$searchType}" => "{$searchValue}" ];
        }

        $logsSvc    = new SysLogsSvc();
        $userSvc    = new SysUserSvc();
      
        $rows   = $logsSvc->getPageData($p, $pageSize, $where);
        $total  = $logsSvc->getCount($where);

        foreach ($rows as $k => $v) {
            $t = $userSvc->get($v['uid']);
            if ($t){
                $rows[$k]['name'] = $t['username'];
            } else {
                $rows[$k]['name'] = '已经删除';
            }
        }


        $response->rows     = $rows;
        $response->total    = $total;
        $response->pageLink = $this->pageLink->getPageLink("/{$this->_controller}/{$this->_action}", $p, $pageSize, $total);

        $this->renderLayout();
    }
}