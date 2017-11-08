<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;

class Loader {

	protected static $instance = [];
    // 类名映射
    protected static $map = [];

    // 命名空间别名
    protected static $namespaceAlias = [];

    // PSR-4
    private static $prefixLengthsPsr4 = [];
    private static $prefixDirsPsr4    = [];
    private static $fallbackDirsPsr4  = [];

    // PSR-0
    private static $prefixesPsr0     = [];
    private static $fallbackDirsPsr0 = [];

    // 自动加载的文件
    private static $autoloadFiles = [];

    public static function autoload($class){

       	if ($file = self::findFile($class)) {
            // Win环境严格区分大小写
            if (IS_WIN && pathinfo($file, PATHINFO_FILENAME) != pathinfo(realpath($file), PATHINFO_FILENAME)) {
                return false;
            }
            
            __include_file($file);
            return true;
        }
    }


    public static function register(){
    	spl_autoload_register('frame\\Loader::autoload', true, true);

    	// 注册命名空间定义
        self::addNamespace([
            'frame'    => FRAME_PATH,
        ]);

    	// Composer自动加载支持
        if (is_dir(VENDOR_PATH.'composer')) {
            self::registerComposer();
        }
        
    }


    // 注册composer自动加载
    public static function registerComposer(){
    	if (is_file(VENDOR_PATH.'composer/autoload_namespaces.php')) {
            $map = require VENDOR_PATH.'composer/autoload_namespaces.php';
            var_dump('autoload_namespaces',$map);
            foreach ($map as $namespace => $path) {
                
                self::addPsr0($namespace, $path);
            }
        }

        if (is_file(VENDOR_PATH.'composer/autoload_psr4.php')) {
            $map = require VENDOR_PATH.'composer/autoload_psr4.php';
            var_dump($map);
            foreach ($map as $namespace => $path) {
                var_dump('autoload_psr4',$namespace,$path);
                self::addPsr4($namespace, $path);
            }
        }


        if (is_file(VENDOR_PATH.'composer/autoload_classmap.php')) {
            $classMap = require VENDOR_PATH.'composer/autoload_classmap.php';
            var_dump('autoload_classmap',$classMap);
            if ($classMap) {
                self::addClassMap($classMap);
            }
        }

        if (is_file(VENDOR_PATH.'composer/autoload_files.php')) {
            $includeFiles = require VENDOR_PATH.'composer/autoload_files.php';
            var_dump($includeFiles);
            foreach ($includeFiles as $fileIdentifier => $file) {
                if (empty(self::$autoloadFiles[$fileIdentifier])) {
                    __require_file($file);
                    self::$autoloadFiles[$fileIdentifier] = true;
                }
            }
        }

    }

    /**
     * 查找文件
     * @param $class
     * @return bool
     */
    private static function findFile($class) {
        // var_dump(self::$map, self::$prefixLengthsPsr4, self::$fallbackDirsPsr4, self::$prefixesPsr0, self::$fallbackDirsPsr0);

        if (!empty(self::$map[$class])) {
            // 类库映射
            return self::$map[$class];
        }

        // 查找 PSR-4
        $logicalPathPsr4 = strtr($class, '\\', DS) . EXT;

        $first = $class[0];
        if (isset(self::$prefixLengthsPsr4[$first])) {
            foreach (self::$prefixLengthsPsr4[$first] as $prefix => $length) {
                if (0 === strpos($class, $prefix)) {
                    foreach (self::$prefixDirsPsr4[$prefix] as $dir) {
                        if (is_file($file = $dir . DS . substr($logicalPathPsr4, $length))) {
                            return $file;
                        }
                    }
                }
            }
        }

        // 查找 PSR-4 fallback dirs
        foreach (self::$fallbackDirsPsr4 as $dir) {
            if (is_file($file = $dir . DS . $logicalPathPsr4)) {
                return $file;
            }
        }

        // 查找 PSR-0
        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $logicalPathPsr0 = substr($logicalPathPsr4, 0, $pos + 1)
            . strtr(substr($logicalPathPsr4, $pos + 1), '_', DS);
        } else {
            // PEAR-like class name
            $logicalPathPsr0 = strtr($class, '_', DS) . EXT;
        }

        if (isset(self::$prefixesPsr0[$first])) {
            foreach (self::$prefixesPsr0[$first] as $prefix => $dirs) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($dirs as $dir) {
                        if (is_file($file = $dir . DS . $logicalPathPsr0)) {
                            return $file;
                        }
                    }
                }
            }
        }

        // 查找 PSR-0 fallback dirs
        foreach (self::$fallbackDirsPsr0 as $dir) {
            if (is_file($file = $dir . DS . $logicalPathPsr0)) {
                return $file;
            }
        }

        return self::$map[$class] = false;
    }

    // 注册classmap
    public static function addClassMap($class, $map = '') {
        if (is_array($class)) {
            self::$map = array_merge(self::$map, $class);
        } else {
            self::$map[$class] = $map;
        }
    }

    // 注册命名空间
    public static function addNamespace($namespace, $path = '') {
        if (is_array($namespace)) {
            foreach ($namespace as $prefix => $paths) {
                self::addPsr4($prefix . '\\', rtrim($paths, DS), true);
            }
        } else {
            self::addPsr4($namespace . '\\', rtrim($path, DS), true);
        }
    }

    public static function addNamespaceOverride($namespace, $path = ''){
        if (is_array($namespace)) {
            foreach ($namespace as $prefix => $paths) {
                $name = $prefix.'\\';
                self::$prefixDirsPsr4[$name] = NULL;
            }
        } else {
            $name = $namespace.'\\';
            self::$prefixDirsPsr4[$name] = NULL;
        }
        
        self::addNamespace($namespace, $path = '');
    }

    // 添加Ps0空间
    private static function addPsr0($prefix, $paths, $prepend = false) {
        if (!$prefix) {
            if ($prepend) {
                self::$fallbackDirsPsr0 = array_merge(
                    (array) $paths,
                    self::$fallbackDirsPsr0
                );
            } else {
                self::$fallbackDirsPsr0 = array_merge(
                    self::$fallbackDirsPsr0,
                    (array) $paths
                );
            }

            return;
        }

        $first = $prefix[0];
        if (!isset(self::$prefixesPsr0[$first][$prefix])) {
            self::$prefixesPsr0[$first][$prefix] = (array) $paths;

            return;
        }
        if ($prepend) {
            self::$prefixesPsr0[$first][$prefix] = array_merge(
                (array) $paths,
                self::$prefixesPsr0[$first][$prefix]
            );
        } else {
            self::$prefixesPsr0[$first][$prefix] = array_merge(
                self::$prefixesPsr0[$first][$prefix],
                (array) $paths
            );
        }
    }

    // 添加Psr4空间
    private static function addPsr4($prefix, $paths, $prepend = false) {
        if (!$prefix) {
            // Register directories for the root namespace.
            if ($prepend) {
                self::$fallbackDirsPsr4 = array_merge(
                    (array) $paths,
                    self::$fallbackDirsPsr4
                );
            } else {
                self::$fallbackDirsPsr4 = array_merge(
                    self::$fallbackDirsPsr4,
                    (array) $paths
                );
            }
        } elseif (!isset(self::$prefixDirsPsr4[$prefix])) {
            // Register directories for a new namespace.
            $length = strlen($prefix);
            if ('\\' !== $prefix[$length - 1]) {
                throw new \InvalidArgumentException("A non-empty PSR-4 prefix must end with a namespace separator.");
            }
            self::$prefixLengthsPsr4[$prefix[0]][$prefix] = $length;
            self::$prefixDirsPsr4[$prefix]                = (array) $paths;
        } elseif ($prepend) {
            // Prepend directories for an already registered namespace.
            self::$prefixDirsPsr4[$prefix] = array_merge(
                (array) $paths,
                self::$prefixDirsPsr4[$prefix]
            );
        } else {
            // Append directories for an already registered namespace.
            self::$prefixDirsPsr4[$prefix] = array_merge(
                self::$prefixDirsPsr4[$prefix],
           		(array) $paths
            );
        }
    }
}

/**
 * 作用范围隔离
 *
 * @param $file
 * @return mixed
 */
function __include_file($file)
{
    return include $file;
}

function __require_file($file)
{
    return require $file;
}