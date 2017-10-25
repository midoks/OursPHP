<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use App\Dao\System_Manager;
use OursPHP\Core\Lib\Traits\BaseService;
use OursPHP\Core\Lib\Cookie\CookieManage;

class ManagerSvc extends BaseService {

    public function userLoginOut() {

        $cookieManage = new CookieManage('manager', 0);
        $cookieManage->clear('info');
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function userLogin($username,$password) {
        $dao    = new System_Manager();
        $query['username'] = $username;
        $where  = ' username=:username and status=1';
        $user   = $dao->findOne($query,$where);
        if( $user && md5($password) === $user['password']) {
            $cookieManage = new CookieManage('manager',0);
            $cookieManage->set('info', $user, 0);
            return $user;
        }
        return false;
    }
}