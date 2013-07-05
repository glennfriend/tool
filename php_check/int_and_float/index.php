<?php
    require_once '../_tool/helper.php'; ////
    //// start ////

    // 8
    $value1 = (0.1+0.7) * 10;
    
    // 7.9999999999999991118
    $value2 = floor( (0.1+0.7) * 10 );
    
    // 7
    $value3 = (int) ( (0.1+0.7) * 10 );

    //// end ////
    output(get_defined_vars()); ////
?>