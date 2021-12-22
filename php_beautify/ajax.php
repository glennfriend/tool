<?php

    session_start();
    include 'helper.php';

    $text = trim($_POST['text']);
    setContent($text);
    runCommand();

    // output
    echo getContent();


//
