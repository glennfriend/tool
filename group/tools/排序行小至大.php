<?php

class ToolObject extends ToolBaseObject
{

    public function init()
    {
        if( !$this->getText() ) {
            $this->setText( "3\n5\n1\n2\n4" );
        }
    }

    function run()
    {
        $text = $this->getText();

        $lines = explode("\n",$text);
        asort($lines);
        $text = join($lines,"\n");

        $this->setText( $text );
    }

}


//