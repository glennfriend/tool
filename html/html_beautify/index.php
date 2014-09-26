<?php

    session_start();
    include 'helper.php';

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-tw" />
    <style type="text/css">
        form {
            margin: 0;
        }
        textarea {
            width: 100%;
            min-height: 600px;
        }
    </style>
    <script type="text/javascript" charset="utf-8" src="//code.jquery.com/jquery-1.11.1.min.js" ></script>
    <script type="text/javascript" charset="utf-8">
        "use strict";

        $(function() {

            var successEvent = function(text)
            {
                $('#text').val(text);
                $('#show').text(new Date());
            };

            $("#text").keypress(function(event) {
                // Ctrl+Enter
                if( 10 === event.which ) {
                    $('#form').submit();
                }
            });

            //
            $('#form').on('submit', function() {
                $.ajax({
                    url: 'ajax.php',
                    type: 'post',
                    dataType: 'html',
                    data: $(this).serialize(),
                    success: successEvent
                });
                return false;
            });

        });


    </script>
</head>
<body>

    <div id="show"></div>
    <form id="form" action="post">
        <textarea id="text" name="text"><?php echo htmlspecialchars(getContent()); ?></textarea>
        <input type="submit" value="Click Button or Send Ctrl+Enter" />
    </form>
    <ul>
        <li>程式若無法使用, 請安裝 nodejs </li>
    </ul>

</body>
</html>

