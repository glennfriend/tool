<?php

$d=Array(
    Array('t'=>'preg_replace',  'n'=>'移除開頭數字',        'e'=>'/^[0-9]+/'                                                            ),
    Array('t'=>'preg_replace',  'n'=>'移除數字',            'e'=>'/[0-9]/'                                                              ),
    Array('t'=>'preg_replace',  'n'=>'分隔符號',            'e'=>'/,(?=,)/'                                                             ),


    Array('t'=>'ereg',          'n'=>'開頭為特定文字',      'e'=>'^abc.{1,}$'                                                           ),
    Array('t'=>'ereg',          'n'=>'首字大英',            'e'=>'^[A-Z]'                                                               ),
    Array('t'=>'ereg',          'n'=>'一數字',              'e'=>'^[0-9]$'                                                              ),
    Array('t'=>'ereg',          'n'=>'大英文',              'e'=>'^[A-Z]{1,}$'                                                          ),
    Array('t'=>'ereg',          'n'=>'中文(錯)',            'e'=>'^[\u4e00-\u9fa6]{1,}$'                                                ),
    Array('t'=>'ereg',          'n'=>'雙字元(錯)',          'e'=>'[^\x00-\xff]{1,}$'                                                    ),
    Array('t'=>'ereg',          'n'=>'白字符',              'e'=>'^[ \f\r\t\n]{1,}$'                                                    ),
    Array('t'=>'ereg',          'n'=>'所有正數',            'e'=>'^[0-9]{1,}$'                                                          ),
    Array('t'=>'ereg',          'n'=>'所有整數',            'e'=>'^-{0,1}[0-9]{1,}$'                                                    ),
    Array('t'=>'ereg',          'n'=>'所有小數',            'e'=>'^-{0,1}[0-9]{0,}.{0,1}[0-9]{0,}$'                                     ),
    Array('t'=>'ereg',          'n'=>'帳號(4-11)',          'e'=>'^[a-zA-Z_][A-Za-z0-9_]{3,11}$'                                        ),
    Array('t'=>'ereg',          'n'=>'簡略身份証',          'e'=>'^[A-Z][01][0-9]{8}$'                                                  ),
    Array('t'=>'ereg',          'n'=>'伊沒有',              'e'=>'^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$'                      ),
    Array('t'=>'ereg',          'n'=>'匹配HTML(內含會錯)',  'e'=>'^<(.*)>.*<\/\1>|<(.*) \/>$'                                           ),
    Array('t'=>'ereg',          'n'=>'字母1_or_數字無限',   'e'=>'^[a-zA-Z]$|^\d+$'                                                     ),
    Array('t'=>'ereg',          'n'=>'email',               'e'=>'^[A-Za-z0-9_\.\-]+@[A-Za-z0-9_\.\-]+\.[A-Za-z0-9_\-][A-Za-z0-9_\-]+$' ),
    Array('t'=>'ereg',          'n'=>'IP初判',              'e'=>'^[0-9]{1,3}\.[1-2]{0,1}\.[0-9]{1,3}\.[0-9]{1,3}$'                     ),

    Array('t'=>'preg_match',    'n'=>'中文',                'e'=>'/^[u4e00-u9fa5]+$/'                                                   )

);
//pr($d,1);


?>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<style><!--
body,td,a{font-family:細明體; font-size:12pt }
input    {font-family:細明體; font-size:8pt  ; border:1px solid #888;}
//-->
</style>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js'></script>
<script type="text/javascript">
// <!-- <![CDTA[




function tester(id,t,e,v) {

    $.ajax({
        type: "POST",
        url: "ereg_ajax.php",
        data: "id="+id+"&t="+t+"&e="+encodeURIComponent(e)+"&v="+v ,
        success: function(msg){
            document.getElementById(id).innerHTML = msg; 
        }
    });

}



// ]]> -->
</script>
<?php





//run data - 變數名稱,結果
//ereg
for($i=0;$i<count($d);$i++)
{
    if( 'preg_replace'==$d[$i]['t'] ) {
        $d[$i]['name']='x'.$i;
        $d[$i]['show']=preg_replace( $d[$i]['e'] , '' , $_POST[ $d[$i]['name'] ] );

    } elseif( 'ereg'==$d[$i]['t'] ) {
        $d[$i]['name']='x'.$i;
        $d[$i]['show']=ereg( $d[$i]['e'] , $_POST[ $d[$i]['name'] ] );

    } elseif( 'preg_match'==$d[$i]['t'] ) {
        $e[$i]['name']='y'.$i;
        $e[$i]['show']=preg_match( $d[$i]['e'] , $_POST[ $d[$i]['name'] ] );
        
    } else {
        echo 'error';

    }

}

//run screen
//echo '<pre>'; print_r($d);
echo "<body topmargin='0' marginwidth='0' marginheight='0' >";
echo "<table>";
//echo "<tr><td>ereg, preg_match</td></tr>";
for($i=0;$i<count($d);$i++)
{
    echo "<tr>";
    echo "<td                ><input onkeydown='if(event.keyCode==13){ tester(\"". $d[$i]['name'] ."\",\"". $d[$i]['t'] ."\",\"". $d[$i]['e'] ."\",this.value ); }' ></td>";
    echo "<td bgcolor=cdefab >".$d[$i]['e']."</td>";
    echo "<td bgcolor=efabcd ><div id='{$d[$i]['name']}' >".$d[$i]['show']."</div></td>";
    echo "<td bgcolor=abefcd >".$d[$i]['n']."</td>";
    echo "<td bgcolor=dddddd >".$d[$i]['t']."</td>";
    echo "</tr>";
}
//echo "<tr><td>preg_match</td></tr>";

echo "</table>";


?>