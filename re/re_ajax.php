<?php /* 請使用 utf-8 格式配合原本的呼叫程式 */
if( !$_POST['id'] || !$_POST['t'] || !$_POST['e'] ) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {

    switch($_POST['t']) {
        case 'preg_replace':
            $show=preg_replace( $_POST['e'] , '<span class="remove">-</span>' , $_POST['v'] );
            break;
        case 'preg_match':
            $show=preg_match( $_POST['e'] , $_POST['v'] );
            break;
        default:
            $show="error";
            break;
    }

}

echo $show;
?>