<?php

$path = "sample/*";
$collect = array();

foreach ( glob($path, GLOB_ONLYDIR) as $dir ) {
    $collect[] = basename($dir);
}

// echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
// print_r( $collect );
// echo "</pre>\n";

// echo json_encode($collect);

echo join($collect,",");
