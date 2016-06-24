<?php

class ToolObject extends ToolBaseObject
{

    public function init()
    {
        ini_set("arg_seperator.output", "&amp;");
        ini_set("magic_quotes_runtime", 0 );
        ini_set("magic_quotes_gpc", true );

        if( !$this->getText() ) {
            $this->setText( 'a:1:{i:0;O:8:"stdClass":3:{s:5:"domId";s:15:"control_tag_143";s:2:"id";s:3:"143";s:4:"name";s:12:"￣)︿(￣)";}}' );
        }
    }

    function run()
    {
        $text = $this->getText();
        $text = unserialize($text);

        $this->setBeforeText(
            '<pre>'
                . print_r($text, true)
                . json_encode($text, JSON_PRETTY_PRINT)
            .'</pre>'
        );
    }

}


//