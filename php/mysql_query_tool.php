<?php
// 該程式用在 command line 下 SQL 指令

$sqlText =<<<EOD
USE `mysql`;
SELECT Host, Db, User, Select_priv, Insert_priv
FROM `db`
WHERE 1
limit 5;

SELECT "------" as "";

USE `wms`;
SELECT account, create_time FROM `users` WHERE 1 limit 5;
SELECT COUNT(id) AS "Count Total"   FROM `store_appointments` WHERE `schedule_date` >= "2017-11-05" AND `schedule_date` <= "2017-11-11";
SELECT COUNT(id) AS "Count Confirm" FROM `store_appointments` WHERE `schedule_date` >= "2017-11-05" AND `schedule_date` <= "2017-11-11" AND `check_status` > 1;
EOD;


// test SQL
// $sqlText = "show databases \G";


// --------------------------------------------------------------------------------
//  start
// --------------------------------------------------------------------------------
$queryContent = tryFirstSqlCommand();

//
validateTextAndError($sqlText);

//
$sql = filterText($sqlText);
// echo $sql . "\n" . "==========\n";
echo sqlQuery($sql);
exit;

 
// --------------------------------------------------------------------------------
// 
// --------------------------------------------------------------------------------
function sqlQuery($sql)
{
    $securitySql = addslashes($sql);
    $cmd = <<<EOD
mysql --login-path="your_mysql_setting" -e "{$securitySql}" 
EOD;

    // echo $cmd . "\n"; exit;
    // echo $cmd . "\n";
    return shell_exec($cmd);
}

/*
    注意:
        - command line 的 mysql 命令中 SQL 不能帶有 ` 符號
        - 例如以下狀況會有錯誤
            - SELECT * FROM `users` limit 1
        - 要改成
            - SELECT * FROM users limit 1
*/
function parseTextToSqlLines($text)
{
    $lines = [];
    $contents = explode("\n", $text);
    foreach ($contents as $content) {
        $content = str_replace("`", "", $content);
        $lines[] = $content;
    }
    
    // print_r($lines);
    return $lines;
}

function filterText($text)
{
    $lines = parseTextToSqlLines($text);
    return join("\n", $lines);
}

function validateTextAndError($text)
{
    $validateStringArr = [
        'insert ',
        'update ',
        'delete ',
    ];
    $allSql = strtolower(filterText($text));

    foreach ($validateStringArr as $str) {
        if (false !== strpos($allSql, $str)) {
            die("[Error] Find not allow string: " . $str . "\n");
        }
    }
}

// --------------------------------------------------------------------------------
// 
// --------------------------------------------------------------------------------
/**
 * 試下一個指令, 看有沒有權限
 */
function tryFirstSqlCommand()
{
    $result = sqlQuery("show databases");
    if (! $result) {
        echo "\n";
        echo <<<EOD
====
    error:
        目前無法使用
        請先設定 database "host" "account" "password"

    how_to_setting:
        mysql_config_editor set --login-path=your_mysql_setting --host="localhost" --user="root" --password

    see_all_setting:
        mysql_config_editor print --all

    remove_setting:
        mysql_config_editor remove --login-path=your_mysql_setting
====
EOD;
        echo "\n";
        exit;
    }
}




//