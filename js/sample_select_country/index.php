<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>Chained Selects</title>
<script language="javascript" src="chainedselects.js"></script>
<script language="javascript" src="country_config.js"></script>
</head>

<body onload="initListGroup('vehicles', document.forms[0].f1, document.forms[0].f2, document.forms[0].f3, 'cs')">

<form method=post>
<table border=1 bgcolor=abcdef ><tr>
<td>Select a country:&nbsp;</td>
<td><select name="f1" style="width:160px;"></select></td>
<td><select name="f2" style="width:160px;"></select></td>
<td><select name="f3" style="width:160px;"></select></td>
<td><input type="button" value="Reset" onclick="resetListGroup('vehicles')"></td>
</tr></table>
<input type=submit>
</form>

<?
echo '<pre>';
print_R($_POST);
?>
