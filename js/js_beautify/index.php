<?php
require_once('javascript_beautify2.php');

$content='';
if( $_POST && $_POST['content'] ) {
    $content = js_beautify( stripslashes($_POST['content']) );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--

  (c) 2006-2007: Einars "elfz" Lielmanis, 
            elfz@laacz.lv
            http://elfz.laacz.lv/

-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Online beautifier for javascript (js beautify, pretty-print)</title>
<script type="text/javascript">
window.onload = function() {
    var c = document.forms[0].content;
    c && c.setSelectionRange && c.setSelectionRange(0, 0);
    c && c.focus && c.focus();
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
form     { margin: 0 10px 0 10px }
textarea { width: 99%; height: 480px; border: 1px solid #ccc; padding: 3px; font-family: liberation mono, consolas, courier new, courier, monospace; font-size: 12px; }
h1       { font-family: trebuchet ms, arial, sans-serif; font-weight: normal; font-size: 18px; color: #666; margin-bottom: 15px; border-bottom: 1px solid #666; }
button   { width: 100%; cursor: pointer;}
code, .code { font-family: liberation mono, consolas, lucida console, courier new, courier, monospace; font-size: 12px; }
pre      { font-size: 12px; font-family: liberation mono, consolas, courier new, courier, monospace; margin-left: 20px; color: #777; }
</style>
</head>
<body>
  <h1>Beautify Javascript</h1>
  <form method="post" action="">
      <textarea name="content"><?=$content?></textarea><br />
      <button type="submit">Beautify</button>
      This script was intended to explore ugly javascripts, e.g <a href="http://createwebapp.com/javascripts/autocomplete.js">compacted in one line</a>.<br />
      PHP source can be <a href="beautify.phps">seen online here</a> or fetched from subversion repository at <a href="svn://edev.uk.to/beautify/">svn://edev.uk.to/beautify</a>. Feel free to use and abuse.<br />
      In case of glitches you may wish to tell me about them&mdash;<code>elfz<span style="color:#999">[at]</span>laacz<span style="color:#999">[dot]</span>lv</code><br />
  </form>
<pre>
from: http://elfz.laacz.lv/beautify/?
</pre>
</body>
</html>