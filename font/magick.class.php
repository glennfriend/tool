<?php
/*
image_magick處理圖型的class
v0.0.01  --  下午 04:51 2005/11/19
v0.1.01  --  上午 11:46 2006/1/12 
v0.1.02  --  下午 03:02 2006/4/11   --  修改一些小地方
v0.1.11  --  下午 03:25 2006/4/11   --  新增許多功能
v0.1.12  --  下午 04:03 2007/6/23   --  文字敘述

注意:
        如果是使用虛擬主機，可能 is_dir() 的功能會被關閉，請特別注意.

注意~ 本程式將要修改:
      輸出圖片檔的情況: convert input.jpg -geometry 100x100  output.jpg
      輸出二進位的情況: convert input.jpg -geometry 100x100  jpg:-
      輸出方式二進位的完整方式:
        header('Content-Type: image/jpg');
        system( 'convert input.jpg -geometry 100x100  jpg:-');


使用方式:

include_once 'magick.class.php';
$im = new magick('D:/wsystem/Apache/_tool/ImageMagick');

$msg = $im->small_size( '1.gif', '2.gif', 80, 60 );                      //縮圖
$msg = $im->change_size( '1.gif', '2.gif', 80, 60 );                     //闊圖或縮圖
$msg = $im->add_area_text( '1.gif', '2.gif', 'hello', '#abcdef', 'up');  //新增圖色塊與文字 
$msg = $im->line4('1.gif','2.gif',1,'#000000');                          //圖片加框
$msg = $im->blur('1.gif','2.gif',3);                                     //糢糊
$msg = $im->sharpen('1.gif','2.gif',3);                                  //清析
$msg = $im->paint('1.gif','2.gif',2);                                    //繪畫
$msg = $im->crop('1.gif','2.gif','left',30,true);                        //裁切
$msg = $im->flip('1.gif','2.gif')                                        //垂直翻轉 
$msg = $im->flop('1.gif','2.gif')                                        //水平翻轉 
$msg = $im->rotate('1.gif','2.gif',180 )                                 //旋轉

if(0!=$msg)
{
    $msg = ' 圖形操作失敗~ ';
    echo $msg;
}


*/
class magick
{

    //--------------------------------------------------------------------------------
    // private
    //--------------------------------------------------------------------------------

    var $_path ='';
    var $_debug=false;
    var $_error_num=99999;

    //--------------------------------------------------------------------------------
    // public
    //--------------------------------------------------------------------------------



    //--------------------------------------------------------------------------------
    // 建構子
    //--------------------------------------------------------------------------------
    function magick( $image_magick_path='' , $debug=false )
    {
        /*
        if(  ''!=$image_magick_path and is_dir($image_magick_path)  )  {
            $this->_path = $image_magick_path;
        } else {
            die(' magick class error - not path ');
        }
        */
        $this->_path = $image_magick_path;

        if($debug)  {
            $this->_debug=true;
        } else {
            $this->_debug=false;
        }

    }


    //--------------------------------------------------------------------------------
    // private function
    //--------------------------------------------------------------------------------

    //儲存圖檔的一些臨時資訊
    //有錯誤會直接顯示錯誤訊息
    function _image_info( $img='' )
    {
        if(!file_exists($img))
        {
            die(' magick - 查無此圖檔! ');
        }
        $info['time']   = filectime($img);
        $info['size']   = filesize($img);
        $tmp            = GetImageSize($img);
        $info['width']  = $tmp[0];
        $info['height'] = $tmp[1];
        $info['mime']   = $tmp[mime];

        if($this->_debug)
        {
            echo '<pre>'; print_r($info); echo '</pre>';
        }

        return $info;
    }

    function _run( $command='' )
    {
        $path = $this->_path;
        if( strlen($path)!='' and substr($path,(strlen($path)-1),1)!='/' ) {
            $path .= '/';
        }

        if('WINNT'==PHP_OS) system(  $path.$command,$out);
        else                passthru($path.$command,$out);

        if($this->_debug)
        {
            echo '['.PHP_OS.'] = '. $path.$command .'<br>';
            if($out)
            {
                echo '[err] = '.$out.'<br>';
            }
        }

        return $out;
    }


    //--------------------------------------------------------------------------------
    // public function
    //--------------------------------------------------------------------------------

    //圖檔等比例放大縮小
    //原圖名,新圖名,寬,長
    function change_size($img,$imgout,$w,$h)
    {
        $w=intval($w);
        $h=intval($h);

        if(0==$w or 0==$h) {
            return false;
        }

        return $this->_run('convert -geometry '.$w.'x'.$h." $img $imgout ");
    }


    //圖檔等比例縮小
    //如果新圖比原圖大,那麼最大只能限制到跟原圖同樣大小
    //原圖名,新圖名,寬,長
    function small_size($img,$imgout,$w,$h)
    {
        $w=intval($w);
        $h=intval($h);
        $info = $this->_image_info($img); //取得圖檔資訊,

        if( $w>$info['width']  or  $w<=0 )  $w=$info['width'] ;
        if( $h>$info['height'] or  $h<=0 )  $h=$info['height'];

        return $this->_run('convert -geometry '.$w.'x'.$h." $img $imgout ");
    }


    //新增色塊與居中的英文字在圖片之中
    // area='down' 色塊新增在下方 
    // area='up'   色塊新增在上方 
    function add_area_text($img='1.gif',$imgout='2.gif',$text='hello',$color='#abcdef',$area='down')
    {
        if('down'==$area) {
            return $this->_run("montage -geometry +0+0 -background $color -label \"$text\" $img $imgout");
        }
        elseif('up'==$area) {
            return $this->_run("convert $img -gravity North -background $color -splice 0x18 -draw \"text 0,0 '$text'\" $imgout");
        }
        else {
            return $this->_error_num;
        }
    }


    //圖片加框
    // $point = 1          框的寬度 
    // $color = "#abcdef"  顏色 
    function line4($img,$imgout,$point=1,$color='#ffffff')
    {
        return $this->_run("montage -background $color -geometry +$point+$point $img $imgout");
    }


    //糢糊 
    function blur($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -blur 0x$power $imgout");
    }


    //清析 
    function sharpen($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -sharpen 0x$power $imgout");
    }


    //繪畫 
    function paint($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -paint $power $imgout");
    }


    //裁切
    // $crop_area = 切那個地方 , up down left right 
    // $long      = 切多長(像素) (int)
    // $can_null  = 真的切掉(true), 還是切除後保留為透明圖(false) 
    function crop($img='1.gif',$imgout='2.gif',$crop_area='',$long=10,$can_null=true )
    {
        $long=(int)$long;
        $crop_area=strtolower($crop_area);

        if(0==$long) {
            return $this->_error_num;
        }

        switch($crop_area)
        {
            case 'up':
            case 'down':
            case 'left':
            case 'right':
                break;
            default:
                return $this->_error_num;
                break;
        }

        $info = $this->_image_info($img); //取得圖檔資訊,
        $width  = (int) $info['width'];
        $height = (int) $info['height'];

        if($can_null)
        {
            $crop_string=' +repage ';
        }

        switch($crop_area)
        {
            case 'up':
                        $height=$height-$long;
                        $height=(int)$height;
                        return $this->_run("convert $img -crop ".$width."x".$height."+0+$long $crop_string $imgout");
                        break;
            case 'down':
                        $height=$height-$long;
                        $height=(int)$height;
                        return $this->_run("convert $img -crop ".$width."x".$height."+0+0 $crop_string $imgout");
                        break;
            case 'left':
                        $width=$width-$long;
                        $width=(int)$width;
                        return $this->_run("convert $img -crop ".$width."x".$height."+$long+0 $crop_string $imgout");
                        break;
            case 'right':
                        $width=$width-$long;
                        $width=(int)$width;
                        return $this->_run("convert $img -crop ".$width."x".$height."+0+0 $crop_string $imgout");
                        break;
        }

    } 


    //垂直翻轉 
    function flip($img='1.gif',$imgout='2.gif')
    {
        return $this->_run("convert $img -flip $imgout");
    } 

    //水平翻轉 
    function flop($img='1.gif',$imgout='2.gif')
    {
        return $this->_run("convert $img -flop $imgout");
    } 

    //旋轉
    function rotate( $img , $imgout , $power=0 )
    {
        return $this->_run("convert -rotate $power $img $imgout ");
    } 


    //gamma
    //高於 1.0 為加亮 , 低於 1.0 為加深 ????
    function gamma( $img , $imgout , $power='3.0' )
    {
        return $this->_run("convert -gamma $power $img $imgout ");
    } 


    //畫面品質, 適用於 jpg 格式
    //調 65 以下還是有可能出來的檔案大小比原來的還大
    // 1 - 100
    function quality( $img , $imgout , $power='65' )
    {
        return $this->_run("convert -quality $power $img $imgout ");
    } 


    /*
    //將圖做上下、左右的亂數轉換
    function rand_move($img='1.gif',$imgout='2.gif')
    {
        srand((double)microtime()*6565656);
        
        for($i=0;$i<30;$i++)
        {
            $math1 = rand(1000,9999);
            $math2 = rand(1000,9999);
            $this->_run("convert $img -roll +$math1+$math2 $imgout");
        }
        return $this->_run("convert $img -roll +$math1+$math2 $imgout");
    } 
    */


    /*
    //合併 
    function unite($img='1.gif',$imgout='2.gif',area='2x2')
    {
        return $this->_run("montage p1.gif 2.gif 3.gif p1.gif 2.gif 3.gif p1.gif 2.gif 3.gif -tile 2x3 -geometry +2+2 4.gif");
    } 
    */

    /*
    //test
    function test()
    {
    } 
    */


}
/*

image magick create 中文

convert -background lightblue -fill blue -font SimSun -pointsize 48 label:@chinese_words.utf8 \
label_utf8.gif

convert -background lightblue -fill blue -font SimSun -pointsize 48 label:'某人的栖息地' \
label_utf8.gif



*/
?>