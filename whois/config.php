<?php

// This is the Matt's Whois PHP version configuration file, lets keep it simple
// folks!

// This variable should be set to the name of the directory which contains your
// titles config file, your template config file and the rest of the stuff like
// that! (ie, there's a directory called "english/" so we put "english") this
// is the standard boring old template
//$theme = "english";
$theme = "big5";

// this a list of the whois extensions to use, edit them as you need them
$whois_exts = array(
	"com",
	"net",
	"org",
	"biz",
	"info",
	"com.tw",
	"org.tw"
);

// should we use the global header and footer templates? (this can be over
// ridden by a POST or GET variable)
$use_global_templates_by_default = 1;

?>
