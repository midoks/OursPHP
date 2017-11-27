<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace  app\controller;


class Tpl extends Base {

	public function __construct($request, $response){
		$response->title = '后台模版事例';
		parent::__construct($request, $response);
	}
    	
    //展示
	public function index($request, $response) {
		$response->stitle = '异步上传';
		return $this->renderLayout();
    }

    

	
}