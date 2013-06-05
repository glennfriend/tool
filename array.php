<?php
echo "<pre>一維陣列的應用\n";

$d = array(10=>'a', 1=>'b', 2=>22, 3=>33, 14=>'e', 5=>'f', 6=>'h', 0=>'a' );
/*
$d = array(0=>'a', 1=>'b', 2=>'c', 3=>'d', 14=>'e', 5=>'f', 6=>'h', 10=>'a' ,
           array(4=>'xxxx', 5=>'xxxxx')
          );
*/

show('print_r($d);');
print_r($d);

show('print_r(array_chunk($d, 3)) - 順序分組 ');
print_r(array_chunk($d, 3));

show('print_r(array_chunk($d, 3, true)) - 原key順序分組 ');
print_r(array_chunk($d, 3, true));

show('print_r(array_count_values($d)) - 數量count');
print_r(array_count_values($d));

show('print_r(array_flip($d)) - 元素相反, ※後面的值會蓋掉前面的值 ');
print_r(array_flip($d));

show('array_push($d,"aaa","bbb","ccc") - array push , 從最大的key之後開始加 ');
$tmp=$d;
array_push($tmp,"aaa","bbb","ccc");
print_r($tmp);

show('echo array_search("a", $d) - a 在 0 與 10 , array_search 會找到第一個值 ');
echo array_search("a", $d)."\n";

show('echo array_sum($d) - 計算值的加總 ');
echo array_sum($d)."\n";

show('print_r(array_unique($d)) - 除了第一個值之外,拿掉所有重覆的值 ');
print_r(array_unique($d));

show('print_r(array_keys($d)) - 留下key轉而成為value, key換成順序數字 ');
print_r(array_keys($d));

show('print_r(array_values($d)) - 將key換成順序數字 ');
print_r(array_values($d));

show('echo  array_key_exists("14",$d); - 陣列是否有這個 key ');
echo array_key_exists("14",$d);
echo '<br>';

show('echo current($d) - 現在');
echo current($d);
show('echo next($d) - next');
echo next($d);
show('echo next($d)');
echo next($d);
show('echo pos($d) - 現在 ');
echo pos($d);
show('echo prev($d) - prev');
echo prev($d);
show('echo end($d) - end ');
echo end($d);
show('echo prev($d)');
echo prev($d);
show('echo reset($d) - first ');
echo reset($d);



function show($str)
{
    echo "\n<font size=2 color=ff0000>".$str."</font>\n";
}
?>