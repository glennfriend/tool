<?php
    
    function getContent()
    {
        $sessionId = session_id();
        $file = 'node/tmp/content.txt';
        return file_get_contents($file);
    }

    function setContent( $text )
    {
        $sessionId = session_id();
        $file = 'node/tmp/content.txt';
        file_put_contents($file, $text);
    }

    function runCommand( $type='default' )
    {
        $sessionId = session_id();
        $file = 'node/tmp/content.txt';
        system("cd node; grunt pretty  > /dev/null 2>&1" );
    }

