<?php

$path = "sample/*";
$collect = array();

foreach ( glob($path) as $dir ) {
    // $fileInfo = pathinfo($dir);
    $fileInfo = my_path_info($dir);
    $collect[] = $fileInfo['filename'];
}

echo join($collect,",");




function my_path_info($filepath) {
    $path_parts = array();
    $path_parts['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";
    $path_parts['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");
    $path_parts['extension'] = substr(strrchr($filepath, '.'), 1);
    $path_parts['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");
    return $path_parts;
}

