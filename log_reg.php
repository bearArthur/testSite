<?php	
	function log_in ($text) {
		$pdo = db_connect();	
		$login = $_POST['log_login'];
		$pass = md5($_POST['log_pass']);			
		$sql_query = $pdo->prepare("SELECT `users`.`id`, `users_data`.`id` as `users_id`, `users`.`login`, `users`.`password` FROM users, users_data WHERE users.login = '$login' and users.password = '$pass' and users_data.id = users.id");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();		
		if (!empty($db_data)) {
			$sql_query = $pdo -> prepare("SELECT id FROM ban_list WHERE id = {$db_data->id}");
			$sql_query->execute();
			$db_data_c = $sql_query->fetchobject();
			if ($db_data_c == NULL) {
				$_SESSION['id_page'] = $db_data->id;
				$_SESSION['id'] = $db_data->id;
				$_SESSION['page'] = 1;
				$sql_query = $pdo->prepare("SELECT id FROM admin WHERE id = {$db_data->id}");
				$sql_query->execute();
				$db_data_c = $sql_query->fetchobject();
				if ($db_data_c != NULL) {
					$_SESSION['admin'] = true;
				}
				else {
					$_SESSION['admin'] = false;
				}
				unset($pdo,$sql_query,$login,$pass,$db_data,$db_data_c);
				return '';
			}
			else {
				unset($pdo,$sql_query,$login,$pass,$db_data,$db_data_c);
				return $text['l_ban'];
			}
		}
		else {
			unset($pdo,$sql_query,$login,$pass,$db_data,$db_data_c);
			return $text['l_log_er'];
		}
	}

  /**
   * Реєстрація нового користувача
   *
   * @param strintg $a
   *   ASdasdasdasd
   *
   * @return якщо виконується - повідомлення про успішну реєстрацію, якщо помилка - повідомлення про помилку
   */
	function register($text) {
		$pdo = db_connect();		
		$name = htmlspecialchars(trim($_POST['reg_name']));
		$surname = $_POST['reg_surname'];
		$login = $_POST['reg_login'];
		$pass = $_POST['reg_pass'];
		$rpass = $_POST['reg_rpass'];
		$sql_query = $pdo -> prepare("SELECT login FROM users WHERE login = '$login'"); 
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		if (!preg_match("^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^",$login)) {
			unset($pdo,$db_data,$sql_query);
			return $text['r_emale_er'];
		}
		elseif ($db_data) {
			unset($pdo,$db_data,$sql_query);
			return $text['r_emale_is'];
		}
		elseif ($rpass != $pass) {
			unset($pdo,$db_data,$sql_query);
			return $text['r_pass_er'];
		}
		else {
			$pass = md5($pass);
			$sql_query = $pdo -> prepare("INSERT INTO users (login,password) VALUES (?,?)");
			$db_data = array($login,$pass);
			$sql_query -> execute($db_data);
			$sql_query = $pdo -> prepare("SELECT * FROM `users` WHERE `login` = '$login'");
			$sql_query -> execute();
			$db_data = $sql_query -> fetchobject();
			$sql_query = $pdo -> prepare("INSERT INTO users_data (id,name,surname) VALUES (?,?,?)");
			$db_data = array($db_data -> id,$name,$surname);
			$sql_query -> execute($db_data);
			unset($pdo,$db_data,$sql_query,$name,$surname,$login,$pass,$rpass);					
		}
		return $text['r_access'];
	}

	function log_out () {
		$lg = $_SESSION['lang'];
		session_destroy();
		header("Location: index.php?lg={$lg}");
		return 0;
	}	
?>