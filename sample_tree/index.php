<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>DHTML Tree samples. dhtmlXTree - Collapse/Expand</title>
</head>
<style>
	body {font-size:12px}
	.{font-family:arial;font-size:12px}
	h1 {cursor:hand;font-size:16px;margin-left:10px;line-height:10px}
	xmp {color:green;font-size:12px;margin:0px;font-family:courier;background-color:#e6e6fa;padding:2px}
</style>
<body>
	<h1>Collapse/Expand</h1>
	<link rel="STYLESHEET" type="text/css" href="dhtmlXTree.css">
	<script  src="dhtmlXCommon.js"></script>
	<script  src="dhtmlXTree.js"></script>		
	<script>
		function closeAllRoots(){
			var rootsAr = tree.getSubItems(0).split(",")
			for(var i=0;i<rootsAr.length;i++){
				tree.closeAllItems(rootsAr[i])
			}
		}
	</script>
	<table>
		<tr>
			<td valign="top">


				<div id="treeboxbox_tree" style="width:250; height:218;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div>
			</td>
			<td rowspan="2" style="padding-left:25" valign="top">
			
			
			<a href="javascript:void(0);" onclick="tree.openAllItems(0);">Expand all</a><br><br>
			<a href="javascript:void(0);" onclick="tree.closeAllItems(0);">Collapse all</a><br><br>
			<a href="javascript:void(0);" onclick="tree.closeItem(tree.getSelectedItemId());">Close selected item</a><br><br>
			<a href="javascript:void(0);" onclick="tree.openItem(tree.getSelectedItemId());">Open selected item</a><br><br>			
			<a href="javascript:void(0);" onclick="tree.closeAllItems(tree.getSelectedItemId());">Collapse selected branch</a><br><br>
			<a href="javascript:void(0);" onclick="tree.openAllItems(tree.getSelectedItemId());">Expand selected branch</a><br><br>

			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr>	
<XMP>
<div id="treeboxbox_tree" style="width:200;height:200"></div>
<script>
			tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
			tree.setImagePath("./imgs/");
			tree.loadXML("tree.xml");
			
			....
			//expand all
			tree.openAllItems(0);
			//open item
			tree.openItem(id);
			//close item
			tree.closeItem(id);
			//open branch
			tree.openAllItems(id);
			//close branch
			tree.closeAllItems(id);									
</script>
</XMP>	

	<script>
			tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
			tree.setImagePath("./imgs/");
			tree.loadXML("tree3.xml");

	</script>
<br><br>

</body>
</html>
