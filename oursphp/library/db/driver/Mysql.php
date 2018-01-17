<?php

namespace frame\db\driver;

use frame\App;
use frame\Debug;
use frame\Logs;
use \PDO;

class Mysql {

    private $linkWrite = NULL;
    private $linkRead  = NULL;
    private $linkID    = NULL;

    //配置
    private $config = NULL;

    //支持多个连接
    protected $links = [];

    //单例对象
    private static $_instance = NULL;

    public $transTimes = 0;

    /**
     * 构造函数
     */
    private function __construct() {}

    /**
     * DB单例模式
     * @return DB object
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 注入配置信息
     * 格式:
     * @param array $config =>
     *  ['write' => [
     *        [
     *            'host'         => '127.0.0.1',
     *            'port'         => '3306',
     *            'user'         => 'root',
     *            'password'    => 'root',
     *            'charset'    => 'utf8',
     *            'prefix'    => 't_',
     *          'weight'    => 1,
     *        ],
     *    ],
     *    'read' => [
     *        [
     *            'host'         => '127.0.0.1',
     *            'port'         => '3306',
     *            'user'         => 'root',
     *            'password'    => 'root',
     *            'charset'    => 'utf8',
     *            'prefix'    => 't_',
     *          'weight'    => 1,
     *        ]
     *    ]]
     * @param $name string 项目名称
     * @return void
     */
    public function inject($config, $project) {
        $this->config      = $config;
        $this->projectName = $project;
    }

    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
     * @return string
     */
    protected function parseDsn($config) {
        $dsn = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'];
        if (!empty($config['port'])) {
            $dsn .= ';port=' . $config['port'];
        } elseif (!empty($config['socket'])) {
            $dsn .= ';unix_socket=' . $config['socket'];
        }
        if (!empty($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }
        return $dsn;
    }

    /**
     * 连接数据库方法
     * @access public
     * @param array         $config 连接参数
     * @param string|int    $linkNum 连接序号
     * @param array|bool    $autoConnection 是否自动连接主数据库（用于分布式）
     * @return PDO
     * @throws Exception
     */
    private function connect($config = array(), $linkNum = '0', $autoConnection = false) {
        if (!isset($this->links[$linkNum])) {
            try {
                if (empty($config['dsn'])) {
                    $config['dsn'] = $this->parseDsn($config);
                }
                $this->links[$linkNum] = new PDO($config['dsn'], $config['username'], $config['password']);
                $this->links[$linkNum]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                if ($autoConnection) {
                    return $this->connect($autoConnection, $linkNum);
                } else {
                    throw $e;
                }
            }
        }
        return $this->links[$linkNum];
    }

    private function initConnect($master = false) {
        $deploy      = $this->config['deploy'];
        $projectName = $this->projectName;
        if (!$deploy) {
            //单一部署

            $m        = 0;
            $dbConfig = $this->config['option'];

            if (!$this->linkID) {
                $this->linkID = $this->connect($dbConfig, $projectName . $m, false);
            }

        } else {
            // 采用分布式数据库
            if ($master) {
                if (!$this->linkWrite) {
                    $this->linkWrite = $this->multiConnect(true);
                }
                $this->linkID = $this->linkWrite;
            } else {
                if (!$this->linkRead) {
                    $this->linkRead = $this->multiConnect(false);
                }
                $this->linkID = $this->linkRead;
            }
        }
    }

    /**
     * 连接分布式服务器
     * @access protected
     * @param boolean $master 主服务器
     * @return PDO
     */
    protected function multiConnect($master = false) {
        $_config = array();

        $projectName = $this->projectName;

        // 分布式数据库配置解析
        if ($master) {
            $_config = $this->config['write'];
        } else {
            $_config = $this->config['read'];
        }

        // 主服务器序号(权重选择)
        $m         = $this->selectWeight($_config);
        $db_config = $_config[$m];

        return $this->connect($db_config, $projectName . $m, false);
    }

    /**
     * 配置权重选择
     * @param array $config
     * @return int
     */
    private function selectWeight($config) {

        $weight = array();
        foreach ($config as $k => $v) {
            $weight[$k] = $v['weight'];
        }

        $roll      = rand(1, array_sum($weight));
        $tmpWeight = 0;
        foreach ($weight as $kw => $kv) {
            $min = $tmpWeight;
            $tmpWeight += $kv;
            $max = $tmpWeight;
            if ($roll > $min && $roll <= $max) {
                return $kw;
            }
        }
    }

    public function query($sql, $bind = array(), $mode = PDO::FETCH_ASSOC) {
        $trim_sql = trim($sql);
        $result   = false;

        if (App::$debug) {
            Debug::remark('sql_start', 'time');
            Logs::record('[ SQL ] : ' . $sql . '|' . json_encode($bind), 'sql');
        }

        //查询数据
        if (preg_match('/^\s*(select|show|describe)/i', $trim_sql)) {
            $this->initConnect(false);

            $result = array();

            if (!empty($bind)) {
                $rows = $this->linkID->prepare($sql);
                if (!$rows) {
                    print_r($this->linkID->errorInfo());
                }

                $rows->execute($bind);
            } else {
                $rows = $this->linkID->query($sql, $mode);
                if (!$rows) {
                    return false;
                }
            }

            while ($data = $rows->fetch($mode)) {
                $result[] = $data;
            }

        } else {
            $this->initConnect(true);

            //添加数据 更新数据 删除数据 替换数据
            $stmt = $this->linkID->prepare($sql);
            if (!$stmt) {
                print_r($this->linkID->errorInfo());
            }

            $stmt->execute($bind);

            if (preg_match('/^\s*(insert)/i', $sql)) {
                $last_id = $this->linkID->lastInsertId();
                $result  = $last_id;
            } else {
                $result = $stmt->rowCount();
            }
        }

        if (App::$debug) {
            Debug::remark('sql_end', 'time');
            Logs::record('[ SQL TIME ] ' . Debug::getRangeTime('sql_start', 'sql_end') . 's', 'sql');
        }

        return $result;
    }

    public function getOne($sql, $bind = array()) {
        $list = $this->query($sql, $bind);
        return $list[0];
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
        $this->startTrans();
        try {
            $result = null;
            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$this]);
            }
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans() {

        $this->initConnect(true);

        if (!$this->linkID) {
            return false;
        }

        ++$this->transTimes;

        if (1 == $this->transTimes) {
            $this->linkID->beginTransaction();
        } elseif ($this->transTimes > 1 && $this->supportSavepoint()) {
            $this->linkID->exec($this->parseSavepoint('trans' . $this->transTimes));
        }
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return void
     * @throws PDOException
     */
    public function commit() {
        $this->initConnect(true);

        if (1 == $this->transTimes) {
            $this->linkID->commit();
        }

        --$this->transTimes;
    }

    /**
     * 事务回滚
     * @access public
     * @return void
     * @throws PDOException
     */
    public function rollBack() {
        $this->initConnect(true);

        if (1 == $this->transTimes) {
            $this->linkID->rollBack();
        } elseif ($this->transTimes > 1 && $this->supportSavepoint()) {
            $this->linkID->exec($this->parseSavepointRollBack('trans' . $this->transTimes));
        }

        $this->transTimes = max(0, $this->transTimes - 1);
    }

    /**
     * 是否支持事务嵌套
     * @return bool
     */
    protected function supportSavepoint() {
        return false;
    }

    /**
     * 生成定义保存点的SQL
     * @param $name
     * @return string
     */
    protected function parseSavepoint($name) {
        return 'SAVEPOINT ' . $name;
    }

    /**
     * 生成回滚到保存点的SQL
     * @param $name
     * @return string
     */
    protected function parseSavepointRollBack($name) {
        return 'ROLLBACK TO SAVEPOINT ' . $name;
    }

    //字符串转义
    public function quote($string, $parameter_type = PDO::PARAM_STR) {
        return $this->linkID->quote($string, $parameter_type);
    }

    /**
     *    @func 析构函数
     */
    public function __destruct() {
        $this->linkWrite = NULL;
        $this->linkRead  = NULL;
        $this->linkID    = NULL;
        $this->config    = NULL;
        $this->links     = [];
    }
}
?>