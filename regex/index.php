<?php

$d=array(
    array('t'=>'preg_replace',  'n'=>'移除開頭數字',        'e'=>'/^[0-9]+/'                                                                ),
    array('t'=>'preg_replace',  'n'=>'移除數字',            'e'=>'/[0-9]/'                                                                  ),
    array('t'=>'preg_replace',  'n'=>'以,做為分隔符號',     'e'=>'/,(?=,)/'                                                                 ),
    array('t'=>'preg_replace',  'n'=>'只留下英文數字',      'e'=>'#[^A-Za-z0-9]#'                                                           ),

    array('t'=>'preg_match',    'n'=>'開頭為特定文字',      'e'=>'/^abc.{1,}$/'                                                             ),
    array('t'=>'preg_match',    'n'=>'首字大英',            'e'=>'/^[A-Z]/'                                                                 ),
    array('t'=>'preg_match',    'n'=>'個位數字',            'e'=>'/^[0-9]$/'                                                                ),
    array('t'=>'preg_match',    'n'=>'大寫英文',            'e'=>'/^[A-Z]{1,}$/'                                                            ),
    array('t'=>'preg_match',    'n'=>'白字符',              'e'=>'/^[ \f\r\t\n]{1,}$/'                                                      ),
    array('t'=>'preg_match',    'n'=>'所有正數',            'e'=>'/^[0-9]{1,}$/'                                                            ),
    array('t'=>'preg_match',    'n'=>'所有整數',            'e'=>'/^-{0,1}[0-9]{1,}$/'                                                      ),
    array('t'=>'preg_match',    'n'=>'所有小數',            'e'=>'/^-{0,1}[0-9]{0,}.{0,1}[0-9]{0,}$/'                                       ),
    array('t'=>'preg_match',    'n'=>'帳號(6-18)',          'e'=>'/^[a-zA-Z][A-Za-z0-9_]{5,17}$/'                                           ),
    array('t'=>'preg_match',    'n'=>'身份証初驗',          'e'=>'/^[A-Z][01][0-9]{8}$/'                                                    ),
    array('t'=>'preg_match',    'n'=>'伊沒有',              'e'=>'/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/'                        ),
    array('t'=>'preg_match',    'n'=>'匹配HTML(內含會錯)',  'e'=>'/^<(.*)>.*<\/\1>|<(.*) \/>$/'                                             ),
    array('t'=>'preg_match',    'n'=>'字母1_or_數字無限',   'e'=>'/^[a-zA-Z]$|^\d+$/'                                                       ),
    array('t'=>'preg_match',    'n'=>'email',               'e'=>'/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9_\.\-]+\.[A-Za-z0-9_\-][A-Za-z0-9_\-]+$/'   ),
    array('t'=>'preg_match',    'n'=>'中文',                'e'=>'/^[u4e00-u9fa5]+$/'                                                       ),
    array('t'=>'preg_match',    'n'=>'雙字元(錯)',          'e'=>'/[^\x00-\xff]{1,}$/'                                                      ),

);
//pr($d,1);


?>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<style>
    body,td,a { font-family: 細明體; font-size: 12pt; }
    input     { font-family: 細明體; font-size: 8pt; border: 1px solid #888; }
    td:nth-child(2) { background-color:#cdefab; }
    td:nth-child(3) { background-color:#eeeeee; width:10px; }
    td:nth-child(4) { background-color:#abefcd; }
    td:nth-child(5) { background-color:#dddddd; }
    .remove { color:#ff0000; }
</style>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.js'></script>
<script type="text/javascript">

function tester(id,t,e,v) {

    $.ajax({
        type: "POST",
        url: "re_ajax.php",
        data: "id="+id + "&t="+t + "&e="+encodeURIComponent(e) + "&v="+v ,
        success: function(msg){
            document.getElementById(id).innerHTML = msg; 
        }
    });

}

</script>
<body>
<?php



//run data - 變數名稱,結果
foreach ( $d as $index => $item ) {

    $item['id'] = 'tag_' . $index;
    $postId = '';
    if (isset($_POST[$item['id']])) {
        $postId = $_POST[$item['id']];
    }
    
    if ('preg_replace'==$item['t']) {

        $item['show']=preg_replace( $item['e'] , '' , $postId );

    }
    elseif ('preg_match'==$item['t']) {

        $item['show']=preg_match( $item['e'] , $postId );

    }
    else {
        echo 'error';
        continue;
    }
    $d[$index] = $item;
}


//run screen
//echo '<pre>'; print_r($d);

echo "<table>";
foreach ( $d as $index => $item ) {

    $id = '';
    if (isset($item['id'])) {
        $id = $item['id'];
    }

    $show = '';
    if (isset($item['show'])) {
        $show  = $item['show'];
    }

    $type  = $item['t'];
    $er = $item['e'];
    echo <<<EOD
        <tr>
            <td><input onkeydown='if(event.keyCode==13){ tester("{$id}","{$type}","{$er}",this.value ); }' ></td>
            <td>{$er}</td>
            <td><div id='{$id}'>{$show}</div></td>
            <td>{$item['n']}</td>
            <td>{$type}</td>
        </tr>
EOD;
}


?>