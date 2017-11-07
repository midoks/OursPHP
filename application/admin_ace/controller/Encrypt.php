<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace  app\controller;

use frame\utils\Authcode;

class Encrypt extends Base {

	public function __construct($request, $response){
		$response->title = '加密相关';
		parent::__construct($request, $response);
	}
    	
    //展示
	public function index($request, $response) {
		$response->stitle = 'authcode';

		if ($request->isPost()){
			
			$response->str = $str = $request->str;
			$submit_decode = $request->submit_decode;

			if ($submit_decode){
				$response->code = $code = Authcode::decode($str);
			} else {
				$response->code = $code = Authcode::encode($str);
			}
		}		

		return $this->renderLayout();
    }

    

	
}