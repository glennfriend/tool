<?php
if (  "127.0.0.1" === $_SERVER['REMOTE_ADDR'] ||
      "192.168."  === substr($_SERVER['REMOTE_ADDR'],0,8)
) {
    // allow
}
else {
    echo $_SERVER['REMOTE_ADDR'] . '<br>';
    echo $_SERVER['REMOTE_HOST'] . '<br>';
    exit;
}
?>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<script>

//顯示資料
function show_array( names,table ) {
    var type = 2 ;
    if(type==1)  show1_array(names,table);
    if(type==2)  show2_array(names,table);
    if(type==3)  show1_easy(names,table);
}

//顯示三層的array - 橫式
function show1_array( names,table )
{
    var save_title='';
    var save_type ='';
    var save_key  ='';
    var save_null ='';
    var save_text ='';
    for(var i=0;i<table[0].length;i++)
    {
        save_title += '<td width=150 ><b>'+table[0][i]+"</b></td>\n";                                    //title
        save_type  += '<td valign=top >'  +table[1][i]+'<br>'+table[2][i]+'<br>'+table[3][i]+"</td>\n";  //type+key+not_null
        save_text  += '<td valign=top >'  +table[4][i]+'<br />&nbsp;</td>'+"\n";                         //text
    }
    document.writeln('<a name=quick_'+names+' ></a><font size=4 face=Verdana ><b>'+names+'</b></font>');
    document.writeln('<table border=0 cellspacing=1 cellpadding=2 style="font-size:10pt;border:solid #aaaaaa 1px;" >'+"\n");
    document.writeln('<tr bgcolor=ccddee >'+save_title+"</tr>\n");
    document.writeln('<tr bgcolor=ddeeff >'+save_type +"</tr>\n");
    document.writeln('<tr bgcolor=eeddff >'+save_text +"</tr>\n");
    document.writeln('</table>'+"\n");
}

//顯示三層的array - 直式
function show2_array( names,table )
{

    document.writeln('<a name=quick_'+names+' ></a><font size=4 face=Verdana ><b>'+names+'</b></font>');
    document.writeln('<table border=0 cellspacing=1 cellpadding=2 style="font-size:10pt;border:solid #aaaaaa 1px;" >'+"\n");
    for(var i=0;i<table[0].length;i++)
    {
        document.writeln('<tr>');
        document.writeln('<td bgcolor=ccddee width=120 >'  +table[0][i]+ "</td>\n");           //title
        document.writeln('<td bgcolor=ddeeff width=100 valign=top >' +table[1][i]+ "</td>\n");           //type
        document.writeln('<td bgcolor=ddeeff width=90 valign=top >' +table[2][i]+ "</td>\n");           //key
        document.writeln('<td bgcolor=ddeeff width=40 valign=top >' +table[3][i]+ "</td>\n");           //not_null
        document.writeln('<td bgcolor=eeddff width=220 valign=top >' +table[4][i]+ '&nbsp;</td>'+"\n");  //text
        document.writeln("</tr>\n");
    }
    document.writeln('</table>'+"\n");
}

//顯示簡約式雙層牛肉堡array - 直式
function show1_easy( names,table )
{
    var save_title='';
    var save_type ='';
    var save_key  ='';
    var save_null ='';
    var save_text ='';
    for(var i=0;i<table[0].length;i++)
    {
        //title
        if(table[5][i]=='') {
            save_title += '<td width=120 >'+table[0][i]+"</td>\n";
        } else {
            save_title += '<td width=120 ><b><u>'+table[0][i]+'</u></b> ('+table[5][i]+")</td>\n";
        }

        save_text  += '<td valign=top >'  +table[4][i]+'<br />&nbsp;</td>'+"\n";  //text
    }
    document.writeln('<a name=quick_'+names+' ></a><font size=4 face=Verdana ><b>'+names+'</b></font>');
    document.writeln('<table border=0 cellspacing=1 cellpadding=2 style="font-size:10pt;border:solid #aaaaaa 1px;" >'+"\n");
    document.writeln('<tr bgcolor=fefefe >'+save_title+"</tr>\n");
    document.writeln('<tr bgcolor=efefef >'+save_text +"</tr>\n");
    document.writeln('</table>'+"\n");
}

</script>
<?php
include_once 'db_mysql_info.class.php';
/*
$conn  = @mysql_pconnect ( '192.168.1.127' ,
                           'root' ,
                           'PolKiFWNimdaDB1' );
*/
/*
$conn  = @mysql_pconnect ( 'db1.sktco.com' ,
                           'skt' ,
                           '!SKT$dev*5886' );
*/
$conn  = @mysql_pconnect ( 'localhost' ,
                           'root' ,
                           '' );


$db = new db_mysql_info( $conn );
//echo '<pre>';
//print_r( $db->list_db('freeweb') );
//$db->list_db('systemx');
//$db->list_db('crm');
$db->list_db('test');


//直接顯示 html code
//echo $db->get_display();

//顯示 map連結
echo $db->get_quick_title();

//顯示 javascript code
echo '<pre>';
echo "<script>\n";
echo $db->get_js_code();
echo '</script>';



echo<<<EOD


<pre>
--------------------

TINYINT   小的整數    , 有符號的范圍是       -128 到        127, 無符號的范圍是0到       255
SMALLINT  小整數      , 有符號的范圍是     -32768 到      32767, 無符號的范圍是0到     65535
MEDIUMINT 中等大小整數, 有符號的范圍是   -8388608 到    8388607, 無符號的范圍是0到  16777215  一千五百萬
INT       正常大小整數, 有符號的范圍是-2147483648 到 2147483647, 無符號的范圍是0到4294967295  四十億

TINYTEXT    一個BLOB或TEXT列，最大長度為        255  (2^8-1)個字符
BLOB        一個BLOB或TEXT列，最大長度為      65535 (2^16-1)個字符
TEXT
MEDIUMBLOB  一個BLOB或TEXT列，最大長度為   16777215 (2^24-1)個字符
MEDIUMTEXT
LONGBLOB    一個BLOB或TEXT列，最大長度為 4294967295 (2^32-1)個字符
LONGTEXT


EOD;
?>