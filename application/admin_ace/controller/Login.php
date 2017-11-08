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
use \frame\Session;

use frame\utils\Cookie;


use common\dao\SysUserDao;

class Login extends Controller {
    
	//登录
    public function index($request, $response) {
        $dao        = new SysUserDao();
        $cookie     = Cookie::getInstance();

        //检查是否已经登录
        $this->_user = $cookie->get('info');
        if ($this->_user){
            $this->redirect('/index');
        }

        //登录操作
        if ($request->isPost()){

            $password = $request->post('password');
            $vars = $request->post('vars');
            $code = $cookie->get('admin_captcha');

            if (strtolower($vars['code']) == strtolower($code)){

                $name = $request->name; 
                $query['username'] = $name;
                $where  = ' username=:username and status=1';
                $user   = $dao->findOne($query, $where);
 
                if( $user && md5($password) === $user['password']) {
                    $cookie->set('info', $user, 1*24*60*60);
                    $this->redirect("/index");
                }
            }
        }

        return $this->render();
    }

    //登出
    public function out($request, $response) {
        $cookie = Cookie::getInstance();
        $cookie->setPath('/');

        $ret    = $cookie->clear('info');
        //var_dump($ret);
        $this->redirect("/index");
        exit;
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