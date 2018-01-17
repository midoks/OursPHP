<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace app\controller;

use \frame\Controller;

class Index extends Controller {

    public function index() {
        return 'v1';
    }

    public function v1() {
        return 'v1 func';
    }

}