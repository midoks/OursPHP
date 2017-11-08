<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;


use frame\cache\driver\Memcache;
use frame\cache\driver\Memcached;
use frame\cache\driver\Redis;
use frame\cache\driver\None;


class Cache {

	public static $_instance = NULL;
	
	public static function getInstance() {
		
		if(isset(self::$_instance)){
           return self::$_instance;
        }

        $option = Config::get('cache');
        $name = strtolower($option['type']);
        
        App::$debug && Logs::record('[ CACHE ] INIT ' . $name, 'info');

        try {
            if (in_array($name, array('memcache', 'memcached','redis'))){
                if ( 'memcache' == $name){
                    self::$_instance =  Memcache::getInstance();
                } else if ('memcached' == $name) {
                    self::$_instance =  Memcached::getInstance();
                } else if( 'redis' == $name){ //Redis
                    self::$_instance =  Redis::getInstance();
                }
            } else {
                self::$_instance =  None::getInstance();
            }  
        } catch (\Exception $e) {
            $option['type'] = '';
            Config::set( $option, 'cache' );
            self::$_instance =  None::getInstance();
        }

        return  self::$_instance;
	}
}

?>