<?php
/*
+--------------------------------------------------------------------------------+
| db_mysql_info                                                                  |
+--------------------------------------------------------------------------------+
v0.5  --  上午 10:28 2006/2/20
v0.6  --  下午 06:32 2006/10/23  --  增加新的資料庫格式

讀取資料庫所有 table 的資訊 (不包含資料)

使用方式:

$db = new db_mysql_info( $conn_r );
$db->list_db('freeweb');
echo $db->get_display();

*/
class db_mysql_info
{

    //--------------------------------------------------------------------------------
    // var private
    //--------------------------------------------------------------------------------
    //連線
    var $_conn;

    //儲存 database type 的資料
    var $_save;

    //--------------------------------------------------------------------------------
    // var public
    //--------------------------------------------------------------------------------



    //--------------------------------------------------------------------------------
    // 建構子
    //--------------------------------------------------------------------------------
    /**
     * @var    connect  $conn
     * @access private
     */
    function db_mysql_info( $conn )
    {
        if( !$conn )
        {
            die(' class error - connect error ');
        }

        $this->_conn = $conn;

    }


    /**
     * list_db()        -  取得資料庫型態資訊
     * @return array    -
     * @access public
     */
    function list_db( $dbname='' )
    {

        mysql_select_db($dbname,$this->_conn);

        $sql='show tables';
        $res = mysql_query($sql,$this->_conn);
        if(!$res)
        {
            die('query failed - for ( select all table name )');
        }

        //table name array
        while( $row=mysql_fetch_assoc($res)  )
        {
            $all_table_array[]=$row;
        }
        unset($row);

        $count=count($all_table_array);

        //$this->_save 儲存資料用的
        $this->_save='';

        for($i=0;$i<$count;$i++)
        {
            $table = $all_table_array[$i]['Tables_in_'.$dbname];

            $sql='select * from '.$table.' where 1<>1 ';
            mysql_select_db($dbname, $this->_conn);
            $result = mysql_query($sql, $this->_conn );
            //$res = mysql_list_tables( $table , $this->_conn );

            $fields = mysql_num_fields ($result);
            $rows   = mysql_num_rows ($result);

            $this->_save[ $table ]['name']  =$table;
            $this->_save[ $table ]['field'] =$fields;
            $this->_save[ $table ]['record']=$rows;

            //更精確的table資料
            $sql     = 'SHOW FIELDS FROM '.$table ;
            mysql_select_db($dbname, $this->_conn);
            $result2 = mysql_query($sql, $this->_conn );


            $x = 0;
            while ($x < $fields)
            {
                $name  = mysql_field_name ($result, $x);
                $type  = mysql_field_type ($result, $x);
                $len   = mysql_field_len  ($result, $x);
                $flags = mysql_field_flags($result, $x);

                $this->_save[ $table ]['table'][$name]['type'] = $type;
                $this->_save[ $table ]['table'][$name]['len']  = $len;

                if(stristr(' '.$flags,'auto_increment'   ))  $this->_save[ $table ]['table'][$name]['autonum'] =true;
                if(stristr(' '.$flags,'not_null'         ))  $this->_save[ $table ]['table'][$name]['not_null']=true;
                if(stristr(' '.$flags,'primary_key'      ))  $this->_save[ $table ]['table'][$name]['primary'] =true;
                if(stristr(' '.$flags,'unique_key'       ))  $this->_save[ $table ]['table'][$name]['unique']  =true;
                if(stristr(' '.$flags,'multiple_key'     ))  $this->_save[ $table ]['table'][$name]['index']   =true;

                if(stristr(' '.$flags,'binary'           ))  $this->_save[ $table ]['table'][$name]['attrib']  ='binary';
                if(stristr(' '.$flags,'unsigned zerofill'))
                {
                    $this->_save[ $table ]['table'][$name]['attrib']  ='unsigned zerofill';
                }
                elseif(stristr(' '.$flags,'unsigned'     ))
                {
                    $this->_save[ $table ]['table'][$name]['attrib']  ='unsigned';
                }

                $this->_save[ $table ]['table'][$name]['flag'] = $flags;

                $x++;
            }//while


            /*
                Field  Type  Null  Key  Default  Extra
                [0] => flag
                [1] => int(10) unsigned
                [2] =>
                [3] => PRI
                [4] =>
                [5] => auto_increment
            */
            while( $row=mysql_fetch_row($result2)  )  {
                $this->_save[$table]['table'][ $row[0] ]['type2']   = $row[1];
                $this->_save[$table]['table'][ $row[0] ]['is_null'] = $row[2];
                $this->_save[$table]['table'][ $row[0] ]['key2']    = $row[3];
                $this->_save[$table]['table'][ $row[0] ]['default'] = $row[4];
                $this->_save[$table]['table'][ $row[0] ]['extra']   = $row[5];
            }//while
            unset($row);

        }//for

        //debug
        //echo '<pre>';  print_R($this->_save);  echo '</pre>';

        return $this->_save;


    }



    /**
     * get_quick_title()    -  取得 html 快速連結
     * @return array        -
     * @access public
     */
    function get_quick_title()
    {
        if(!$this->_save)
        {
            return '';
        }

        $show='';

        foreach( $this->_save as $table_name => $table_info )
        {
            $show .= '<a href=#quick_'.$table_info['name'].' >'.$table_info['name'].'</a> / ';
        }

        return '<font face=Verdana size=2 >'.$show.'</font>';

    }//function


    /**
     * get_js_code()    - 將取得的資料寫成javascript
     * @return string   - javascript code
     * @access public
     */
    function get_js_code()
    {
        if(!$this->_save) {
            return '';
        }

//        echo '</script><pre>';
//        print_r($this->_save);
        // 取得資料表 $save_table[]
        // 取得欄位   $save_field[][]
        // 取得格式   $save_type[][]
        //     型態   $save_type[]['type']
        //     鍵值   $save_type[]['key']
        //     null   $save_type[]['null']
        // 取得資料   $save_data=''

        //首先要取得資料表
        $save_table=array();
        foreach( $this->_save as $keys => $vals )
        {
            $save_table[]=$keys;  //取得資料表
            foreach( $vals['table'] as $v1 => $v2 )
            {
                $save_field[$keys][]=$v1;  //取得欄位

                //型態
                $save_type[$keys][$v1]['type']  = $v2['type2'];

              //$save_type[$keys][$v1]['type']  = $v2['type'].'('.$v2['len'].')';
              //if($v2['attrib'])  {   $save_type[$keys][$v1]['type'] .= ' - '.$v2['attrib'] ;    }

                //鍵值
                if($v2['primary']) {    $save_type[$keys][$v1]['key'] .=  '<font color=ff0000>primary</font>'  ;    }
                if($v2['autonum']) {    $save_type[$keys][$v1]['key'] .= '(<font color=00aa00>autonum</font>)' ;    }
                if($v2['unique'])  {    $save_type[$keys][$v1]['key'] .=  '<font color=ff0000>unique</font>'   ;    }
                if($v2['index'])   {    $save_type[$keys][$v1]['key'] .=  'index' ;    }

                //空值
                if($v2['not_null']){    $save_type[$keys][$v1]['null'] .= 'not_null' ;    }

                //鍵值2
                $save_type[$keys][$v1]['key2'] = $v2['key2'];

                //etc
                $save_type[$keys][$v1]['extra'] = $v2['extra'];



            }//foreach $vals
        }//foreach $this->_save
        unset($keys,$vals,$v1,$v2,$d1,$d2,$tmp_type);
/*
        echo '<pre>';
        print_r($save_table);
        print_r($save_field);
        print_r($save_type);
*/
/*
//要取得以下的格式
var ad=0;
var table = new Array( Array(),Array(),Array(),Array(),Array() );
var names = 'table1';
table[0][ad++]='欄位1';
table[0][ad++]='欄位2';
ad=0;
table[1][ad++]='格式1';
table[1][ad++]='格式2';
ad=0;
table[2][ad++]='鍵值1';
table[2][ad++]='鍵值2';
ad=0;
table[3][ad++]='空值1';
table[3][ad++]='空值2';
ad=0;
table[4][ad++]='資料1';
table[4][ad++]='資料2';
show_array( names,table );
*/

        //顯示 javascript 的變數
        $show="";

        //組合

            foreach( $save_field as $keys => $vals )
            {
                $tmp1='';  //暫存變數
                $tmp2='';  //暫存變數
                $tmp3='';  //暫存變數
                $tmp4='';  //暫存變數
                $tmp5='';  //暫存變數
                $tmp6='';  //暫存變數
                $tmp7='';  //暫存變數
                foreach( $vals as $v1=>$v2 )
                {
                    $tmp1.="\ntable[0][ad++]='$v2';";
                    $tmp2.="\ntable[1][ad++]='".$save_type[$keys][$v2]['type']."';";
                    $tmp3.="\ntable[2][ad++]='".$save_type[$keys][$v2]['key']."';";
                    $tmp4.="\ntable[3][ad++]='".$save_type[$keys][$v2]['null']."';";
                    $tmp5.="\ntable[4][ad++]='';";
                    $tmp6.="\ntable[5][ad++]='".$save_type[$keys][$v2]['key2']."';";
                    $tmp7.="\ntable[6][ad++]='".$save_type[$keys][$v2]['extra']."';";
                }//foreach $vals
                $show.="var ad=0;\n";
                $show.="var table = new Array( Array(),Array(),Array(),Array(),Array(),Array(),Array() );\n";
                $show.="var names = '{$keys}';";
              //$show.="\n";
                $show.=$tmp1;
                $show.="ad=0;".$tmp2;
                $show.="ad=0;".$tmp3;
                $show.="ad=0;".$tmp4;
                $show.="ad=0;".$tmp5;
                $show.="ad=0;".$tmp6;
                $show.="ad=0;".$tmp7;
                $show.="\n";
                $show.="show_array( names,table );\n\n";
            }//foreach $save_field

        //<script>
        //</script>
        return $show;

    }





    /**
     * get_display()    -  取得顯示資料庫型態的資訊
     * @return string   -  html code
     * @access public
     */
    function get_display()
    {
        if(!$this->_save)
        {
            return '';
        }

        $show='';  //顯示html

        foreach( $this->_save as $table_name => $table_info )
        {
            foreach( $table_info as $k1 => $k2 )
            {

                //table info
                if('table'!=$k1)
                {
                    //$show .=  $k1.'='.$k2.' <br>';
                    if('name'==$k1)
                    {
                        $show .= '<a name=quick_'.$k2.' ></a><font size=4 face=Verdana ><b>'.$k2.'</b></font>';
                    }

                }
                else
                {

                    $save_v1='';  //第一列
                    $save_v2='';  //第二列
                    $save_v3='';  //第三列

                    foreach( $k2 as $v1 => $v2array )
                    {
                        $save_v1.='<td width=160 ><b>'.$v1.'</b></td>';

                        $save_v2_buffer='';
                        $tmp_type='';

                        foreach( $v2array as $v2_1 => $v2_2 )
                        {
                            switch($v2_1)
                            {
                                case 'type':
                                    if(''==$tmp_type)  $tmp_type = $v2_2 ;
                                    else               $tmp_type = $v2_2 . $tmp_type .'<br>';
                                break;
                                case 'len':
                                    if(''==$tmp_type)  $tmp_type  = '('.$v2_2.')<br>';
                                    else               $tmp_type .= '('.$v2_2.')';
                                break;
                                case 'flag':
                                break;
                                default:
                                    $save_v2_buffer.=$v2_1.'='.$v2_2.'<br>';
                                break;
                            }

                            //if('flag'!=$v2_1)
                            //{
                            //    $save_v2_buffer.=$v2_1.'='.$v2_2.'<br>';
                            //}

                        }

                        $save_v2.='<td valign=top >'.$tmp_type.'<br>'.$save_v2_buffer.'</td>';
                        $save_v3.='<td valign=top ><br /><br />&nbsp;</td>';

                    }

                    $show .= '<table border=0 cellspacing=1 cellpadding=2 style="font-size:10pt;border:solid #aaaaaa 1px;" ><tr bgcolor=ccddee >'.$save_v1.'</tr>'
                            .'<tr bgcolor=ddeeff >'.$save_v2.'</tr>'
                            .'<tr bgcolor=eeddff >'.$save_v3.'</tr></table><br>';

                }

            }
        }


        return $show;



    }//function




}//class
?>
