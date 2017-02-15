<?php
	header("Content-Type: text/html; charset=utf-8");
	include_once("../GetDetails.php");

	function AddFile($uploaddir,$id)
	{
		if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   		{
	   		$size = round($_FILES["filename"]["size"]/1048576, 2);
	   		$path = $uploaddir.$_FILES["filename"]["name"];
	   		echo $id;
	   		move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
			$db=new mysqli("localhost", "root", "20021", "Cloud");
			$db->query("insert into Files (file_name, path, id_user, size) values ('".$_FILES["filename"]["name"]."', '".$path."', ".$id.", ".$size.");");
			$db->close();
  		} 
  		else 
  		{
    		$_SESSION["error"] = "Ошибка загузки файла";
		}
	}

	function CheckType($name)
	{
		$type = new SplFileInfo($name);
		$types = array ("com","bat","js","php","exe","cmd","vb","vbs");
		foreach ($types as $value) {
			if ($value == ($type->getExtension()))
				return true;
		}
		return false;
	}

	session_start();
	$uploaddir = "../disc/".$_SESSION["email"]."/";
	if($_FILES["filename"]["size"] > 209715200)
   {
    	echo ("Размер файла превышает 200 мегабайт");
    	exit;
   }

   if (!CheckType($_FILES["filename"]["name"]))
		AddFile($uploaddir,GetId($_SESSION["email"]));
	else
		$_SESSION["error"] = "Неподдерживаемый формат!"; 
	header("location: files.php");
?>