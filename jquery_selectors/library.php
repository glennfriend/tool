<?php

function outputHtml( $html )
{
    $output = '';
    $lines = explode("\n",$html);
    foreach ( $lines as $line ) {
        preg_match('/\[.+\]/', $line, $matches );
        $baseContent = $matches[0];

        $innerHTML = str_replace($baseContent, substr($baseContent,1,-1), $line );
        $innerHTML = htmlspecialchars($innerHTML);
        $innerHTML = str_replace(' ', '&nbsp;', $innerHTML );

        if ( $baseContent ) {
            $item = str_replace($baseContent, '***', $line );
            $item = str_replace('***', $innerHTML, $item );
        }
        else {
            $item = $line . $innerHTML . "<br />";
        }

        $output .= $item . "\n";
    }
    return $output;
}

