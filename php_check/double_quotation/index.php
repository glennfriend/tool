<?php
    require_once '../_tool/helper.php'; ////
    //// start ////

    $char1 = '"';
    $char2 = '&#65279;"';

    $result1 = strlen( $char1 );
    $result2 = strlen( $char2 );

    $isEqual = ( $char1 == $char2 );

    //
    echo phpversion();

    //// end ////
    output(get_defined_vars()); ////
?>