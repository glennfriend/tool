<?php

    define('TOOLS_DIRECTORY_FILES','tools/*.php');
    include_once('libs/ToolBaseObject.class.php');

    $post = Array(
        'key'     => ($_POST['key']!='') ? $_POST['key'] : $_GET['key'],
        'content' => $_POST['content'],
    );

    $toolFilenames = Array();
    foreach( glob(TOOLS_DIRECTORY_FILES) as $file ) {

        $key = md5($file);
        $filename = mb_substr($file, strrpos($file, '/') + 1);

        $show = mb_substr($filename,0,(mb_strlen($filename)-4));
        if( 'UTF-8' != mb_detect_encoding($show, 'UTF-8, BIG-5') ) {
            $show = mb_convert_encoding($show, "UTF-8", "BIG5");  // big5 to utf-8
        }
        else {
            $show = $show;
        }

        $toolFilenames[$key] = Array(
            'key'       => $key,
            'pathname'  => $file,
            'filename'  => $filename,
            'show'      => $show,
            'extension' => pathinfo($file,PATHINFO_EXTENSION),
        );
    }


    if( $post['key'] && $toolFilenames[$post['key']]['pathname'] ) {
        $pathName = $toolFilenames[$post['key']]['pathname'];
        include_once($pathName);
        $object = new ToolObject( $post['content'] );
        $object->init();
        $object->run();
        $beforeText = $object->getBeforeText();
        $text       = $object->getText();
        $afterText  = $object->getAfterText();
    }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js'></script>
    <style type="text/css">
        *,body {
            font-size:12px; 
        }
        textarea {
            border:1px solid #000;
        }
    </style>
  </head>
  <body>

    <form name="formSubmit" action="" method="post" enctype="multipart/form-data" style="margin: 0;" >
        <?php
            foreach( $toolFilenames as $file ) {
                echo '<input type="radio" name="key" value="'. $file['key'] .'" />'. $file['show']. "\n";
            }
        ?>
        <br />
        <input type="submit" value=" submit " />
        <input type="button" value=" clear " onclick="document.getElementById('content').innerHTML='';" />
        <div id="beforeText"><?php echo $beforeText; ?></div>
        <textarea id="content" name="content" style="width:100%; height:300px;"><?php echo $text; ?></textarea>
        <div id="afterText"><?php echo $afterText; ?></div>
    </form>




    <script type="text/javascript">
        var focus = "<?php echo $post['key']; ?>";
        if( focus ) {
            $('input[value='+focus+']').get(0).checked = true;
        }
        else {
            $('input[value="56ef239631d08888a53c4557c52803d2"]').get(0).checked = true;
        }
    </script>

    <br />

  </body>
</html>
<?php

/*
echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
print_r($toolFilenames);
echo "</pre>\n";
*/
