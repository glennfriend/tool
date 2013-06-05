<?PHP
/*
    顏色漸變計算、產生程式
    v0.0.1  --  下午 09:45 2007/6/13  --  create it 

    @param $color1 = 開始顏色
    @param $color2 = 結束顏色
    @param $counts = 漸變數量, 低於2將不會計算, 但是還是會傳回至少兩個顏色
    @return Array
    
    ex.
        $arr = color_blender( $color1='000000', $color2='ffffff', $counts=10 );
*/
function color_blender( $color1='000000' , $color2='ffffff', $counts=10 )
{

    //去 # 符號
    $color1 = strtolower(preg_replace("/#|\ /is","",$color1));
    $color2 = strtolower(preg_replace("/#|\ /is","",$color2));

    //最低陣列數量, 最高陣列數量 都只傳回兩個陣列值
    $counts=(int)$counts;
    if( $counts<=2 or $counts>100 ) {
        return Array($color1,$color2);
    }

    $arr   = Array();  // color buffer array
    $c1    = Array();   // color1  r,g,b data
    $c2    = Array();   // color2  r,g,b data
    $add   = Array();   // color   每單位增值空間 

    //拆解並分析顏色值
    $c1[0]=HexDec(substr($color1,0,2));
    $c1[1]=HexDec(substr($color1,2,2));
    $c1[2]=HexDec(substr($color1,4,2));
    $c2[0]=HexDec(substr($color2,0,2));
    $c2[1]=HexDec(substr($color2,2,2));
    $c2[2]=HexDec(substr($color2,4,2));
    //增值空間
    $add[0]=($c2[0]-$c1[0])/($counts-1);
    $add[1]=($c2[1]-$c1[1])/($counts-1);
    $add[2]=($c2[2]-$c1[2])/($counts-1);
    //pr($c1,1);
    //pr($c2,1);
    //pr($add,1);

    $arr[0]=$color1;
    for( $i=1 ; $i<($counts-1) ; $i++ ) {
        //echo ($c1[0]+($add[0]*$i)).' - '.($c1[1]+($add[1]*$i)).' - '.($c1[2]+($add[2]*$i)).'<br />';
        $arr[$i] = sprintf('%02s',DecHex(round($c1[0]+($add[0]*($i))))).
                   sprintf('%02s',DecHex(round($c1[1]+($add[1]*($i))))).
                   sprintf('%02s',DecHex(round($c1[2]+($add[2]*($i)))));
    }
    $arr[ ($counts-1) ]=$color2;

    return $arr;

}








/*
    顏色處理的函式
    v0.1  -  上午 10:25 2005/7/28

    $color 可以是 '#ff0000' 或 'ff0000'
    回傳也是會以一樣的格式 (傳回英文的小寫字串)(小寫是依 dechex() 函式[10轉16進位函式]決定)

    $r,$g,$b 必須為數字, 範圍為 -255 ~ 255

    注意: 當原本顏色值減去設定顏色值, 小於零時,  就會一直在零的狀態
          當原本顏色值相加設定顏色值, 大於255時, 就會一直在255的狀態

    ex.   $color='#996600';
          $color=color_cal($color,200,-7,200);
*/
function color_cal($obj_color='',$r=0,$g=0,$b=0)
{
    $min=-255;
    $max=255;
    $len=strlen($obj_color);

    $title=''; //傳入顏色字串如有 '#' 符號時, 傳出也要有這個符號
    if( 6==$len )
    {
        $color=$obj_color;
    }
    elseif( 7==$len )
    {
        if( '#'!=substr($obj_color,0,1) )
            die(' error - 顏色設定格式錯誤! ');
        $color=substr($obj_color,1,6);
        $title=substr($obj_color,0,1); //'#'
    }
    else
    {
        die(' error - 顏色設定格式錯誤!! ');
    }

    $now_r=hexdec(substr($color,0,2));
    $now_g=hexdec(substr($color,2,2));
    $now_b=hexdec(substr($color,4,2));

    //傳入的 rbg 值
    $now_r=(int)$now_r;
    $now_g=(int)$now_g;
    $now_b=(int)$now_b;
    if($now_r<0 or $now_r>$max) die(' error - 傳入顏色值 r 錯誤 ');
    if($now_g<0 or $now_g>$max) die(' error - 傳入顏色值 g 錯誤 ');
    if($now_b<0 or $now_b>$max) die(' error - 傳入顏色值 b 錯誤 ');

    //要設定的 rbg 值
    $r=(int)$r;
    $g=(int)$g;
    $b=(int)$b;
    if($r<$min or $r>$max) die(' error - 設定顏色值 r 錯誤 ');
    if($g<$min or $g>$max) die(' error - 設定顏色值 g 錯誤 ');
    if($b<$min or $b>$max) die(' error - 設定顏色值 b 錯誤 ');

    //retrun 0~255 的 rgb 值
    $return_r=$now_r+$r;    if($return_r<0)$return_r=0;    if($return_r>$max)$return_r=$max;
    $return_g=$now_g+$g;    if($return_g<0)$return_g=0;    if($return_g>$max)$return_g=$max;
    $return_b=$now_b+$b;    if($return_b<0)$return_b=0;    if($return_b>$max)$return_b=$max;

    //return '#ff0000' , 'ff0000' 兩種格式
    return $title.
           sprintf('%02s',dechex($return_r)).
           sprintf('%02s',dechex($return_g)).
           sprintf('%02s',dechex($return_b));

}


/*
這個函式的用途是將以 HSV 表示的顏色轉換成 RGB 色碼，演算法來自 Wikipedia 
目前網站的隨日期變色效果就是用此 function 來達成的。
$H 的範圍為 0–360 , $S 的範圍為 0–100 , $V 的範圍為 0–100
(本程式未經測試)
*/
function hsv2rgb($H, $S, $V)
{
    if ( $V == 0 )
    {
        $R = 0;
        $G = 0;
        $B = 0;
    }
    else if ( $S == 0 )
    {
        $V = $V / 100;
        $R = $V * 255;
        $G = $V * 255;
        $B = $V * 255;
    }
    else
    {
        //這一段是讓 $H 也能輸入小於 0 或大於 360 的數字，這樣用起來比較方便
        if ( $H > 360 ) {
            $H = $H % 360;
        } else if ( $H < 0 ) {
            $H = 360 + ( $H % 360 );
        }

        $S = $S / 100;
        $V = $V / 100;
        $Hf = $H / 60;
        $Hi = floor( $Hf );
        $f = $Hf - $Hi;
        $p = $V * ( 1 - $S );
        $q = $V * ( 1 - $S * $f );
        $t = $V * ( 1 - $S * ( 1 - $f ) );

        switch ( $Hi ) {
            case 0:
                $R = $V * 255;
                $G = $t * 255;
                $B = $p * 255;
                break;
            case 1:
                $R = $q * 255;
                $G = $V * 255;
                $B = $p * 255;
                break;
            case 2:
                $R = $p * 255;
                $G = $V * 255;
                $B = $t * 255;
                break;
            case 3:
                $R = $p * 255;
                $G = $q * 255;
                $B = $V * 255;
                break;
            case 4:
                $R = $t * 255;
                $G = $p * 255;
                $B = $V * 255;
                break;
            case 5:
                $R = $V * 255;
                $G = $p * 255;
                $B = $q * 255;
                break;
        }
    }

    return '#' . str_pad(dechex(round($R)), 2, "0", STR_PAD_LEFT) . str_pad(dechex(round($G)), 2, "0", STR_PAD_LEFT) . str_pad(dechex(round($B)), 2, "0", STR_PAD_LEFT);
}

?>