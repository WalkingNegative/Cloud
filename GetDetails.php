<?php 
	function GetId($email)
	{
		$db = new mysqli("localhost", "root", "20021", "Cloud");
		$query = $db->query("select * from Users;");
		while($users = $query->fetch_assoc())
		{
			if ($users["email"] == $email)
			{
				return $users["id_user"];
				$db->close();
			}
		}
	}

	function CheckEmail($email)
	{
		$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
		preg_match($regex, $email, $result);
		if (empty($result) == false)
			return true;
		else
			return false;
	}

	function IsOwner($file, $id)
	{
		$db = new mysqli("localhost", "root", "20021", "Cloud");
		$query = $db->query("select * from Files;");
		while($files = $query->fetch_assoc())
		{
			if (($files["path"] == $file) && ($files["id_user"] == $id))
			{
				return true;
				$db->close();
			}
		}
		return false;
	}
?>