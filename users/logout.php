<?php  
	session_start();
	session_destroy();
	header("location: ".PAGE_START);
?>