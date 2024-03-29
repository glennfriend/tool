<?php
// 該程式用在 command line 下 SQL 指令

function getSqlItems()
{
    $items = [];
    $items[] = [
        'rentown.net', <<<EOD

        USE `tracker`;
        select  name,
                SUBSTR(created_at,6,11) as _created_at,
                CONCAT(SUBSTR(uuid,1,8),"..") as _uuid,
                ip,
                REPLACE(
                    REPLACE(
                        url, "http://www.rentown.net/", "___//"
                    ) ,     "https://www.rentown.net/", "_s_//"
                ) as _url
        from events
        where category="rentown.net"
        order by id desc limit 18;

EOD
];

    $items[] = [
        'rentown.net count', <<<EOD

        USE `tracker`;
        select  count(*) as total
        from events
        where category="rentown.net"

EOD
];

    $items[] = [
        'rentownhomespro.com', <<<EOD

        USE `tracker`;
        select  name,
                SUBSTR(created_at,6,11) as _created_at,
                CONCAT(SUBSTR(uuid,1,8),"..") as _uuid,
                ip,
                REPLACE(
                    REPLACE(
                        url, "http://rentownhomespro.com/", "___//"
                    ) ,     "https://rentownhomespro.com/", "_s_//"
                ) as _url
        from events
        where category="rentownhomespro.com"
        order by id desc limit 18;

EOD
];

    $items[] = [
        'rentownhomespro.com count', <<<EOD

        USE `tracker`;
        select  count(*) as total
        from events
        where category="rentownhomespro.com"

EOD
];

    /*
    $items[] = [
        'other category', <<<EOD

        USE `tracker`;
        select id, name, category, created_at, uuid, ip
        from events
        where category != "rentown.net" and category != "rentownhomespro.com"
        order by id desc limit 2;

EOD
];
    */

    return $items;
}

// --------------------------------------------------------------------------------
// 
// --------------------------------------------------------------------------------
function main()
{
    $sqlItems = getSqlItems();
    if (! $sqlItems) {
        $sqlItems[] = [
            "show databases",
            "show databases \G"
        ];
    }

    // --------------------------------------------------------------------------------
    //  validate
    // --------------------------------------------------------------------------------
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

    // --------------------------------------------------------------------------------
    //  start
    // --------------------------------------------------------------------------------
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
            // echo "### \n";
            echo "### {$title} \n";
            //echo "### \n";
        }

        $sql = filterText($sql);
        // echo $sql . "\n" . "==========\n";
        echo sqlQuery($sql);
        echo "\n";
    }
}
main();
tryFirstSqlCommand();
exit;



 
// --------------------------------------------------------------------------------
//  private
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