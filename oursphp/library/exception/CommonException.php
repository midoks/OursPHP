<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\exception;

use frame\Exception;

class CommonException extends Exception {

    public function __construct($message, $type = 'common') {

        $this->setData($type, ['msg' => $message]);

        parent::__construct($message, 404);
    }
}
