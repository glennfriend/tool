<?php

    

    $pages = [
        ['常用功能 group & properties show','group/'],
        ['js beautify','js/js_beautify/'],
        ['js compressor','js/js_compressor/'],
        ['jquery selectors example','jquery_selectors'],
        ['css format','css/format/'],
        ['css sample','css/sample/'],
        ['html unicode tool','word_to_unicode.htm'],
        ['php check','php_check/'],
        ['ip check','ip_qqwry/qqwry.php?ip=59.121.119.9'],
        ['database show','show_database/'],
        ['_preg_match example','re/'],
        ['',''],
        ['',''],
        ['',''],
        ['',''],
        ['',''],
    ];

    foreach ( $pages as $index => $page ) {
        $pages[$index] = [
            'name' => $page[0],
            'url'  => $page[1],
        ];
    }

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-tw" />
</head>
<body>
    <ul><?php
        foreach ( $pages as $page ) {
            if ( !$page['name'] ) {
                continue;
            }
            echo <<<EOD
                <li><a href="{$page['url']}">{$page['name']}</a></li>
EOD;
        }
    ?></ul<
    
</body>
</html>