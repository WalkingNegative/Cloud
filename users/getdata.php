	<?php
		function CheckEmail($email){
			$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
			preg_match($regex, $email, $result);
			if (empty($result) == false)
				return true;
			else
				return false;
		}

		if ((CheckEmail($_POST["email"]) == true) && (strlen($_POST["password"]) > 7))
		{
	 		session_start();
	 		$_SESSION["email"] = $_POST["email"];
	 		$_SESSION["password"] = $_POST["password"];
	 		unset($_SESSION["error"]);
	 		header("location: auth.php");
	 	}
	 	else 
	 	{
	 		session_start();
	 		$_SESSION["error"] = "Неверный формат ввода";
	 		header("location: ../index.php");
	 	}
	?>