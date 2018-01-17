<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace app\controller;

class Monitor extends Base {

    public function __construct($request, $response) {
        $response->title = '资源监控';
        parent::__construct($request, $response);
    }

    //展示
    public function index($request, $response) {
        $response->stitle = '异步上传';
        return $this->renderLayout();
    }

}