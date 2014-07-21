<?php
    require_once '../_tool/helper.php'; ////
    //// start ////

    $data = [1, 2, 3];

    //
    echo implode(',', $data), "<br />\n";

    // by reference
    foreach ($data as &$value) {}
    echo implode(',', $data), "<br />\n";

    // by value (i.e., copy)
    foreach ($data as $value) {}
    echo implode(',', $data), "<br />\n";

    //// end ////
    output(get_defined_vars()); ////
