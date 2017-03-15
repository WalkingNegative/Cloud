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
			unset($_SESSION["error"]);
			return false;
		}

		public function newUser($email, $password)
		{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$stmt = $db->prepare("Insert into Users (email, pas) values (?, ?);");
			$stmt->bind_param("ss", $email, $password);
			$stmt->execute();
			if (!file_exists(DIR_DISC.$email)) {
				mkdir(DIR_DISC.$email);
			}
			$db->close();
			unset($_SESSION["error"]);
		}

		public function clearText($text)
		{
			$text = str_replace(" ", "", $text);
			$text = htmlentities($text, ENT_QUOTES, "UTF-8");
			return $text;
		}

		public static function checkUsersOnline()
		{
			$id_session = session_id(); 
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$stmt = $db->prepare("select * from Sessions where id_session = ?"); 
			$stmt->bind_param("s", $id_session);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) { 
				$stmt = $db->prepare("update Sessions set putdate = now(), id_user = ? WHERE id_session = ?");
				$stmt->bind_param("is", $_SESSION["id_user"], $id_session);
				$stmt->execute();
			} else { 
		        $stmt = $db->prepare("insert into Sessions values(?, NOW(), ?);");
				$stmt->bind_param("si", $id_session, $_SESSION["id_user"]);
				$stmt->execute();
			}  
			$db->query("delete from Sessions  where putdate < NOW() -  interval '15' minute"); 
		}

		public function getUsersOnline()
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$result = $db->query("select * from Sessions order by id_user;"); 
			$db->close();
			return $result;
		}

	}
