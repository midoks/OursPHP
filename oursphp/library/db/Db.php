<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\db;

use frame\db\driver\Mysql;
use frame\Config;

class Db {

	private $instance = NULL;

	//mysql 常用关键字
	public static $keys = array(
		'user',
		'time',
		'key',
		'type',
		'condition',
		'div',
		'status',
		'order',
	);

	public function __construct(){}

	/**
	 * 设置当前项目
	 * @param string $name 项目名
	 */
	public function setProject($name){
		$this->instance = Mysql::getInstance();
		$cfg = Config::get('db', $name);
		$this->instance->injection($cfg, $name);
	}

	public function query($sql, $bind = array()){
		return $this->instance->query($sql, $bind);
	}

	/**
	 * 插入数据
	 * @param $table string 表名
	 * @param $data array 数据
	 * @return int 最后插入ID
	 */
	public function insert($table, $data){
		$ks = array();
		foreach (array_keys($data) as $k) {
			if (in_array($k, self::$keys)){
				$k = "`$k`";
			}
			$ks[] = $k;
		}
		$sqlK = implode(', ', $ks);
		$sqlV = ':'.implode(', :', array_keys($data));
		
		$sql = "insert into $table ($sqlK) values ($sqlV)";

		$_data = array();
		foreach ($data as $key => $value) {
			$_data[':'.$key] = $value;
		}

		return $this->instance->query($sql, $_data);
	}

	/**
	 * 插入多条数据 
	 * @param string $table
	 * @param array $datas
	 * @return boolean
	 */
	public function inserts($table, array $datas) {
		$ks = array();
		foreach (array_keys($datas[0]) as $k) {
			if (in_array($k, self::$keys)){
				$k = "`$k`";
			}
			$ks[] = $k;
		}
		$sqlK 	= implode(', ', $ks);
		$i 		= 0;
		$sqlV 	= '';
		$newdata= [];

		foreach ($datas as &$item) {
			$keys 	= array_keys($item);
			$_sqlV 	= ':'.implode($i.', :', $keys).$i;
			$sqlV 	= $sqlV.(',('.$_sqlV.')');
			$sqlV 	= ltrim($sqlV, ",");
			
			foreach ($keys as $key) {
				$newdata[$key.$i] = $item[$key];
			}
			$i++;
		}

		$sql = "insert into $table ($sqlK) values $sqlV";

		$_datas = array();
		foreach ($datas as $key1 => $keyData) {
			foreach ($keyData as $key2=>$data) {
				$_datas[':'.$key2.$key1] = $data;
			}
		}
		
		return $this->instance->query($sql, $_datas);
	}

	/**
	 * 删除数据
	 * @param $table string 表名
	 * @param $where mixed  删除条件
	 * @return int 返回影响行数
	 */
	public function delete($table, $where){
		$sql = "delete from $table where $where";
		return $this->instance->query($sql);
	}

	/**
	 * 更新数据
	 * @param $table string 表名
	 * @param $where mixed  删除条件
	 * @return int 返回影响行数
	 */
	public function update($table, $data, $where){
		if (strlen($where) == 0){
			return false;
		}
			
		$sqlU = 'set ';
		foreach ($data as $v=>$v2) {
			if ($v[0] == ':'){
				$v[0] = '';
			}

			if (in_array($v, self::$keys)){
				$k = "`$v`";
			} else {
				$k = $v;
			}
			$sqlU .= "$k=:$v, ";
		}
		$sqlU = trim(trim($sqlU, ' '), ',');
		$sql = "update $table $sqlU where $where";

		$_data = array();
		foreach ($data as $key => $value) {
			$_data[':'.$key] = $value;
		}
		
		return $this->instance->query($sql, $_data);
	}

	
}

?>