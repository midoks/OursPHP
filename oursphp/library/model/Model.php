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

    //use \frame\traits\WithCache;

    public function __construct(){

        //var_dump($this->getProjectName());

    	$this->_db = new Db();
    	$this->_db->setProject($this->getProjectName());

    }
    
    /**
	 * 启动当前连接的事务
	 */
	public function startTrans(){
		return $this->_db->startTrans();
	}
	
	/**
	 * 提交当前已经启动的事务
	 */
	public function commit(){
		return $this->_db->commit();
	}
	
	/**
	 * 回滚事务
	 */
	public function rollBack(){
		return $this->_db->rollBack();
	}

	/**
	 * 事务处理
     * @param $callback 闭包方法
	 */
	public function transaction($callback){
		return $this->_db->transaction($callback);
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
            $this->cacheEnd($trim_sql, $bind, $result);
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
        //var_dump($method, $args);exit;
    	return call_user_func_array(array($this->_db, $method), $args);
    }
}
