<?php
// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;


class Logs {

	public  static function record($log, $error){

	}


	/**
	 * 调式显示日志
	 * @param $msg string 日志信息
	 * @return void
	 */
	public static function show($msg) {
		if (OURS_DEBUG) {
        	echo '<!--  '.$msg.'  -->'."\r\n";
    	}
	}

	public static function getLog(){
		return [];
	}

	/**
	 * 判断是否是chrome浏览器内容
	 * @return boolean
	 */
	public static function isChrome(){


	}

	public static function save($log, $type){
		var_dump($log,$type);
	}

}



?>