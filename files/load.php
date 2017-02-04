<?php
	function GetId($email){
		$db = @mysql_connect("localhost", "root", "20021");
		mysql_select_db("Cloud", $db);
		$query = mysql_query("select * from Users where email = '".$email."';");
		while($users = @mysql_fetch_array($query))
		{			
			if ($users["email"] == $email)
			{
				return $users["id_user"];
				mysql_close($db);
			}
		}
	}

	session_start();
	$uploaddir = "../disc/".$_SESSION["email"]."/";
	if($_FILES["filename"]["size"] > 209715200)
   {
    	echo ("Размер файла превышает 200 мегабайт");
    	exit;
   }
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
   		$id = GetId($_SESSION["email"]);
   		$size = round($_FILES["filename"]["size"]/1048576, 2);
   		$path = $uploaddir.$_FILES["filename"]["name"];
   		echo $id;
   		move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
	    $db=@mysql_connect("localhost", "root", "20021");
		mysql_select_db("Cloud", $db);
		@mysql_query("insert into Files (file_name, path, id_user, size) values ('".$_FILES["filename"]["name"]."', '".$path."', ".$id.", ".$size.");");
		mysql_close($db);
    	header("location: files.php");
   } else {
    	echo("Ошибка загрузки файла");
   }
?>