<?php 
	class User
	{
		function get_id($email)
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

		function check_email($email)
		{
			$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
			preg_match($regex, $email, $result);
			if (empty($result))
				return false;
			else
				return true;
		}

		function is_exist_email($email)
		{
			$db = new mysqli("localhost", "root", "20021", "Cloud");
			$query = $db->query("Select * From Users;");
			while($users = $query->fetch_assoc())
				{
					if (($users['email'] == $email))
						{
							return true;
						};
				}
			$db->close();
			return false;
		}

		function authorization($email, $password)
		{
			$db = new mysqli("localhost", "root", "20021", "Cloud");
			$query = $db->query("Select * From Users;");
			while($users = $query->fetch_assoc())
			{
				if (($users["email"] == $email) && (password_verify($password, $users["pas"])))
			 	{
			 		header("location: ../files/files.php");
			 		unset($_SESSION["error"]);
			 		$db->close();
			 		return true;
			 	};
			}
			$db->close();
			return false;
		}
		
		function new_user($email, $password)
		{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$db = new mysqli("localhost", "root", "20021", "Cloud");
			$db->query("Insert into Users (email, pas) values ('".$email."', '".$password."');");
			$db->close();
			mkdir("../disc/".$_POST["email"]);
		}

		function clear_text($text)
		{
			$text = htmlentities($text, ENT_QUOTES, "UTF-8");
			$text = str_replace(" ", "", $text);
			return $text;
		}
	}
?>