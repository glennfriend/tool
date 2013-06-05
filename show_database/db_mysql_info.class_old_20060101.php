<?php
/*
+--------------------------------------------------------------------------------+
| db_mysql_info                                                                  |
+--------------------------------------------------------------------------------+
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
            die('query failed - for ( select all table name ');
        }
        
        //table name array
        while( $row=mysql_fetch_assoc($res)  )
        {
            $all_table_array[]=$row;
        }

        $count=count($all_table_array);

        //$this->_save 儲存資料用的 
        $this->_save='';

        for($i=0;$i<$count;$i++)
        {
            $table_name = $all_table_array[$i]['Tables_in_'.$dbname];

            $sql='select * from '.$table_name.' where 1<>1 ';
            $result = mysql_db_query($dbname,$sql,$this->_conn);
            //$res = mysql_list_tables( $table_name , $this->_conn );

            $fields = mysql_num_fields ($result); 
            $rows   = mysql_num_rows ($result); 

            $x = 0;
            $table = mysql_field_table ($result, $x); 

            $this->_save[ $table ]['name']  =$table;
            $this->_save[ $table ]['field'] =$fields;
            //$this->_save[ $table ]['record']=$rows;

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
            }


        }

 
        return $this->_save;


    }



    /**
     * get_quick_title()    -  取得
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
     * get_display()    -  取得顯示資料庫型態的資訊 
     * @return array    - 
     * @access public
     */
    function get_display()
    {
        if(!$this->_save)
        {
            return '';
        }

        $show='';      //顯示html

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
