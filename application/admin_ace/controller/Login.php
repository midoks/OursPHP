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
use frame\utils\Cookie;

use common\dao\SystemManager;

class Login extends Controller {
    
	//登录
    public function index($request, $response) {
    	//var_dump($_POST);
        $dao        = new SystemManager();
        $dao->startTrans();
        $cookie     = Cookie::getInstance();

        if ($request->isPost()){

            $vars = $request->post('vars');
            $code = $cookie->get('admin_captcha');

            if (strtolower($vars['code']) == strtolower($code)){

                var_dump($vars,$code);
                var_dump($_POST);
                $name = $request->name;
                var_dump($name);
           
                $query['username'] = $name;
                $where  = ' username=:username and status=1';
                $user   = $dao->cache('cache_t',30)->findOne($query,$where);
                var_dump($user);exit;
                if( $user && md5($password) === $user['password']) {
                    $cookie->set('info', $user, 0);
                    //return $user;
                }
            }
        }

        return $this->render();
    }

    public function t(){
        $dao        = new SystemManager();
        $dao->startTrans();

        $query['username'] = 'admin';
        $where  = ' username=:username and status=1';
        $user   = $dao->cache('cache_t',30)->findOne($query,$where);

    }

    //登出
    public function loginOut($request, $response) {
    }


    //生成验证码
    public function captcha($request, $response){
    	$im        = new Image();
        $cookie    = Cookie::getInstance();

        $im->captcha();
        $char = $im->getChar();

        //设置cookie
        $cookie->set('admin_captcha', $char, 300);

        $im->output();
    }
	
}