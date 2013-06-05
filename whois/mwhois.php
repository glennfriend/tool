<?php

echo "<!-- Powered by MWhois written by Matt Wilson <matt@mattsscripts.co.uk> -->\n";

/*
    MWhois - a Whois lookup script written in PHP and Perl
    Copyright (C) 2000 Matt Wilson <matt@mattsscripts.co.uk>

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

/* Template information stuff
  ----------------------------
  The following strings in your templates are replaced with the description;

	[>DOMAIN<] = domain searching for
	[>FULLDOMAIN<] = full domain name string (domain+ext)
	[>RAWOUTPUT<] = the raw output of the whois query
	[>WHOIS_SERVER<] = the whois server used
	[>AVAIL_LIST<] = a list of the available domains (in global/wizard search mode)
	[>UNAVAIL_LIST<] = a list of the unavailable domains (in global/wizard search mode)
	[>ERROR_MSG<] = the error message produced
	[>EXT<] = the extension if it is set
	[>EXT_HTML_LIST<] = a list of the extensions supported in a html list
	[>EXT_LIST<] = a list of extensions supported

  parameters to the script (no parameters brings up normal search script);

	show_raw=1	= wherther to show the raw output page
	do_wizard=1	= whether the information being passed is for the wizard
	fulldomain=(string)	= the full domain to lookup (don't set domain and ext)
	domain=(string) = do a search for the domain (string)
	list_exts=1	= show the extensions supported page
	do_global=1	= goto the global search page
	do_mini_search=1 = just show the search form without anything else
	company=(string) = used for the wizard, needed in order to search
	keyword1=(string) = used for the wizard, needed in order to search
	keyword2=(string) = used for the wizard, needed in order to search

  If any of this is unclear, see the provided example templates
*/

// include the config file
require "config.php";

// check whether we are going to use the header and footer template files
if(!isset($use_global_templates)) {
	$use_global_templates = $use_global_templates_by_default;	// whether to use the global templates
}

// some extensions (com/net/org) have a server which contains the name of the server which should be used for the information, this simply tells the script to use the whois server as a source for the server info... ;)
$whois_si_servers = array();

// an array of the `whois' servers
$whois_servers = array();

// default whois servers for info
$whois_info_servers = array();

// the backup whois servers to try
$whois_info_servers_backup = array();

// the strings that are returned if the domain is available
$whois_avail_strings = array();

// the page titles
$whois_page_titles = array();

// the template list
$whois_templates = array();

// some substitution strings follow
$errormsg = "";
$titlebar = "MWhois written by Matt Wilson";    // the default title bar
$rawoutput = "";
$avail = array();
$unavail = array();
$whois_server = "";

// the name of the script
$script_name = "mwhois.php";

function my_in_array($val,$array_)
{
	for($l=0; $l<sizeof($array_); $l++) {
		if($array_[$l] == $val) {
			return 1;
		}
	}

	return 0;
}

// in case the theme can't be found
function fatal_theme()
{
	global $theme;

	echo '<HTML><BODY><FONT FACE=VERDANA SIZE=2>MWhois could not load the specified theme `<B>'.$theme.'</B>\', check the directory exists and try again!</FONT></BODY></HTML>';
	exit();
}

// in case the theme can't be found
function fatal_servers_lst()
{
	echo '<HTML><BODY><FONT FACE=VERDANA SIZE=2>MWhois could not locate the `servers.lst\' file in the current directory!</FONT></BODY></HTML>';
	exit();
}

// loads the page titles into a hash
function load_page_titles()
{
	global $whois_page_titles;
	global $theme;
	global $dir_split;
	
	// load the titles.cfg file
	$titles_cfg_file = @file($theme.$dir_split."titles.cfg");
	if(!$titles_cfg_file) { echo "\n<!-- recoverable error : no titles.cfg found in theme dir `$theme' -->"; }

	// go through each line of the titles config file and grab what we want
	for($l=0; $l<sizeof($titles_cfg_file); $l++){
		// trim each line and see if its a comment
		$titles_cfg_file[$l] = trim($titles_cfg_file[$l]);
		if(substr($titles_cfg_file[$l],0,1) == ";") { continue; }
		
		// explode the bits, we can't use the limit paramater in case of PHP<4.0.1
		$bits = explode("|", $titles_cfg_file[$l]);
		
		// check to make sure there are at least two bits
		if(sizeof($bits) < 2) { continue; }
		
		// glue the bits of the line together that are after 2
		$flag = $bits[0];
		$title = $bits[1];
		for($t=0; $t<sizeof($bits)-2; $t++){ $title .= "|".$bits[$t]; }
		
		// put the page title in to the hash array
		$whois_page_titles[$flag] = $title;
		
		// for the sakes of debugging show whats happening
		//echo "\n<!-- added new title for flag `".$flag."' => `".$title."' -->";
	}
}

// loads the template filenames into a hash ref
function load_template_names()
{
	global $whois_templates;
	global $theme;
	global $dir_split;
	
	// load the titles.cfg file
	$template_cfg_file = @file($theme.$dir_split."templates.cfg");
	
	if(!$template_cfg_file) { fatal_theme(); }
	
	// go through each line of the titles config file and grab what we want
	for($l=0; $l<sizeof($template_cfg_file); $l++){
		// trim each line and see if its a comment
		$template_cfg_file[$l] = trim($template_cfg_file[$l]);
		if(substr($template_cfg_file[$l],0,1) == ";") { continue; }
		
		// explode the bits, we can't use the limit paramater in case of PHP<4.0.1
		$bits = explode("|", $template_cfg_file[$l]);
		
		// check to make sure there are at least two bits
		if(sizeof($bits) < 2) { continue; }
		
		// glue the bits of the line together that are after 2
		$flag = $bits[0];
		$template = $bits[1];
		for($t=0; $t<sizeof($bits)-2; $t++){ $template .= "|".$bits[$t]; }
		
		// put the page title in to the hash array
		$whois_templates[$flag] = $theme.$dir_split.$template;
		
		// for the sakes of debugging show whats happening
		//echo "\n<!-- added new template for flag `".$flag."' => `".$template."' -->";
	}
}

// this loads the server info for the extensions in $whois_exts;
function load_server_info()
{
	global $whois_exts;
	global $whois_si_servers;
	global $whois_servers;
	global $whois_info_servers;
	global $whois_info_servers_backup;
	global $whois_avail_strings;
	global $vars;
	
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

		// check to see whether we want this TLD
		if(!my_in_array($es[0], $whois_exts)) { continue; }

		// yes we do, so store the details in the appropriate arrays
		$whois_servers[$es[0]] = $es[1];
		$whois_si_servers[$es[0]] = $es[5];
		$whois_info_servers[$es[0]] = $es[3];
		$whois_info_servers_backup[$es[0]] = $es[4];
		$whois_avail_strings[$es[1]] = $es[2];

		// thats it!
	}
}

function choose_info_server($domain, $ext)
{
	global $whois_info_servers;
	global $whois_si_servers;
	global $whois_server;
	global $whois_servers;
	global $vars;

	$whois_server = "";

	if($whois_si_servers[$ext]){
		if(($co = fsockopen($whois_servers[$ext], 43)) == false){
			//echo "\n<!-- choose_info_server() : unable to connect to ".$whois_servers[$ext]." @ line #".$__LINE__." -->";
			$whois_server = $whois_servers[$ext];
		} else {
			//echo "\n<!-- choose_info_servers() : connected to ".$whois_servers[$ext]." @ line #".$__LINE__.", looking for `".$whois_si_servers[$ext]."' -->";
			fputs($co, $domain.".".$ext."\r\n");
			while(!feof($co)) { $output .= fgets($co,128); }

			fclose($co);

			$he = strpos($output, $whois_si_servers[$ext]) + strlen($whois_si_servers[$ext]);
			$le = strpos($output, "\n", $he);
			$whois_server = substr($output, $he, $le-$he);
			//echo "\n<!-- choose_info_servers() : found `".$whois_server."' @ line #".$__LINE__.", using for whois info server -->";
		}
	} else {
		$whois_server = $whois_info_servers[$ext];
	}

	$whois_server = trim($whois_server);
}

// make all the changes
function make_changes($fil)
{
	global $vars;
	global $errormsg;
	global $titlebar;
	global $rawoutput;
	global $avail;
	global $unavail;
	global $whois_exts;
	global $whois_servers;
	global $script_name;
	global $theme;
	global $dir_split;
	
	$f = implode("", file($fil));

	$f = str_replace("[>WHOIS_SERVER<]",$whois_servers[$vars["ext"]],$f);
	$f = str_replace("[>TITLE_BAR<]",$titlebar,$f);
	$f = str_replace("[>DOMAIN<]",$vars["domain"],$f);
	$f = str_replace("[>FULLDOMAIN<]", $vars["fulldomain"], $f);
	$f = str_replace("[>ERROR_MSG<]",$errormsg,$f);
	$f = str_replace("[>RAWOUTPUT<]",$rawoutput,$f);

	for($l=0; $l<sizeof($avail); $l++){
		$sp[1] = substr(strchr($avail[$l],"."),1);
		$sp[0] = substr($avail[$l],0,strlen($avail[$l])-strlen($sp[1])-1);
		//$avail_s = $avail_s."<a href=\"".$script_name."?domain=".$sp[0]."&ext=".$sp[1]."\">".$avail[$l]."</a><br>";
		$avail_s = $avail_s."<font color=blue>".$avail[$l]."</font><br>";
	}

	 for($l=0; $l<sizeof($unavail); $l++){
                $sp[1] = substr(strchr($unavail[$l],"."),1);
                $sp[0] = substr($unavail[$l],0,strlen($unavail[$l])-strlen($sp[1])-1);
                //$unavail_s = $unavail_s."<a href=\"".$script_name."?domain=".$sp[0]."&ext=".$sp[1]."\">".$unavail[$l]."</a><br>";
				$unavail_s = $unavail_s."<font color=red>".$unavail[$l]."</font><br>";
	}

	$f = str_replace("[>AVAIL_LIST<]",$avail_s,$f);
	$f = str_replace("[>UNAVAIL_LIST<]",$unavail_s,$f);
	$f = str_replace("[>SCRIPT_NAME<]", $script_name, $f);
	$f = str_replace("[>EXT<]",$vars["ext"],$f);
	$f = str_replace("[>EXT_LIST<]",implode("<br>",$whois_exts),$f);
	$f = str_replace("[>EXT_HTML_LIST<]","<select name=\"ext\">\n<option>".implode("\n<option>",$whois_exts)."\n</select>",$f);

	return $f;
}

// show the error page
function do_error()
{
	global $use_global_templates;
	global $vars;
	global $titlebar;
	global $errormsg;
	global $whois_templates;
	global $whois_page_titles;
	
	$titlebar = $whois_page_titles["error"];

	if($use_global_templates) { echo make_changes($whois_templates["header"]); }
	echo make_changes($whois_templates["error"]);
	if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

// checks the domain is legal
function check_domain()
{
	global $errormsg;
	global $vars;
	global $whois_exts;

	if(isset($vars["ext"])
		&& !isset($vars["do_global"]) 
		&& !isset($vars["do_wizard"]) ){
		if(!strlen($vars["ext"])){
			$errormsg = "No top level domain selected";
			return 0;
		}
		if(!my_in_array($vars["ext"], $whois_exts)){
			$errormsg = "Top level domain not supported";
			return 0;
		}
	}
	if(isset($vars["domain"])){
		if(@ereg("^-|-$",$vars["domain"])){
			$errormsg = "Domain names cannot begin or end in a hyphen or contain double hyphens";
			return 0;
		}
		if(!@ereg("([a-z]|[A-Z]|[0-9]|-){".strlen($vars["domain"])."}",$vars["domain"]) || !strlen($vars["domain"])){
			$errormsg = "Domain names must only contain alphanumerical characters and hyphens";
			return 0;
		}
	}

	return 1;
}

// perform_whois function returns 1 if domain is available otherwise returns either the raw info or 0
function perform_whois($domainname, $ext, $raw)
{
	global $vars;
	global $errormsg;
	global $whois_servers;
	global $rawoutput;
	global $whois_avail_strings;

	$rawoutput = "";

	if($raw) { return do_raw($domainname, $ext); }

	if(($ns = fsockopen($whois_servers[$ext], 43)) == false){
		$errormsg = "Cannot connect to <b><i>".$whois_servers[$ext]."</i></b>";
		return -1;
	}
	fputs($ns, $domainname.".".$ext."\r\n");
	while(!feof($ns)) { $rawoutput .= fgets($ns,128); }

	fclose($ns);

	$whois_avail_strings[$whois_servers[$ext]] = str_replace("\\n", "\n", $whois_avail_strings[$whois_servers[$ext]]);
//	echo "<!--\nAvail string = \"".$whois_avail_strings[$whois_servers[$ext]]."\"\nComparing against = \"".$rawoutput."\"\n-->\n";

	$tmp = strpos($rawoutput, $whois_avail_strings[$whois_servers[$ext]]);
	if(!strlen($rawoutput) || is_integer($tmp)) { return 1; }

	return 0;
}

// this performs the whois lookup and then shows the data returned
function do_raw($domainname, $ext)
{
	global $vars;
	global $titlebar;
	global $use_global_templates;
	global $whois_info_servers;
	global $whois_servers;
	global $rawoutput;
	global $errormsg;
	global $whois_info_servers_backup;
	global $whois_avail_strings;
	global $whois_server;
	global $whois_page_titles;
	global $whois_templates;
	
	choose_info_server($domainname, $ext);

	if(($ns = fsockopen($whois_server,43)) == false){
		if(($ns = fsockopen($whois_info_servers[$ext],43)) == false){
			if(($ns = fsockopen($whois_info_servers_backup[$ext], 43)) == false){
	                	return -1;
			} else {
				$whois_server = $whois_info_servers_backup[$ext];
			}
		} else {
			$whois_server = $whois_info_servers[$ext];
		}
	}

	//print "\n<!-- do_raw() : using `".$whois_server."' for whois query -->";

        fputs($ns, $domainname.".".$ext."\r\n");
        while(!feof($ns)) { $rawoutput .= fgets($ns, 128); }

        fclose($ns);

	//echo "<!--".$rawoutput."-->";

	$titlebar = $whois_page_titles["raw"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }
        echo make_changes($whois_templates["raw"]);
        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

function do_getsearch_mini()
{
	global $whois_templates;
        echo make_changes($whois_templates["searchmini"]);
	exit();
}

function do_getsearch()
{
        global $use_global_templates;
        global $titlebar;
        global $whois_page_titles;
	global $whois_templates;
	
        $titlebar = $whois_page_titles["search"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }
        echo make_changes($whois_templates["search"]);
        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

        exit();	
}

function do_avail()
{
	global $use_global_templates;
	global $titlebar;
	global $whois_templates;
	global $whois_page_titles;
	
        $titlebar = $whois_page_titles["avail"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }
        echo make_changes($whois_templates["avail"]);
        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

function do_taken()
{
	global $use_global_templates;
	global $titlebar;
	global $whois_page_titles;
	global $whois_templates;

        $titlebar = $whois_page_titles["taken"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }
        echo make_changes($whois_templates["taken"]);
        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

function do_exts()
{
        global $use_global_templates;
	global $whois_page_titles;
        global $titlebar;
	global $whois_templates;

        $titlebar = $whois_page_titles["exts"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }
        echo make_changes($whois_templates["extlist"]);
        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

        exit();
}

function do_glob()
{
	global $vars;
	global $whois_exts;
	global $avail;
	global $unavail;
        global $use_global_templates;
        global $titlebar;
        global $whois_page_titles;
	global $whois_templates;

	$titlebar = $whois_page_titles["global"];

	if($use_global_templates) { echo make_changes($whois_templates["header"]); }

	if(!isset($vars["domain"])) {
		echo make_changes($whois_templates["global"]);
	} else{
		for($l=0; $l<sizeof($whois_exts); $l++){
			if(($r = perform_whois($vars["domain"], $whois_exts[$l], 0)) != -1){
				if(!$r) { $unavail[] = $vars["domain"].".".$whois_exts[$l]; } else { $avail[] = $vars["domain"].".".$whois_exts[$l]; }
			}
		}

		echo make_changes($whois_templates["globalres"]);
	}

	if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

function do_wiz()
{
	global $vars;
        global $avail;
        global $unavail;
        global $use_global_templates;
        global $titlebar;
	global $errormsg;
	global $whois_templates;
	global $whois_page_titles;

        $titlebar = $whois_page_titles["wiz"];

        if($use_global_templates) { echo make_changes($whois_templates["header"]); }

	if(!$vars["company"] && !$vars["keyword1"] && !$vars["keyword2"]) {
		echo make_changes($whois_templates["wizard"]);
	} else{
		// remove whitespace from either side of each variable
		$vars["company"] = strtolower(trim($vars["company"]));
		$vars["keyword1"] = strtolower(trim($vars["keyword1"]));
		$vars["keyword2"] = strtolower(trim($vars["keyword2"]));

		$cdomains = array(
			$vars["company"],
			$vars["company"].$vars["keyword1"],
			$vars["company"]."-".$vars["keyword1"],
			$vars["keyword1"].$vars["company"],
			$vars["keyword1"]."-".$vars["company"],
                        $vars["company"].$vars["keyword2"],
                        $vars["company"]."-".$vars["keyword2"],
                        $vars["keyword2"].$vars["company"],
                        $vars["keyword2"]."-".$vars["company"],
			$vars["keyword1"],
			$vars["keyword2"],
			$vars["keyword1"].$vars["keyword2"],
			$vars["keyword2"].$vars["keyword1"],
			$vars["keyword1"]."-".$vars["keyword2"],
			$vars["keyword2"]."-".$vars["keyword1"]
		);

		// remove any duplicates :)
		$domains = @array_unique($cdomains);

		for($l=0; $l<sizeof($domains); $l++){
			$vars["domain"] = $domains[$l];
			if(check_domain()){
				if(($r = perform_whois($vars["domain"], $vars["ext"], 0)) != -1 && strlen($vars["domain"]) > 0){
					if(!$r) { $unavail[] = $vars["domain"].".".$vars["ext"]; } else { $avail[] = $vars["domain"].".".$vars["ext"]; }
				}
			}
		}

                echo make_changes($whois_templates["wizres"]);
        }

        if($use_global_templates) { echo make_changes($whois_templates["footer"]); }

	exit();
}

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

// try find out the script's path, if the first character is / then assume
// that the directory splitter is that too (otherwise use \)
$dir_split = "/";
if($_ENV["DOCUMENT_ROOT"] != "") {
	if(substr($_ENV["DOCUMENT_ROOT"], 0, 1) != "/") {
		$dir_split = "\\";
		echo "\n<!-- Using dir_split=\"", $dir_split, "\" -->";
	}
}

echo "\n<!-- Using dir_split=\"", $dir_split, "\" -->";

if(!isset($vars["domain"]) && !isset($vars["ext"]) && isset($vars["fulldomain"])) {
	$vars["domain"] = ereg_replace("\..*$", "", $vars["fulldomain"]);
	$vars["ext"] = str_replace($vars["domain"].".", "", $vars["fulldomain"]);
}

if(!isset($vars["fulldomain"]) && isset($vars["ext"]) && isset($vars["domain"])) {
	$vars["fulldomain"] = $vars["domain"].".".$vars["ext"];
}

load_server_info();
load_template_names();
load_page_titles();

if(!check_domain()) { do_error(); }
if($vars["do_wizard"]) { do_wiz(); }
if($vars["do_global"]) { do_glob(); }
if($vars["list_exts"]) { do_exts(); }
if($vars["do_mini_search"]) { do_getsearch_mini(); }
if(!$vars["domain"]) { do_getsearch(); }

if(isset($vars["show_raw"])){
	if(perform_whois($vars["domain"], $vars["ext"], 1) != -1) {
		$errormsg = "MWhois internal error";
	}
	do_error();
}

$ret = perform_whois($vars["domain"], $vars["ext"], 0);
if($ret == -1) {
	do_error();
}
if(!$ret) {
	do_taken();
} else {
	do_avail();
}

?>
