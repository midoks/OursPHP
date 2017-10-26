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

    /**
     *  生成随机数
     */
    public function makeChar($figures = 4){
        $data       = OURS_CHARSET;
        $char = '';
        for ($i=0; $i < $figures; $i++) { 
            $char   .= substr($data,rand(0,strlen($data)),1);
        }
        return $char;   
    }

    /**
     * 生成验证码
     */
    public function captcha(){

        $image      = imagecreatetruecolor(100,30);
        $bgcolor    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image,0,0,$bgcolor);

        $captch_code = '';

        for( $i=0; $i<4; $i++ ){

            $fontsize   = 6;
            $fontcolor  = imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
            $data       = OURS_CHARSET;
            $fontcontent=substr($data,rand(0,strlen($data)),1);
            $captch_code .= $fontcontent;

            $x  = ($i*100/4)+ rand(5,10);
            $y  = rand(5,10);

            imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
        }

        //随机点，生成干扰点
        for( $i=0; $i<200; $i++ ){
            
            $pointcolor = imagecolorallocate($image,rand(50,120),rand(50,120),rand(50,120));
            imagesetpixel($image,rand(1,99),rand(1,99),$pointcolor);
        }

        //随机线，生成干扰线
        for( $i=0; $i<3; $i++ ){

            $linecolor = imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
            imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linecolor);
        }

        header("content-type:image/png");
        imagepng($image);
        imagedestroy($image);
    }

    public function out(){

    }

}