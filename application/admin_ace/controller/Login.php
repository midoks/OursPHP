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

use common\dao\SysUser;

class Login extends Controller {
    
	//登录
    public function index($request, $response) {
    	//var_dump($_POST);
        $dao        = new SysUser();
        //$dao->startTrans();
        $cookie     = Cookie::getInstance();

        if ($request->isPost()){

            $password = $request->post('password');
            $vars = $request->post('vars');
            $code = $cookie->get('admin_captcha');

            if (strtolower($vars['code']) == strtolower($code)){

                $name = $request->name; 
                $query['username'] = $name;
                $where  = ' username=:username and status=1';
                $user   = $dao->cache('cache_t', 30)->findOne($query, $where);
 
                if( $user && md5($password) === $user['password']) {
                    $cookie->set('info', $user, 0);
                    $this->redirect("/index");
                }
            }
        }

        return $this->render();
    }

    public function t(){
        $dao        = new SysUser();
        $dao->startTrans();

        $query['username'] = 'admin';
        $where  = ' username=:username and status=1';
        $data = $user   = $dao->cache(30)->findOne($query,$where);
        $data = $user   = $dao->findOne($query,$where);

        // $data = $user   = $dao->findOne($query,$where);
        // var_dump($data);
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