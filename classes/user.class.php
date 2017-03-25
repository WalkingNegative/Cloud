<?php
	require_once("../config.php.ini");
	require_once("abstractmodel.class.php");

	class User extends AbstractModel
	{
		protected $db;
		
		public function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		}

		public function getId($email)
		{
			$query = $this->db->query("select * from Users;");
			while($users = $query->fetch_assoc()) {
				if ($users["email"] == $email) {
					return $users["id_user"];
				}
			}
		}

		public function getInfo($id)
		{
			$query = $this->db->query("select * from Users;");
			while($users = $query->fetch_assoc()) {
				if ($users["id_user"] == $id) {
					return [$users["name"], $users["surname"], $users["email"]];
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
			$query = $this->db->query("Select * From Users;");
			while($users = $query->fetch_assoc()) {
				if (($users['email'] == $email)) {
					return true;
				}
			}
			return false;
		}

		public function authorization($email, $password)
		{
			if (!$this->checkEmail($email) || (strlen($password) < 8)) {
				return false;
			}
			$query = $this->db->query("Select * From Users;");
			while($users = $query->fetch_assoc()) {
				if (($users["email"] == $email) && password_verify($password, $users["password"])) {
			 		return true;
			 	}
			 }
			unset($_SESSION["error"]);
			return false;
		}

		public function newUser($email, $password, $name, $surname)
		{
			if (!$this->checkEmail($email) || (strlen($password) < 8) || $this->isExistEmail($email))
			{
				return false;
			}
			$password = password_hash($password, PASSWORD_DEFAULT);
			echo $email." ".$password." ".strlen($password);
			$stmt = $this->db->prepare("Insert into Users (email, password, name, surname) values (?, ?, ?, ?);");
			$stmt->bind_param("ssss", $email, $password, $name, $surname);
			$stmt->execute();
			if (!file_exists(DIR_DISC.$email)) {
				mkdir(DIR_DISC.$email);
			}
			unset($_SESSION["error"]);
			return true;
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
			$db = parent::connectBd();  
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
			return $this->db->query("select * from Sessions order by id_user;");
		}

		public function getAllUsers()
		{ 
			return $this->db->query("select * from Users order by id_user;");
		}

	}
