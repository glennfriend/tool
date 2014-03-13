<?php

init();
return;


function outputHeader()
{
    echo <<<EOD
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-tw" />
    <link rel="stylesheet" type="text/css" href="themes/bootstrap_302/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="themes/bootstrap_302/css/bootstrap-theme.css" />
    <script type="text/javascript" charset="utf-8" src="js/knockout-3.0.0.js" ></script>
</head>
<body style="margin:20px;">
EOD;
}

function outputFooter()
{
    echo <<<EOD
</body>
</html>
EOD;
}

function init()
{
    //
    header("Content-Type:text/html; charset=utf-8");
    error_reporting(E_ALL);
    ini_set('html_errors','On');
    ini_set('display_errors','On');
}

