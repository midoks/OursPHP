<?php
namespace OursPHP\Core\Lib\Db;

use OursPHP\Core\Common\Config;
use OursPHP\Core\Common\DataAssert;
use OursPHP\Core\Common\BizException;

use OursPHP\Core\Lib\Db\Db;
use OursPHP\Core\Mvc\Model\Model;

//abstract class Model implements ConnectionInterface 
abstract class Orm extends Model {

    public function __construct(){
    	parent::__construct();
    }

	//取得数据库从库配置，由子类实现
	protected abstract function getProjectName();
	
	//Model对应表名，由子类实现
	protected abstract function getTableName();
	
	//Model对应表主键名，由子类实现
	protected abstract function getPKey();

	public function __call($method, $args){
    	return call_user_func_array(array($this->_db, $method), $args);
    }

}
