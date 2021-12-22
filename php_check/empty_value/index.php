<?php
    require_once '../_tool/helper.php'; ////
    error_reporting( E_ALL & ~E_NOTICE );
    //// start ////
?>
    <table border="1">
    <tr>
        <td></td>
        <td> gettype()  </td>
        <td> empty()    </td>
        <td> isset()    </td>
        <td> is_bool()  </td>
        <td> is_array() </td>
        <td> is_null()  </td>
        <td> ===null    </td>
    </tr>
<?php

    $data0;
    $data1 = null;
    $data2 = "";
    $data3 = FALSE;
    $data4 = array();
    $data5 = "\n";

    $count=5;
    for( $i=0; $i<=$count; $i++ )
    {
        if    (0==$i) echo '<tr><td> $d             </td>';
        elseif(1==$i) echo '<tr><td> $d = null      </td>';
        elseif(2==$i) echo '<tr><td> $d = ""        </td>';
        elseif(3==$i) echo '<tr><td> $d = false     </td>';
        elseif(4==$i) echo '<tr><td> $d = array()   </td>';
        elseif(5==$i) echo '<tr><td> $d = "\n"      </td>';
        else          break;

            $data='data'.$i;
            echo '<td>'.  gettype($$data) .'</td>';
            echo '<td>'.    empty($$data) .'</td>';
            echo '<td>'.    isset($$data) .'</td>';
            echo '<td>'.  is_bool($$data) .'</td>';
            echo '<td>'. is_array($$data) .'</td>';
            echo '<td>'.  is_null($$data) .'</td>';
            echo '<td>'.  ($$data===null) .'</td>';

        echo '</tr>';
    }
    echo '</table>';

    //// end ////
    output(get_defined_vars()); ////
