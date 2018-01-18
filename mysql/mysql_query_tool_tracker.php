<?php
// 該程式用在 command line 下 SQL 指令

function getSqlItems()
{
    $items = [];
    $items[] = [
        'category = rentown.net', <<<EOD

        USE `tracker`;
        select id, name, category, created_at, url
        from events
        where category="rentown.net"
        order by id desc limit 15;

EOD
];

    $items[] = [
        'category != rentown.net', <<<EOD

        USE `tracker`;
        select id, name, category, created_at, url
        from events
        where category != "rentown.net"
        order by id desc limit 15;

EOD
];

    return $items;
}


$sqlItems = getSqlItems();


// --------------------------------------------------------------------------------
//  test SQL
// --------------------------------------------------------------------------------
if (! $sqlItems) {
   $sqlItems = ["show databases \G"];
}

// --------------------------------------------------------------------------------
//  start
// --------------------------------------------------------------------------------
$queryContent = tryFirstSqlCommand();

//
foreach ($sqlItems as $index => $sqlItem) {

    if (count($sqlItem)>1) {
        $title  = $sqlItem[0];
        $sql    = $sqlItem[1];
    }
    else {
        $title = '';
        $sql = $sqlItem[0];
    }
    validateTextAndError($sql);
}

//
foreach ($sqlItems as $index => $sqlItem) {

    if (count($sqlItem)>1) {
        $title  = $sqlItem[0];
        $sql    = $sqlItem[1];
    }
    else {
        $title = '';
        $sql = $sqlItem[0];
    }

    if ($title) {
        echo "==== {$title} ====\n";
    }

    $sql = filterText($sql);
    // echo $sql . "\n" . "==========\n";
    echo sqlQuery($sql);
    echo "\n";
}
exit;

 
// --------------------------------------------------------------------------------
// 
// --------------------------------------------------------------------------------
function getLoginPath()
{
    return "your_mysql_setting_by_tracker";
}

function sqlQuery($sql)
{
    $loginPath = getLoginPath();
    $securitySql = addslashes($sql);
    $cmd = <<<EOD
mysql --login-path="{$loginPath}" -e "{$securitySql}" 
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
    $loginPath = getLoginPath();
    $result = sqlQuery("show databases");
    if (! $result) {
        echo "\n";
        echo <<<EOD
Error:
    目前還無法使用
    請先設定 database "host" "account" "password"

Tip:
    [how to setting]
        mysql_config_editor set --login-path={$loginPath} --host="localhost" --user="root" --password

    [see all setting]
        mysql_config_editor print --all

    [remove setting]
        mysql_config_editor remove --login-path={$loginPath}
\n
EOD;
        exit;
    }
}




//