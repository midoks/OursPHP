<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace  app\controller;

class Index extends Base {
    	
    //控制台页面
	public function index($request, $response) {
		
		$response->title = '控制台';
		$response->stitle = '首页';
		return $this->renderLayout();
    }
}