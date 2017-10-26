<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\model;

use frame\db\Db;
use frame\Config;

class Model {

    use \frame\traits\Cache;

	public $_db = NULL;
    public $_default_item = 'default';

    public function __construct(){

        //var_dump($this->getProjectName());

    	$this->_db = new Db();
    	$this->_db->setProject($this->getProjectName());

    }

	/**
     * 获取数据
     * @param $sql string SQL语句
     * @param $bindd array 绑定数据
     * @return array
     */
    public function query($sql, $bind = []){
    	
    	$trim_sql = trim($sql);

    	if(preg_match( '/^\s*(select|show|describe)/i', $trim_sql)){
    		$result = $this->_db->query($trim_sql, $bind);

            // \frame\traits\Cache;
            if (method_exists($this, 'cacheEnd')){
                $this->cacheEnd($trim_sql, $bind, $result);
            }
    		return $result;
    		
    	}
    	return $this->_db->query($trim_sql, $bind);
    }


    /**
     * 获取一条数据
     * @param $sql string SQL语句
     * @param $bindd array 绑定数据
     * @return array
     */
	public function getOne($sql, $bind = array()){
		$list = $this->query($sql, $bind);
		return $list[0];
	}

	public function __call($method, $args){
        if (method_exists($this->_db, $method)){
            return call_user_func_array(array($this->_db, $method), $args);
        }
        return false;
    }
}
