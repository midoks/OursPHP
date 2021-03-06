<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\exception;

use Exception;
use frame\exception\Handle as BaseHandle;
use frame\Response;

class Handle extends BaseHandle {

    public function render(Exception $e) {

        $response = new Response('123123', 'html');
        $response->code(200);
        return $response;
    }

}
?>