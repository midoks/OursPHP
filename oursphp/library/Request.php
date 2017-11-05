<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------


namespace frame;

class Request {

    /**
     * @var string pathinfo
     */
    protected $pathinfo;

    /**
     * @var string pathinfo（不含后缀）
     */
    protected $path;
    
    private static $_instance;
    private static $filter   = "'|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

    const REPLACEMENT = '';

    protected $module;
    protected $controller;
    protected $action;

    private function __construct() {
        //$this->input = file_get_contents("php://input");
    }

    /**
     * 读取请求缓存设置
     * @access public
     * @return array
     */
    public function getCache() {
        return $this->cache;
    }

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return \frame\Request
     */
    public static function getInstance() {
        if (!self::$_instance){
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /**
     * 当前的请求类型
     * @access public
     * @param bool $method  true 获取原始请求类型
     * @return string
     */
    public function method($method = false) {
        if (true === $method) {
            // 获取原始请求类型
            return IS_CLI ? 'GET' : (isset($this->server['REQUEST_METHOD']) ? $this->server['REQUEST_METHOD'] : $_SERVER['REQUEST_METHOD']);
        } elseif (!$this->method) {
            if (isset($_POST[Config::get('var_method')])) {
                $this->method = strtoupper($_POST[Config::get('var_method')]);
                $this->{$this->method}($_POST);
            } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $this->method = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            } else {
                $this->method = IS_CLI ? 'GET' : (isset($this->server['REQUEST_METHOD']) ? $this->server['REQUEST_METHOD'] : $_SERVER['REQUEST_METHOD']);
            }
        }
        return $this->method;
    }

    /**
     * 设置或者获取当前的Header
     * @access public
     * @param string|array  $name header名称
     * @param string        $default 默认值
     * @return string
     */
    public function header($name = '', $default = null) {
        if (empty($this->header)) {
            $header = [];
            if (function_exists('apache_request_headers') && $result = apache_request_headers()) {
                $header = $result;
            } else {
                $server = $this->server ?: $_SERVER;
                foreach ($server as $key => $val) {
                    if (0 === strpos($key, 'HTTP_')) {
                        $key          = str_replace('_', '-', strtolower(substr($key, 5)));
                        $header[$key] = $val;
                    }
                }
                if (isset($server['CONTENT_TYPE'])) {
                    $header['content-type'] = $server['CONTENT_TYPE'];
                }
                if (isset($server['CONTENT_LENGTH'])) {
                    $header['content-length'] = $server['CONTENT_LENGTH'];
                }
            }
            $this->header = array_change_key_case($header);
        }
        if (is_array($name)) {
            return $this->header = array_merge($this->header, $name);
        }
        if ('' === $name) {
            return $this->header;
        }
        $name = str_replace('_', '-', strtolower($name));
        return isset($this->header[$name]) ? $this->header[$name] : $default;
    }
    

    /**
     * 返回 $_GET[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function get($index, $default = '') {
        if (isset($_GET[$index])) {
            $data=$this->filter_input($_GET[$index]);
            return preg_replace("/".self::$filter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * 返回 $_POST[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function post($index, $default = '') {
        if (isset($_POST[$index])) {
            $data = $_REQUEST[$index];
            return preg_replace("/".self::$filter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * 设置或者获取当前的模块名
     * @access public
     * @param string $module 模块名
     * @return string|Request
     */
    public function module($module = null) {
        if (!is_null($module)) {
            $this->module = $module;
            return $this;
        } else {
            return $this->module ?: '';
        }
    }

    /**
     * 设置或者获取当前的控制器名
     * @access public
     * @param string $controller 控制器名
     * @return string|Request
     */
    public function controller($controller = null) {
        if (!is_null($controller)) {
            $this->controller = $controller;
            return $this;
        } else {
            return $this->controller ?: '';
        }
    }

    /**
     * 设置或者获取当前的操作名
     * @access public
     * @param string $action 操作名
     * @return string|Request
     */
    public function action($action = null) {
        if (!is_null($action)) {
            $this->action = $action;
            return $this;
        } else {
            return $this->action ?: '';
        }
    }

    /**
     * 返回 $_REQUEST[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function getRequest($index, $default='') {
        if (isset($_REQUEST[$index])) {
            $data = $_REQUEST[$index];
            $data = preg_replace("/".self::$filter."/is", self::REPLACEMENT, $data);
            return preg_replace("/".self::$filter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * $_COOKIE[$index]
     * @param string $index
     * @return array|null <string, string>
     */
    public function getCookie($index) {
        return isset($_COOKIE[$index]) ? preg_replace("/" . self::$filter . "/is", self::REPLACEMENT, $_COOKIE[$index]) : null;
    }


    /**
     * 获取当前请求URL的pathinfo信息（含URL后缀）
     * @access public
     * @return string
     */
    public function pathinfo() {
        if (is_null($this->pathinfo)) {
            // 分析PATHINFO信息
            if (!isset($_SERVER['PATH_INFO'])) {
                foreach (Config::get('pathinfo_fetch') as $type) {
                    if (!empty($_SERVER[$type])) {
                        $_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ?
                        substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
                        break;
                    }
                }
            }
            $this->pathinfo = empty($_SERVER['PATH_INFO']) ? '/' : ltrim($_SERVER['PATH_INFO'], '/');
        }
        return $this->pathinfo;
    }

    /**
     * 获取当前请求URL的pathinfo信息(不含URL后缀)
     * @access public
     * @return string
     */
    public function path() {

        if (is_null($this->path)) {
            $suffix   = Config::get('url_html_suffix');
            $pathinfo = $this->pathinfo();
            if (false === $suffix) {
                // 禁止伪静态访问
                $this->path = $pathinfo;
            } elseif ($suffix) {
                // 去除正常的URL后缀
                $this->path = preg_replace('/\.(' . ltrim($suffix, '.') . ')$/i', '', $pathinfo);
            } else {
                // 允许任何后缀访问
                $this->path = preg_replace('/\.' . $this->ext() . '$/i', '', $pathinfo);
            }
        }
        return $this->path;
    }

    /**
     * 当前URL的访问后缀
     * @access public
     * @return string
     */
    public function ext() {
        return pathinfo($this->pathinfo(), PATHINFO_EXTENSION);
    }

    public function isPost() {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * 用对象属性方式直接调用
     * @param string $key
     * @return string
     */
    public function __get($key) {
        return $this->getRequest($key);
    }

}