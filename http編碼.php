<?
$http = "http://tw.yahoo.com.tw/index.htm?aaa=111&bbb=222&ccc=333";
echo "<BR>".$http."<BR>";

echo "<BR> base64_encode(編碼)  base64_decode(解碼)";
$a = base64_encode($http);
echo "<BR>".$a;
$b = base64_decode($a);
echo "<BR>". $b;

echo "<br>";
echo "<BR> rawurlencode(編碼)  rawurldecode(解碼)";
$a = rawurlencode($http);
echo "<BR>".$a;
$b = rawurldecode($a);
echo "<BR>". $b;

echo "<br>";
echo "<BR> urlencode(編碼)  urldecode(解碼)";
$a = urlencode($http);
echo "<BR>".$a;
$b = urldecode($a);
echo "<BR>". $b;

?>