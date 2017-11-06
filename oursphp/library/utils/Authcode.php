<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\utils;

class Authcode {

    //加密
    public static function encode($string){
        return self::authcode($string, 'ENCODE');
    }

    //解密
    public static function decode($string){
        return self::authcode($string, 'DECODE');
    }

    
    public static function encrypt($string, $operation, $key='' ){ 
        $key = md5($key); 
        $key_length = strlen($key); 
        $string = $operation == 'D'?base64_decode($string):substr(md5($string.$key),0,8).$string; 
        $string_length = strlen($string); 
        $rndkey = $box= array(); 
        $result = '';

        for($i=0;$i<=255;$i++){ 
               $rndkey[$i]=ord($key[$i%$key_length]); 
            $box[$i]=$i; 
        } 
        
        for($j=$i=0;$i<256;$i++){ 
            $j=($j+$box[$i]+$rndkey[$i])%256; 
            $tmp=$box[$i]; 
            $box[$i]=$box[$j]; 
            $box[$j]=$tmp; 
        } 
        
        for($a = $j = $i = 0; $i < $string_length; $i++){ 
            $a = ($a+1)%256;
            $j = ($j+$box[$a])%256; 
            $tmp = $box[$a]; 
            $box[$a] = $box[$j]; 
            $box[$j] = $tmp; 
            $result .= chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256])); 
        } 

        if($operation == 'D'){ 
            if(substr($result,0,8) == substr(md5(substr($result,8).$key),0,8)){ 
                return substr($result,8); 
            } else { 
                return''; 
            } 
        } else { 
            return str_replace('=','',base64_encode($result)); 
        } 
    }

    /**
     * 加密/解密数据
     * @param string $string  原文或密文
     * @param string $operation ENCODE or DECODE
     * @return string 根据设置返回明文活密文
     */
    private static function authcode($string, $operation = 'DECODE' ) {

        $ckey_length = 4;  // 随机密钥长度 取值 0-32;

        $key = $this->_securekey;

        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
}