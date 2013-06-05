<?php
$c[0] = $_GET['c1'] ;
$c[1] = $_GET['c2'] ;
$c[2] = $_GET['c3'] ;

$b[]=$c[0].$c[1].$c[2];
$b[]=$c[0].$c[2].$c[1];
$b[]=$c[1].$c[0].$c[2];
$b[]=$c[1].$c[2].$c[0];
$b[]=$c[2].$c[0].$c[1];
$b[]=$c[2].$c[1].$c[0];



?>
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body style='font-family:"Courier New";'>
<form name="form1" method="get" action="" >
    顏色組成: <br />
    <input type="text" name="c1" value="ab" size="2" maxlength="2" /><br />
    <input type="text" name="c2" value="cd" size="2" maxlength="2" /><br />
    <input type="text" name="c3" value="ef" size="2" maxlength="2" /><br />
    <input type="submit" />
</form>
<?php

$show='';
if( $_GET ) {
    foreach( $b as $number=>$color ) {
        $show .= '<tr><td bgcolor="'. $color .'">&nbsp;</td><td width="1">'. $color .'</td></tr>';
    }
    echo '<table width="320" border="0">'.$show.'</table>';
}


//