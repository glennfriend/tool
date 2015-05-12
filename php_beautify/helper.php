<?php
    
    function getContent()
    {
        $sessionId = 'sess_' . session_id();
        $file = DIR_PATH . '/tmp/' . $sessionId;
        return file_get_contents($file);
    }

    function setContent( $text )
    {
        $sessionId = 'sess_' . session_id();
        $file = DIR_PATH . '/tmp/' . $sessionId;
        file_put_contents($file, $text);
    }

    function runCommand( $type='default' )
    {
        $sessionId = 'sess_' . session_id();
        $file = DIR_PATH . '/tmp/' . $sessionId;
        system("php-cs-fixer fix --level=psr2 --fixers=lowercase_constants {$file} > /dev/null 2>&1" );
        // system("php-cs-fixer fix --fixers=unused_use,phpdoc_params,extra_empty_lines,controls_spaces,indentation,trailing_spaces,elseif {$file} > /dev/null 2>&1" );
    }

/*
    all
        unused_use
        phpdoc_params
        extra_empty_lines
        controls_spaces

    psr2
        indentation
        trailing_spaces
        elseif
*/

