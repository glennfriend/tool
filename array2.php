<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-tw" />
<?php
echo "<pre>兩個一維陣列的應用\n";

$d = array(10=>'a', 1=>'b', 2=>22, 3=>33, 14=>'e', 6=>'h', 0=>'a' );

$e = array(0=>'a', 1=>'b', 2=>'c', 3=>'d', 14=>'e' );


show('print_r($d);');
print_r($d);

show('print_r($e);');
print_r($e);


show('print_r(array_intersect($d, $e)); - 取出陣列中相同的元素值 ');
print_r(array_intersect($d, $e));

show('print_r(array_merge($d, $e)); - 合併多個陣列 (key被平面化,value不會被覆蓋) ');
print_r(array_merge($d, $e));

show('print_r(array_merge_recursive($d, $e)); - 用遞迴的方式合併多個陣列 (key被平面化,value不會被覆蓋) ');
print_r(array_merge_recursive($d, $e));












function show($str)
{
    echo "\n<font size=2 color=ff0000>".$str."</font>\n";
}
?>