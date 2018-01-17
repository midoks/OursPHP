<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\exception;

use frame\Response;

class HttpResponseException extends \RuntimeException {
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }

}
