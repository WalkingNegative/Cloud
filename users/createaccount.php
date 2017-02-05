<?php
			function CheckEmail($email){
				$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
				preg_match($regex, $email, $result);
				if (empty($result) == false)
					return true;
				else
					return false;
			}

			function IsExistEmail($email)
			{
				$db = @mysql_connect("localhost", "root", "20021");
				if (!($db))
					echo "Не удалось подключиться!";
				mysql_select_db("Cloud", $db);
				$query = @mysql_query("Select * From Users;");
				while($users = mysql_fetch_array($query))
				{
					if (($users['email'] == $email))
						{
							return true;
						};
				}
				mysql_close($db);
				return false;
			}

			function NewUser($email, $password)
			{
				$db = @mysql_connect("localhost", "root", "20021");
				if (!($db))
					echo "Не удалось подключиться!";
				mysql_select_db("Cloud", $db);
				mysql_query("Insert into Users (email, pas) values ('".$email."', '".$password."');");
				mysql_close($db);
				mkdir("../disc/".$_POST["email"]);
			}
			
			session_start();

			if ((CheckEmail($_POST["email"]) == true) && (strlen($_POST["password"]) > 7) && (IsExistEmail($_POST["email"]) == false))
				{
					NewUser($_POST["email"], $_POST["password"]);
					$_SESSION["email"] = $_POST["email"];
					$_SESSION["password"] = $_POST["password"];
					header("location: ../files/files.php");
				}
				else
					{
						//do it: print error
						header("location: registration.php");
					}
		?>