<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

class SimpleEncrypt {


	//加密函数
	public static function encode($txt, $key) {
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000));
		$ctr = 0;
		$tmp = '';
		for($i = 0;$i < strlen($txt); $i++) {
		   $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		   $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
		}
		return base64_encode(self::passport_key($tmp, $key));
	}

	//解密函数
	public static function decode($txt, $key) {
		$txt = self::passport_key(base64_decode($txt), $key);
		$tmp = '';
		for($i = 0;$i < strlen($txt); $i++) {
		   $md5 = $txt[$i];
		   $tmp .= $txt[++$i] ^ $md5;
		}
		return $tmp;
	}

	public static function passport_key($txt, $encrypt_key) {
		$encrypt_key = md5($encrypt_key);
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
		   $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		   $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
		}
		return $tmp;
	}
}


?>