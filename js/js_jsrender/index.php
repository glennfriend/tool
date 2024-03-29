<?php include_once 'library.php'; ?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-tw" />
    <link rel="stylesheet" type="text/css" href="dist/bootstrap-3.2.0-dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="dist/main.css" />
    <script type="text/javascript" src="dist/jquery/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="dist/jquery/jsrender/jsrender.js"></script>
    <script type="text/javascript" src="dist/jquery/jsrender/range.js"></script>
    <script type="text/javascript" src="dist/main.js"></script>
    <script type="text/javascript">

        $(function() {

            var initTemplate = 'codes/default.html';
            var initData = 'codes/default.json';
            loadFile( initTemplate, initData );

            $("#sourceCode, #sourceData").on("keypress", function( event ) {
                if ( event.which == 10 ) {
                    // Ctrl+Enter
                    run();
                    event.preventDefault();
                }
            });

        });
    
        function run()
        {
            var dataString = $("#sourceData").val();
            eval("var data = " + dataString + ";");
            // var data = JSON.parse(dataString);

            var renderSourceCode 
                = '<script type="text/html" id="myTemplate">'
                + $("#sourceCode").val()
                + '</' +'script>'
            ;

            $("#sourceCodeDisplay").html(renderSourceCode);
            var html = $("#myTemplate").render( data )
            $("#result").html(html);
        }

        /**
         *  麻煩在地方在於, template file and data file 載入之後
         *  要設定到 textarea 裡面, "等待" 設定完成才能 run()
         *  所以要注意 callback 地獄
         */
        function loadFile(template,data)
        {
            var templateXhr =
                $.get( template+"?"+Date(), function(){} )
                .done(function(content) {
                    $("#sourceCode").val(content);
                })
                .always(function() {
                    var dataXhr = 
                        $.get( data+"?"+Date(), {}, function(){}, 'html' )
                        .done(function(content) {
                            $("#sourceData").val(content);
                        })
                        .always(function(){
                            run();
                        })
                    ;
                })
            ;
        }

        // custom template function
        $.views.helpers({

            format: function( val, format ) {
                console.log(val);
                console.log(format);
            }

        });

    </script>
</head>
<body>

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <button onClick="run()">Run ( Ctrl+Enter )</button>
                    <?php
                        foreach ( getList() as $list ) {
                            $load
                                = "loadFile('{$list['template']}', '{$list['data']}');"
                                . "run();"
                            ;
                            echo '<button onClick="'. $load .'">'. $list['show'] .'</button>';
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <textarea class="form-control" rows="16" id="sourceCode"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div id="sourceCodeDisplay"></div>
                    <textarea class="form-control" rows="14" id="sourceData"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div id="result"></div>
            </div>
        </div>
    </div>

</body>
</html>