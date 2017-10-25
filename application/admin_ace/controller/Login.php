<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace  app\controller;

use frame\Controller;
use frame\utils\Image;

class Login extends Controller {
    
	//登录
    public function index($request, $response) {
    	var_dump($_POST);
        return $this->render();
    }

    //登录
    public function index2($request, $response) {
    	var_dump($_POST);
        return $this->render();
    }

    //登出
    public function loginOut($request, $response) {
        return $this->renderLayout();
    }


    //生成码
    public function captcha($request, $response){
    	Image::captcha();
    }
	
}