<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame\utils;

class Image {

    private $char = NULL;
    private $image = NULL;

    /**
     * 获取字符
     */
    public function getChar(){
        return $this->char;
    }


    /**
     * 生成随机数
     * @param int $figurres 位数
     */
    public function makeChar($figures = 4){
        $data       = OURS_CHARSET;
        $char = '';
        for ($i=0; $i < $figures; $i++) { 
            $char   .= substr($data,rand(0,strlen($data)-1),1);
        }
        $this->char = $char;
        return $char;   
    }

    /**
     * 生成验证码
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $num 字数数 
     */
    public function captcha($width = 100, $height = 30, $num = 4){//$width = 100, $height = 30


        $image      = imagecreatetruecolor($width, $height);
        $bgcolor    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgcolor);

        $char = $this->makeChar($num);

        for( $i=0; $i<$num; $i++ ){

            $fontstyle = 5;//mt_rand(1,5);
            $fontcolor  = imagecolorallocate($image,rand(5,120),rand(5,120),rand(5,120));
            $data       = OURS_CHARSET;
            $content    = substr($char, $i, 1);
    

            $x  = ($i*($width/$num)) + rand(1,$width/($num+2));
            $y  = rand($height/4, $height/3);

            imagestring($image, $fontstyle, $x, $y, $content, $fontcolor);
        }

        //随机点，生成干扰点
        for( $i=0; $i<100; $i++ ){
            
            $pointcolor = imagecolorallocate($image,rand(50,120),rand(50,120),rand(50,120));
            imagesetpixel($image,rand(1,$width),rand(1,$height),$pointcolor);
        }

        //随机线，生成干扰线
        for( $i=0; $i<3; $i++ ){

            $linecolor = imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
            imageline($image,rand(1, $width),rand(1,$height),rand(1,$width),rand(1,$height),$linecolor);
        }
        $this->image = $image;
    }


    public function output(){
        header("content-type:image/png");
        imagepng($this->image);
        imagedestroy($this->image);
    }

}