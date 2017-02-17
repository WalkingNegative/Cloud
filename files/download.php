<?php
	header("Content-Type: text/html; charset=utf-8");

	include_once("user.class.php");

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/files/files.php")
	{
		header("location: ../files.php");
		exit;
	}

	function file_download($file) {
		if (file_exists($file)) {
			if (ob_get_level()) {
				ob_end_clean();
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}

	session_start();
	//if (IsOwner($_GET["path"], GetId($_SESSION["email"])))
	//{
		file_download($_GET["path"]);
		header("location: ../files.php");
	//}
	//else
	//{
		//header("location: ../index.php");
	//}
?>