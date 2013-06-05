<?php

class ToolObject extends ToolBaseObject
{

    public function init()
    {
        if( !$this->getText() ) {
            $this->setText( time() );
        }
    }

    public function run()
    {
        $text = $this->getText();

        $myDate = date("Y-m-d(w) H:i:s",$text);

        $this->setBeforeText( $myDate );
    }

}

//