<?php
	header("Content-Type: text/html; charset=utf-8");
	$path = $_GET["path"];
	unlink($path);
	$db = new mysqli("localhost", "root", "20021", "Cloud");
	$db->query("delete from Files where path = '".$path."';");
	$db->close();
	header("location: ../files.php");
?>