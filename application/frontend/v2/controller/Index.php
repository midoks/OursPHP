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
        return 'v2';
    }

    public function v2() {
        return 'v2 func';
    }

}