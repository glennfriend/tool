<?php

    function getList()
    {
        $items = array();
        foreach (glob("codes/*.html") as $file) {
            $names = explode(".", basename($file) );
            $name = $names[0];
            $items[] = array(
                'show'      => $name,
                'template'  => 'codes/'.$name.'.html',
                'data'      => 'codes/'.$name.'.json',
            );
        }
        return $items;
    }


//