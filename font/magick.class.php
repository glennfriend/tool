<?php
/*
image_magick�B�z�ϫ���class
v0.0.01  --  �U�� 04:51 2005/11/19
v0.1.01  --  �W�� 11:46 2006/1/12 
v0.1.02  --  �U�� 03:02 2006/4/11   --  �ק�@�Ǥp�a��
v0.1.11  --  �U�� 03:25 2006/4/11   --  �s�W�\�h�\��
v0.1.12  --  �U�� 04:03 2007/6/23   --  ��r�ԭz

�`�N:
        �p�G�O�ϥε����D���A�i�� is_dir() ���\��|�Q�����A�ЯS�O�`�N.

�`�N~ ���{���N�n�ק�:
      ��X�Ϥ��ɪ����p: convert input.jpg -geometry 100x100  output.jpg
      ��X�G�i�쪺���p: convert input.jpg -geometry 100x100  jpg:-
      ��X�覡�G�i�쪺����覡:
        header('Content-Type: image/jpg');
        system( 'convert input.jpg -geometry 100x100  jpg:-');


�ϥΤ覡:

include_once 'magick.class.php';
$im = new magick('D:/wsystem/Apache/_tool/ImageMagick');

$msg = $im->small_size( '1.gif', '2.gif', 80, 60 );                      //�Y��
$msg = $im->change_size( '1.gif', '2.gif', 80, 60 );                     //��ϩ��Y��
$msg = $im->add_area_text( '1.gif', '2.gif', 'hello', '#abcdef', 'up');  //�s�W�Ϧ���P��r 
$msg = $im->line4('1.gif','2.gif',1,'#000000');                          //�Ϥ��[��
$msg = $im->blur('1.gif','2.gif',3);                                     //�U�k
$msg = $im->sharpen('1.gif','2.gif',3);                                  //�M�R
$msg = $im->paint('1.gif','2.gif',2);                                    //ø�e
$msg = $im->crop('1.gif','2.gif','left',30,true);                        //����
$msg = $im->flip('1.gif','2.gif')                                        //����½�� 
$msg = $im->flop('1.gif','2.gif')                                        //����½�� 
$msg = $im->rotate('1.gif','2.gif',180 )                                 //����

if(0!=$msg)
{
    $msg = ' �ϧξާ@����~ ';
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
    // �غc�l
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

    //�x�s���ɪ��@���{�ɸ�T
    //�����~�|������ܿ��~�T��
    function _image_info( $img='' )
    {
        if(!file_exists($img))
        {
            die(' magick - �d�L������! ');
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

    //���ɵ���ҩ�j�Y�p
    //��ϦW,�s�ϦW,�e,��
    function change_size($img,$imgout,$w,$h)
    {
        $w=intval($w);
        $h=intval($h);

        if(0==$w or 0==$h) {
            return false;
        }

        return $this->_run('convert -geometry '.$w.'x'.$h." $img $imgout ");
    }


    //���ɵ�����Y�p
    //�p�G�s�Ϥ��Ϥj,����̤j�u�୭�����ϦP�ˤj�p
    //��ϦW,�s�ϦW,�e,��
    function small_size($img,$imgout,$w,$h)
    {
        $w=intval($w);
        $h=intval($h);
        $info = $this->_image_info($img); //���o���ɸ�T,

        if( $w>$info['width']  or  $w<=0 )  $w=$info['width'] ;
        if( $h>$info['height'] or  $h<=0 )  $h=$info['height'];

        return $this->_run('convert -geometry '.$w.'x'.$h." $img $imgout ");
    }


    //�s�W����P�~�����^��r�b�Ϥ�����
    // area='down' ����s�W�b�U�� 
    // area='up'   ����s�W�b�W�� 
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


    //�Ϥ��[��
    // $point = 1          �ت��e�� 
    // $color = "#abcdef"  �C�� 
    function line4($img,$imgout,$point=1,$color='#ffffff')
    {
        return $this->_run("montage -background $color -geometry +$point+$point $img $imgout");
    }


    //�U�k 
    function blur($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -blur 0x$power $imgout");
    }


    //�M�R 
    function sharpen($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -sharpen 0x$power $imgout");
    }


    //ø�e 
    function paint($img='1.gif',$imgout='2.gif',$power=1)
    {
        $power=(int)$power;
        return $this->_run("convert $img -paint $power $imgout");
    }


    //����
    // $crop_area = �����Ӧa�� , up down left right 
    // $long      = ���h��(����) (int)
    // $can_null  = �u������(true), �٬O������O�d���z����(false) 
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

        $info = $this->_image_info($img); //���o���ɸ�T,
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


    //����½�� 
    function flip($img='1.gif',$imgout='2.gif')
    {
        return $this->_run("convert $img -flip $imgout");
    } 

    //����½�� 
    function flop($img='1.gif',$imgout='2.gif')
    {
        return $this->_run("convert $img -flop $imgout");
    } 

    //����
    function rotate( $img , $imgout , $power=0 )
    {
        return $this->_run("convert -rotate $power $img $imgout ");
    } 


    //gamma
    //���� 1.0 ���[�G , �C�� 1.0 ���[�` ????
    function gamma( $img , $imgout , $power='3.0' )
    {
        return $this->_run("convert -gamma $power $img $imgout ");
    } 


    //�e���~��, �A�Ω� jpg �榡
    //�� 65 �H�U�٬O���i��X�Ӫ��ɮפj�p���Ӫ��٤j
    // 1 - 100
    function quality( $img , $imgout , $power='65' )
    {
        return $this->_run("convert -quality $power $img $imgout ");
    } 


    /*
    //�N�ϰ��W�U�B���k���ü��ഫ
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
    //�X�� 
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

image magick create ����

convert -background lightblue -fill blue -font SimSun -pointsize 48 label:@chinese_words.utf8 \
label_utf8.gif

convert -background lightblue -fill blue -font SimSun -pointsize 48 label:'�Y�H���ᮧ�a' \
label_utf8.gif



*/
?>