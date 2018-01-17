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

class Model {

    use \frame\traits\Cache;

    public $_db = NULL;

    public function __construct() {

        $this->_db = new Db();
        $this->_db->setProject();
    }

    /**
     * 获取数据
     * @param $sql string SQL语句
     * @param $bindd array 绑定数据
     * @return array
     */
    public function query($sql, $bind = []) {

        $trim_sql = trim($sql);

        if (preg_match('/^\s*(select|show|describe)/i', $trim_sql)) {

            // \frame\traits\Cache;
            if (method_exists($this, 'cacheSqlStart')) {
                $cache_result = $this->cacheSqlStart($trim_sql, $bind);
                if ($cache_result) {
                    return $cache_result;
                }
            }

            $result = $this->_db->query($trim_sql, $bind);

            // \frame\traits\Cache;
            if (method_exists($this, 'cacheSqlEnd')) {
                $this->cacheSqlEnd($trim_sql, $bind, $result);
            }
            return $result;

        }
        return $this->_db->query($trim_sql, $bind);
    }

    /**
     * 执行数据库事务
     * @access public
     * @param callable $callback 数据操作方法回调
     * @return mixed
     * @throws PDOException
     * @throws \Exception
     * @throws \Throwable
     */
    public function transaction($callback) {
        return $this->_db->startTrans($callback);
    }

    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans() {
        $this->_db->startTrans();
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return void
     * @throws PDOException
     */
    public function commit() {
        return $this->_db->commit();
    }

    /**
     * 事务回滚
     * @access public
     * @return void
     * @throws PDOException
     */
    public function rollBack() {
        return $this->_db->rollBack();
    }

    /**
     * 获取一条数据
     * @param $sql string SQL语句
     * @param $bindd array 绑定数据
     * @return array
     */
    public function getOne($sql, $bind = array()) {
        $list = $this->query($sql, $bind);

        if (empty($list)) {
            return false;
        }
        return $list[0];
    }
}
