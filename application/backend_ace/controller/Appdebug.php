<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;

use \common\service\AppDebugSvc;
use \common\dao\AppDebugDao;

class Appdebug  extends Base {

    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '调式管理';
    }

    //列表展示
    public function index( $request, $response ) {


        $response->stitle = '列表';

        $debugSvc    = new AppDebugSvc();

        $debugDao = new AppDebugDao();
        $response->log_type = $debugDao->logType;

        // $debugSvc->write( json_encode($_COOKIE) );

        $response->p        = $p = $request->p ? $request->p : 1;
        $pageSize           = 10;

        $rows   = $debugSvc->getPageData($p, $pageSize);
        $total  = $debugSvc->getCount();

        $response->rows = $rows;
        $response->pageLink = $this->pageLink->getPageLink("/{$this->_controller}/{$this->_action}?t=1", $p, $pageSize, $total,"");

        $this->renderLayout();
    }

}