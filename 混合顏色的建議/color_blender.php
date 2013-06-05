<?php

require_once './lib/color.function.php';

$c1 = $_GET['c1'] ;
$c2 = $_GET['c2'] ;
$cs = (int) $_GET['cs'] ;
$color = color_blender( $c1,$c2,$cs );
//pr($color,1);



?>
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body style='font-family:"Courier New";'>
<form name="form1" method="get" action="color_blender.php" >
    顏色生成 <br />
    首色: <input type="text"  name="c1" value="000000" size="7" maxlength="7" /><br />
    尾色: <input type="text"  name="c2" value="FFFFFF" size="7" maxlength="7" /><br />
    數量: <input type="text"  name="cs" value="10"     /><br />
    <input type="submit" />
</form>
<?php

$show='';
if( $_GET ) {
    foreach( $color as $keys=>$vals ) {
        $show .= '<tr><td width="1">'. ($keys+1) .'</td><td bgcolor="'. $vals .'">&nbsp;</td><td width="1">'. $vals .'</td></tr>';
    }
    echo '<table width="320" border="0">'.$show.'</table>';
}

