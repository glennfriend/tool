<?php

date_default_timezone_set('Asia/Taipei');
ini_set( 'date.timezone', 'Asia/Taipei');

echo '<script type="text/javascript">'
   . file_get_contents( __DIR__ .'/../public/dist/livejs/live.js')
   . '</script>';

$length = ob_get_length();
$last_modified = date ("F d Y H:i:s", getlastmod());
header("Content-Length: $length");
header("Last-Modified: $last_modified GMT time");


//