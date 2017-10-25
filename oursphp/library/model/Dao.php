<?php
namespace oursphp\core\mvc\model;

use oursphp\core\common\Config;
use oursphp\core\common\Assert;
use oursphp\core\common\BizException;

use oursphp\core\mvc\model\Model;


//abstract class Model implements ConnectionInterface 
abstract class Dao extends Model {

    public function __construct(){
    	parent::__construct();
    }
    
	
	//取得数据库从库配置，由子类实现
	protected abstract function getProjectName();
	
	//Model对应表名，由子类实现
	protected abstract function getTableName();
	
	//Model对应表主键名，由子类实现
	protected abstract function getPKey();


    /**
     * 根据主键查找一条记录
     * @param $pk_value
     * @return mixed
     */
    public function findByPkey($pk_value) {
        $sql 	= "select * from {$this->getTableName()} where {$this->getPkeyWhereEx()} limit 1";
        $binds	= $this->getPkeyBind($pk_value);
        return $this->getOne($sql,$binds);
    }

    /**
     * @param array $binds
     * @param string $where
     * @param array $feild
     * @param string $group
     * @param string $having
     * @param string $order
     * @return bool
     */
	public function findOne($binds = [], $where = '', $feild = [], $group = '', $having = '', $order = '') {
		$rows = self::findAll($binds, $where, 1, $feild, $group, $having, $order);
		return isset($rows[0]) ? $rows[0]: null;
	}
	
	/**
	 * @param unknown_type $fieldName 字段名
	 * @param unknown_type $value 字段值
	 */
	public function findByField($fieldName, $value) {
		$sql = "select * from {$this->getTableName()} where $fieldName=?";
		return $this->_db->getRows($sql, array($value));
	}
	
	public function exec($sql,array $binds) {
		return $this->_db->getScaler($sql,$binds);
	}
	
	/**
	 * 根据条件统计
	 * @param unknown $where
	 */
	public function countBy(array $binds, $where) {
		$sql = "select count(1) as num from {$this->getTableName()} where $where";
		return $this->getOne($sql,$binds);
	}
	
	/**
	 * 求和
	 * @param string $fieldName 需要求和的字段
	 * @param array $binds 
	 * @param unknown $where
	 */
	public function sumBy($fieldName, array $binds, $where) {
		$sql = "select sum($fieldName) as $fieldName from {$this->getTableName()} where $where";
		return $this->getOne($sql,$binds);
	}
	/**
	 * 新增一条记录
	 * @param array $data 行记录数组
	 */
	public function add(array $data) {
		Assert::notEmpty($data, new BizException('插入内容为空'));
		return $this->_db->insert($this->getTableName(), $data);
	}
	
	/**
	 * 新增一条记录
	 * @param array $vars 行记录数组
	 */
	public function adds(array $varslist) {
		Assert::notEmpty($varslist, new BizException('插入内容为空'));
		return $this->_db->inserts($this->getTableName(), $varslist);
	}

	/**
	 * 修改一条记录
	 * @param unknown_type $pk_value 主键
	 * @param array $vars 修改行记录数组
	 */
	public function edit($pk_value, array $vars) {
		Assert::notEmpty($pk_value, new BizException('主键为空'));
		return $this->_db->update($this->getTableName(), $vars, $this->getPkeyWhere($pk_value));
		
	}
	
	/**
	 * 根据条件更新
	 * @param array $vars
	 * @param string $where
	 * e.g. 
	 * 	editByWhere(array('name'=>'hi'), 'id=1') == sql: update xxx set name='hi' where id=1;
	 */
	public function editByWhere(array $vars, $where) {
		Assert::notEmpty($where, new BizException('where条件为空'));
		return $this->_db->update($this->getTableName(), $vars, $where);
	}
	
	
	/**
	 * 根据数据表里的 unique index 替换
	 * @param array $vars 修改行记录数组
	 */
	public function replace(array $vars) {
		return $this->_db->replace($this->getTableName(), $vars);
	
	}
	
	/**
	 * 按主键删除一条记录
	 * @param unknown_type $pk_value 主键值
	 */
	public function delete($pk_value) {
		Assert::notEmpty($pk_value, new BizException('主键为空'));
		return $this->_db->delete($this->getTableName(), $this->getPkeyWhere($pk_value));
	}
	
	/**
	 * 按条件删除一条记录
	 * @param unknown_type $where 条件
	 */
	public function deleteByWhere($where) {
		Assert::notEmpty($where, new BizException('where条件为空'));
		return $this->_db->delete($this->getTableName(), $where);
	}

	protected function getPkeyWhereEx() {
		$pkname = $this->getPKey();
		return " $pkname=:$pkname";
	}

	protected function getPkeyBind($pk_value) {
		$pkname = $this->getPKey();
		return [$pkname=>$pk_value];
	}

	protected function getPkeyWhere($pk_value) {

		if (is_array($pk_value)) {
			$tmp = array();
			foreach ($pk_value as $key=>$field) {
				$tmp[] = "$key='$field'";
			}
			return implode(' and ', $tmp);
		} else {
		    $pkname = $this->getPKey();
			return " $pkname='$pk_value' ";
		}
	}
	

    /**
     * @param array $binds
     * @param string $where
     * @param array $feild
     * @param string $group
     * @param string $having
     * @param string $order
     * @param int $limit
     * @return mixed
     */
	public function findAll($binds = array(), $where = '', $limit = 100, $feild = array(), $group = '',$having = '',$order = '') {
		$feildstr='*';
		if(!empty($feild)) {
			$feildstr=implode(',', $feild);
		}
		$sql = "select $feildstr from {$this->getTableName()} ";
		if(!empty($where)) {
			$sql.="where $where ";
		}
		if(!empty($group)) {
			$sql.="group by $group ";
			if(!empty($having)) {
				$sql.="having $having  ";
			}
		}
		if(!empty($order)) {
			$sql.="order by $order ";
		}
		if($limit!=0 || is_string($limit)) {
			$sql.="limit $limit";
		}

		return $this->query($sql, $binds);
	}
}
