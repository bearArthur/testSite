<?php	
	function log_in ($text) {
		$pdo = db_connect();	
		$email = $_POST['log_email'];
		$_SESSION['log_email'] = $_POST['log_email'];
		$pass = md5($_POST['log_pass']);			
		$sql_query = $pdo->prepare("SELECT * FROM users, users_data WHERE `users`.`email` = '$email' and `users`.`password` = '$pass' and `users`.`id` = `users_data`.`id`");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();		
		if (!empty($db_data)) {
			$sql_query = $pdo->prepare("SELECT id FROM ban_list WHERE id = {$db_data->id}");
			$sql_query->execute();
			$db_data_c = $sql_query->fetchobject();
			if (!$db_data_c) {
				$_SESSION['id_page'] = $db_data->id;
				$_SESSION['id'] = $db_data->id;
				$_SESSION['page'] = 1;
	  		$_SESSION['last_login'] = $db_data->last_login;
	  		$_SESSION['registered'] = $db_data->registered;
	  		$_SESSION['email'] = $db_data->email;
				$_SESSION['role'] = $db_data->role;
				$_SESSION['lang'] = $_GET['lg'];
				$_SESSION['user_role'] = $db_data->role;;
				$sql_query = $pdo->prepare("UPDATE users_data SET last_login = NOW()");
				$sql_query->execute();
				unset($pdo, $sql_query, $login, $pass, $db_data, $db_data_c);
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
		$login = htmlspecialchars($_POST['reg_login']);
		$email = htmlspecialchars($_POST['reg_email']);
		$pass = htmlspecialchars($_POST['reg_pass']);
		$rpass = $_POST['reg_rpass'];
		$_SESSION['reg_login'] = $_POST['reg_login'];
		$_SESSION['reg_email'] = $_POST['reg_email'];
		$sql_query = $pdo->prepare("SELECT login, email FROM users WHERE login = '$login' or email = '$email'"); 
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		if (!preg_match("^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^",$email)) {
			unset($pdo, $db_data, $sql_query);
			return $text['r_emale_er'];
		}
		elseif ($db_data) {
			unset($pdo, $db_data, $sql_query);
			return $text['r_emale_is'];
		}
		elseif ($rpass != $pass) {
			unset($pdo, $db_data, $sql_query);
			return $text['r_pass_er'];
		}
		else {
			$pass = md5($pass);
			$sql_query = $pdo->prepare("INSERT INTO users (login, password, email, role) VALUES (?, ?, ?, 1)");
			$db_data = array($login,$pass,$email);
			$sql_query->execute($db_data);
			$sql_query = $pdo->prepare("SELECT id FROM users WHERE login = '$login'");
			$sql_query->execute();
			$db_data = $sql_query->fetchobject();
			$_SESSION['id_page'] = $db_data->id;
			$_SESSION['id'] = $db_data->id;
			$_SESSION['page'] = 1;
			$_SESSION['role'] = 1;
			$_SESSION['user_role'] = 1;
			$_SESSION['lang'] = $_GET['lg'];
			$sql_query = $pdo -> prepare("INSERT INTO users_data (id, last_login, registered) VALUES ({$db_data->id}, NOW(), NOW())");
			$sql_query->execute();	
			$sql_query = $pdo->prepare("SELECT * FROM users,users_data WHERE users.id = {$_SESSION['id']} and users_data.id = {$_SESSION['id']}");
			$sql_query->execute();
			$db_data = $sql_query->fetchobject();		
  		$_SESSION['last_login'] = $db_data->last_login;
  		$_SESSION['registered'] = $db_data->registered;
  		$_SESSION['email'] = $db_data->email;
			unset($pdo, $db_data, $sql_query, $login, $pass, $rpass);					
		}
		return $text['r_access'];
	}

	function log_out () {
		session_destroy();
		// unset($_SESSION['id'], $_SESSION['id_page'], $_SESSION['name'], $_SESSION['surname'], $_SESSION['role'], $_SESSION['user_role']);
		return 0;
	}	
?>