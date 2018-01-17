<?php
namespace frame\model;

//abstract class Model implements ConnectionInterface
abstract class Orm extends Model {

    public function __construct() {
        parent::__construct();
    }

    //Model对应表名，由子类实现
    protected abstract function getTableName();

    //Model对应表主键名，由子类实现
    protected abstract function getPKey();

    public function __call($method, $args) {
        return call_user_func_array(array($this->_db, $method), $args);
    }

}
