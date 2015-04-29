<?php

function getCodeLinesByHtml( $html, $isOutputColor=true )
{
    if ( $isOutputColor ) {
        $html = "<"."?php\n". $html. "\n?>";
        $html = highlight_string($html,true);
        $tmp = explode("\n",$html);
        $colorHtml = $tmp[1];
        $lines = explode('<br />',$colorHtml);
        unset( $lines[ count($lines)-1 ] );
        unset( $lines[0] );
        return array_values($lines);
    }
    else {
        $lines = explode("\n",$html);
        return $lines;
    }
}

function outputHtml( $originHtml )
{

    $colorLines = getCodeLinesByHtml( $originHtml, true  );
    $htmlLines  = getCodeLinesByHtml( $originHtml, false );

    $len = count($colorLines);
    if ( $len != count($htmlLines) ) {
        return 'parse html line error';
    }

    $output = '';
    $lines = explode("\n",$html);
    for ( $i=0 ; $i<$len; $i++ ) {
        preg_match('/\[.+\]/', $htmlLines[$i], $matches );
        $baseContent = $matches[0];

        if ( $baseContent ) {
            $item = str_replace($baseContent, $colorLines[$i], $htmlLines[$i] );
            $item = str_replace('[', '', $item );
            $item = str_replace(']', '', $item );
        }
        else {
            $item = $htmlLines[$i] . $colorLines[$i] . "<br />";
        }

        $output .= $item . "\n";
    }
    return $output;
}

