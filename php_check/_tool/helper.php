<?php

set('get_defined_vars', get_defined_vars() ); //先讀取使用過的變數
init();
return;


function output( $vars )
{
    showFinal();

    echo "<br />\n";
    echo "================================================================================";
    echo "<br />\n";

    $originVars = get('get_defined_vars' );
    $diffs = array_diff( $vars, $originVars );
    echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
    var_export( $diffs );
    echo "</pre>\n";

    echo "================================================================================";
    echo "<br />\n";
    highlight_file_especial( get('phpFile') );
}

function show( $result )
{
    $valueBuffer = get('valueBuffer');
    if ( !is_array($valueBuffer) ) {
        $valueBuffer = array();
    }
    $valueBuffer[] = $result;

    set('valueBuffer', $valueBuffer );
}

function showFinal()
{
    $valueBuffer = get('valueBuffer');
    if ( !$valueBuffer ) {
        return;
    }

    echo '<ol>';
    foreach ( $valueBuffer as $value ) {
        echo '<li>'. $value . '</li>';
    }
    echo '</ol>';
}







function highlight_file_especial( $filePath, $key='////' )
{
    //echo $filePath;
    $content = file_get_contents($filePath);
    $code = '';
    foreach ( explode("\n",$content) as $line ) {
        if( stristr($line, $key) ) {
            continue;
        }
        $code .= $line . "\n";
    }
    echo highlight_string($code, true);
}

function get( $key )
{
    if ( !isset($_SERVER['custom']) ) {
        return null;
    }
    if ( !isset($_SERVER['custom'][$key]) ) {
        return null;
    }
    return $_SERVER['custom'][$key];
}

function set( $key, $value )
{
    $_SERVER['custom'][$key] = $value;
}

function init()
{
    //
    header("Content-Type:text/html; charset=utf-8");
    error_reporting(E_ALL);
    ini_set('html_errors','On');
    ini_set('display_errors','On');
    
    //
    // register_shutdown_function("shutdown_callback");

    // get entry php file
    $traceInfo = debug_backtrace();
    $traceInfoCount = count($traceInfo);
    set('phpFile', $traceInfo[ $traceInfoCount-1 ]['file'] );

}

