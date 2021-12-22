<?php
    require_once '../_tool/helper.php'; ////
    //// start ////

    $val1 = md5('240610708');
    $val2 = md5('QNKCDZO');

    $isEqual1 = ( $val1 == $val2 );
    $isEqual2 = ( $val1 === $val2 );

    //// end ////
    output(get_defined_vars()); ////
?>