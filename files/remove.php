<?php
	$path = $_GET["path"];
	unlink($path);
	$db=@mysql_connect("localhost", "root", "20021");
	mysql_select_db("Cloud", $db);
	@mysql_query("delete from Files where path = '".$path."';");
	mysql_close($db);
	header("location: files.php");
?>