<?php
    include 'library.php';
    $html = outputHtml(file_get_contents('sample.tpl'));
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <link rel="stylesheet" type="text/css" href="reset.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
    <script type="text/javascript" charset="utf-8">
        "use strict";

        var currentLine = '';

        $(function() {

            $("#command").focus();

            $('#command').on('keyup',function(e){
                currentLine = $('#command').val();
                $(".focusStyle").removeClass('focusStyle');
                //try {} catch (e) {}
                $(currentLine).addClass('focusStyle');
            });
        });

    </script>
    <style type="text/css">
        body {
            margin: 10px;
            font-size: 14px;
            font-family: Consolas,dina,GulimChe;
        }
        #command {
            width: 100%;
            margin-bottom: 10px;
        }
        #source {
            border:1px solid #cccccc;
            width:100%;
            min-height:500px;
        }
        .focusStyle {
            background-color:#ddeeff;
        }
    </style>
</head>
<body>

<input type="text" value="" id="command" />
<section id="source"><code><?php echo $html; ?></code></section>

<pre>
h2 ~ p
h2 + p
p + h2
div *
li[class|="baby"]
li:nth-child(3n + 1)
.food:not(h2)
div #line3 > h2 > span
div #line3 > h2 span
input[type=text]:disabled

http://milanlandaverde.com/css-quiz
</pre>


</body>
</html>