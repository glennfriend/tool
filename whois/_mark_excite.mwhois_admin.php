<?php

/*
	MWhois Admin - a quick admin front end to MWhois by Matt Wilson <matt@mattsscripts.co.uk>
	
	MWhois - a Whois lookup script written in PHP and Perl
	Copyright (C) 2000 Matt Wilson

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// this lump of code should sort out register_globals="off" problems :)
global $vars;

if(!isset($_SERVER)) { $_SERVER = $HTTP_SERVER_VARS; }
if(!isset($_POST)) { $_POST = $HTTP_POST_VARS; }
if(!isset($_GET)) { $_GET = $HTTP_GET_VARS; }
if(!isset($_COOKIE)) { $_COOKIE = $HTTP_COOKIE_VARS; }
if(!isset($_FILES)) { $_FILES = $HTTP_POST_FILES; }
if(!isset($_ENV)) { $_ENV = $HTTP_ENV_VARS; }
if(!isset($_SESSION)) { $_SESSION = $HTTP_SESSION_VARS; }

while(list($key, $var) = each($_GET)) { $vars[$key] = $var; }
while(list($key, $var) = each($_POST)) { $vars[$key] = $var; }

$whois_exts = array();
$theme_list = array();

$dir_split = "/";

if(substr($HTTP_ENV_VARS["PATH"], 0, 1) != "/") { $dir_split = "\\"; }

function my_in_array($val, $array_)
{
	for($l=0; $l<sizeof($array_); $l++) {
		if($array_[$l] == $val) {
			return 1;
		}
	}

	return 0;
}

// in case the servers.lst can't be found
function fatal_servers_lst()
{
	echo '<HTML><BODY><FONT FACE=VERDANA SIZE=2>MWhois could not locate the `servers.lst\' file in the current directory!</FONT></BODY></HTML>';
	exit();
}

// get a list of the directory names so that we can list them
function list_themes()
{
	global $theme_list;
	global $dir_split;
	global $vars;
	
	$dir = opendir(".");
	while($name = readdir($dir)){
		if($name == ".." || $name == ".") { continue; }
		if(is_dir($name) && is_file($name.$dir_split."templates.cfg")){ $theme_list[] = $name; }
	}
	
	closedir($dir);
}

// this loads the server info for the extensions in $whois_exts;
function load_server_info()
{
	global $whois_exts;
	
	// load the servers.lst file
	$tlds = @file("servers.lst");
	if(!$tlds) { fatal_servers_lst(); }

	for($l=0; $l<sizeof($tlds); $l++){
		// time leading spaces or trailing spaces
		$tlds[$l] = chop($tlds[$l]);
		
		// filter out the commented lines (begin with #)
		if(substr($tlds[$l], 0, 1) == "#" || !strlen($tlds[$l])) { continue; }

		// explode via the seperation char `|'
		$es = explode("|", $tlds[$l]);

		// check to see whether this TLD is a duplicate
		if(my_in_array($es[0], $whois_exts)) { continue; }
		$whois_exts[] = $es[0];
		
		// thats it!
	}
}

load_server_info();
list_themes();

function show_admin_menu()
{
	global $whois_exts;
	global $theme_list;
	global $vars;

?>
<html>
<head>
<title>MWhois Admin menu</title>
</head>
<body bgcolor="#ffffff">
<font face="verdana" size="2">Welcome to the quick admin section of <a href="http://www.mattsscripts.co.uk/mwhois.htm" target="_new">Matt's Whois</a> by <a href="http://www.mattsscripts.co.uk/" target="_new">Matt Wilson</a>.<br>Here you have the option to select which theme you would like to use with <a href="http://www.mattsscripts.co.uk/mwhois.htm" target="_new">Matt's Whois</a>, which Top Level Domains you would like to use and possibly a couple of other miscellaneous options as the script progresses.<br>For the latest <a href="http://www.mattsscripts.co.uk/servers.lst" target="_new">servers.lst</a> file, click <a href="http://www.mattsscripts.co.uk/servers.lst" target="_new">here</a> to download it.<br>
<br>
<form method="post" action="mwhois_admin.php">
Please choose the theme you would like to use :- <select name="theme">
<?php
echo "<option>", implode("</option><option>", $theme_list), "</option>";
//for($l=0; $l<sizeof($theme_list); $l++){ echo "<option>".$theme_list[$l]."</option>"; }
?>
</select><br>
Would you like the script to use the global page headers and footers (usually yes for full page sites)? :- <input type="checkbox" name="global" value="1" checked><br>
Below is a list of all the TLDs that are supported by the version of servers.lst you have installed, please check the ones you would like to use;<br><br><table><?php
for($l=0; $l<sizeof($whois_exts); $l+=3){
	echo	'<tr><td>',
		'<input type="checkbox" value="yes" name="use_', str_replace(".", "_", $whois_exts[$l]).'">.', $whois_exts[$l],
		'</td><td>';
	if($whois_exts[$l+1]) { echo '<input type="checkbox" value="yes" name="use_', str_replace(".", "_", $whois_exts[$l+1]), '">.', $whois_exts[$l+1]; } else { echo '&nbsp;'; }
	echo	'</td><td>';
	if($whois_exts[$l+2]) { echo '<input type="checkbox" value="yes" name="use_', str_replace(".", "_", $whois_exts[$l+2]), '">.', $whois_exts[$l+2]; } else { echo '&nbsp;'; }
	echo "</td></tr>";
}
?>
</table>
<br><br>Click the button below to retrieve the config file, simply rename it to "config.php" and put it in the same directory as the main "mwhois.php" script;<br><input type=submit name=action value="Get config file">
</form>
</body>
</html>
<?php
}

function get_config_file()
{
	global $whois_exts;
	global $vars;

	settype($vars["global"], "integer");

//	header("Content-Type: octet/stream");
//	header("Content-Disposition: attachment; filename=config.php");
	header("Content-Type: text/plain");
	
	echo	"<?php\r\n",
		"\r\n",
		"// This is the Matt's Whois PHP version configuration file, lets keep it simple\r\n",
		"// folks!\r\n",
		"\r\n",
		"// This variable should be set to the name of the directory which contains your\r\n",
		"// titles config file, your template config file and the rest of the stuff like\r\n",
		"// that! (ie, there's a directory called \"english/\" so we put \"english\") this\r\n",
		"// is the standard boring old template\r\n",
		"\$theme = \"", $vars["theme"], "\";\r\n",
		"\r\n",
		"// this a list of the whois extensions to use, edit them as you need them\r\n",
		"\$whois_exts = array(\r\n";

	$use_exts = array();
	for($l=0; $l<sizeof($whois_exts); $l++){
		if($vars["use_".str_replace(".", "_", $whois_exts[$l])] == "yes") { $use_exts[] = $whois_exts[$l]; }
	}
	
	echo	"\t\"",
		implode("\",\r\n\t\"", $use_exts),
		"\"\r\n);\r\n",
		"\r\n",
		"// should we use the global header and footer templates? (this can be over\r\n",
		"// ridden by a POST or GET variable)\r\n",
		"\$use_global_templates_by_default = ", $vars["global"], ";\r\n",
		"\r\n",
		"?>\r\n";
}

if(!$action) { show_admin_menu(); } else { get_config_file(); }

?>
