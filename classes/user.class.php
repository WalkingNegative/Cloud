<?php
	include_once("../config.php.ini");

	class User
	{
		public function getId($email)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("select * from Users;");
			while($users = $query->fetch_assoc()) {
				if ($users["email"] == $email) {
					return $users["id_user"];
				}
			}
		}

		public function getEmail($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("select * from Users;");
			while($users = $query->fetch_assoc()) {
				if ($users["id_user"] == $id) {
					return $users["email"];
				}
			}
			return false;
		}

		public function checkEmail($email)
		{
			$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
			preg_match($regex, $email, $result);
			if (empty($result)) {
				return false;
			} else  {
				return true;
			}
		}

		public function isExistEmail($email)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("Select * From Users;");
			while($users = $query->fetch_assoc()) {
				if (($users['email'] == $email)) {
					return true;
				}
			}
			$db->close();
			return false;
		}

		public function authorization($email, $password)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("Select * From Users;");
			while($users = $query->fetch_assoc()) {
				if (($users["email"] == $email) && password_verify($password, $users["pas"]))
			 	{
			 		$db->close();
			 		return true;
			 	}
			 }
			$db->close();
			return false;
		}

		public function newUser($email, $password)
		{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$db->query("Insert into Users (email, pas) values ('".$email."', '".$password."');");
			$db->close();
		}

		public function clearText($text)
		{
			$text = htmlentities($text, ENT_QUOTES, "UTF-8");
			return $text;
		}
	}
