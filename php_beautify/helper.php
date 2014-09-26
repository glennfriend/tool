<?php
    
    function getContent()
    {
        $sessionId = session_id();
        $file = 'tmp/' . $sessionId;
        return file_get_contents($file);
    }

    function setContent( $text )
    {
        $sessionId = session_id();
        $file = 'tmp/' . $sessionId;
        file_put_contents($file, $text);
    }

    function runCommand( $type='default' )
    {
        $sessionId = session_id();
        $file = 'tmp/' . $sessionId;
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

