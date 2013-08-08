<?php
ini_set("arg_seperator.output", "&amp;");
ini_set("magic_quotes_runtime", 0 );
ini_set("magic_quotes_gpc", true );
require_once("css_inline.php");
require_once("css_formatter.php");



$s = $_POST['s'];
$s2 = css_inline($s);
$s3 = css_formatter($s2);
if( ''==$s ) {
    $s=<<<EOD
#lo .row p {
    text-align:center;
    font-size:123.1%;
    margin:50px;
}
#mb .bd .badge th,
#mb .bd .badge td {padding:5px;}
EOD;
}
?>
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="/brc/css/basic.css" >
<body style='font-family:"Courier New";'>
<form name="form1" method="post" action="<?=basename($_SERVER['SCRIPT_NAME'])?>" >
    <textarea name="s" cols="80" rows="12"><?=$s?></textarea>
    <input type="submit" />
</form>
<pre><hr><?php
    print_r($s2);
    echo '<hr />';
    print_r($s3);
?>