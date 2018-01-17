<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace app\controller;

use frame\Cookie;

class T {

    public function t($request, $response) {

        Cookie::init([
            'path'   => '/',
            'domain' => '.oursphp.cn',
            'secure' => false,
        ]);
        Cookie::set('t', '123', 111);
    }

    public function t2($request, $response) {

        Cookie::init([
            'path'   => '/ddd',
            'secure' => true,
        ]);
        $t = Cookie::get('t');
        var_dump($t);
    }

}