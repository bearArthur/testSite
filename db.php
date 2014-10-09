<?php
	function db_connect(){
		try {  
			$pdo = new PDO('mysql:host=localhost;dbname=db_first;charset=utf8', 'root', 'vlar2210373');
			$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch(PDOException $e) {
			echo $e->getMessage();
			exit;
		}
		return $pdo;
	}

	function message_in($id_page, $id_user){
		$message = htmlspecialchars($_POST['send_mess']);
		$message = nl2br($message);
		$capt = substr(htmlspecialchars($_POST['send_capt']), 0, 100);
		$pdo = db_connect();
		$sql_query = $pdo->prepare("INSERT INTO messages(id_page, id_user, message, capt, date) VALUES (?, ?, ?, ?, NOW())");
		$db_data = array($id_page, $id_user, $message, $capt);
		$sql_query->execute($db_data);
		unset($pdo, $message, $sql_query, $db_data);
		return 0;
	}

	function message_out($count){	
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("SELECT users.login, users_data.photo, messages.message,messages.capt, messages.id, messages.id_user, messages.id_page, messages.date FROM users_data, messages, users WHERE messages.id_user = users_data.id and messages.id_page = {$_SESSION['id_page']} and users.id = messages.id_user ORDER BY `date` DESC LIMIT {$count},10 ");
		$sql_query -> execute();
		$db_data = $sql_query -> fetchAll();
		unset($pdo,$sql_query);
		return $db_data;
	}	

	function message_del($id){
		$pdo = db_connect();
		$sql_query = $pdo->prepare("DELETE FROM `messages` WHERE `messages`.`id` = {$id}");
		$sql_query->execute();
		return 0;
	}

	function get_row_count($id_page){	
		$pdo = db_connect();	
		$sql_query = $pdo -> prepare("SELECT COUNT(1) FROM messages WHERE messages.id_page = {$id_page}");
		$sql_query -> execute();
		$row_count = $sql_query -> fetch();
		return $row_count;
	}

	function get_users() {
		$pdo = db_connect();
		if (isset($_SESSION['id'])) {
			$sql_query = $pdo -> prepare("SELECT * FROM users, users_data WHERE users.id = users_data.id and users.id != {$_SESSION['id']}");
		}
		else {
			$sql_query = $pdo -> prepare("SELECT * FROM users, users_data WHERE users.id = users_data.id");
		}
		$sql_query -> execute();
		$db_data = $sql_query -> fetchall();
		return $db_data;
	}

	function gen_name($format) {
		$char = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
		$char_num = strlen($char);
		$pdo = db_connect();
		do {
			unset($sql_query,$db_data);
			$string = '';
			for ($i = 0; $i <= 9; $i++) {
				$n = substr($char, rand(1, $char_num) - 1, 1);
				$string = $string.$n;
			}
			$sql_query = $pdo -> prepare("SELECT * FROM `images` WHERE `path` = '/var/www/html/test/images/users/{$string}.{$format}'");
			$sql_query -> execute();
			$db_data = $sql_query -> fetchall();
		}
		while ($db_data != NULL);
		unset($pdo,$sql_query,$db_data);
		return $string.$format;
	}

	function upload_image($id) {
		$pdo = db_connect();
		$path = $_FILES['userfile']['name'];
		$pos = strrpos($path,'.');
		$format = substr($path,$pos);
		$pass = md5($_POST['ti_pass']);
		$sql_query = $pdo -> prepare("SELECT password FROM users WHERE id = {$id}");
		$sql_query -> execute();
		$db_data = $sql_query->fetchobject();
		if ($pass == $db_data->password) {
			if ($_FILES['userfile']['size'] <= 1024*1024*10) {
					$name = gen_name($format);
					$path = '/var/www/html/test/testSite/images/users/'.$name;
					if (move_uploaded_file($_FILES['userfile']['tmp_name'],$path)) {					
						$pdo = db_connect();
						$sql_query = $pdo -> prepare("INSERT INTO `images`(id_user,path) VALUES (?,?)");
						$db_data = array($_SESSION['id_page'],$path);
						$sql_query -> execute($db_data);	
						$db_data = 'images/users/'.$name;
						$sql_query = $pdo->prepare("UPDATE `users_data` SET `photo` = '{$db_data}' WHERE id = {$_SESSION['id_page']}");
						$sql_query -> execute();
					}						
			}
		}
		unset($pdo,$sql_query,$db_data,$path,$pos,$format);
		return 0;
	}

	function get_message($id_mess) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT `messages`.`message`,`messages`.`id`,`messages`.`id_user`,`messages`.`id_page` ,`date`,`photo`,`messages`.`capt`,`users`.`login` FROM `messages`,`users_data`,`users` WHERE `messages`.`id` = {$id_mess} and `users_data`.id = `messages`.`id_user` and `users`.`id` = `messages`.`id_user`");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		return $db_data;
	}


	function message_update($id_mess,$message,$capt) {
		$pdo = db_connect();
		$message = htmlspecialchars($message);
		$message = nl2br($message);
		$capt = substr(htmlspecialchars($capt),0,10);
		$sql_query = $pdo->prepare("UPDATE messages SET message = '{$message}', capt = '{$capt}' WHERE id = {$id_mess}");
		$sql_query->execute();
		return 0;
	}

	function user_check_b($id) {
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("SELECT id FROM ban_list WHERE id = {$id}");
		$sql_query->execute();
		$db_data = $sql_query->fetchall();
		if ($db_data) {
			unset($pdo,$sql_query);
			return true;
		}
		else {
			unset($pdo,$sql_query);
			return false;
		}
	}

	function unlock_user($id_page) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("DELETE FROM ban_list WHERE id = {$id_page}");
		$sql_query->execute();
		unset($pdo, $sql_query, $id_page);
		return 0;
	}

	function lock_user($id_page) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("INSERT INTO ban_list (id) VALUES ({$id_page})");
		$sql_query->execute();
		unset($pdo, $sql_query, $id_page);
		return 0;
	}

	function get_page_data($id) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT * FROM users,users_data WHERE users.id = {$id} and users_data.id = {$id}");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		if (!empty($db_data)) {
			$_SESSION['name'] = $db_data->name;
  		$_SESSION['surname'] = $db_data->surname;
  		$_SESSION['photo'] = $db_data->photo;
  		$_SESSION['last_login'] = $db_data->last_login;
  		$_SESSION['registered'] = $db_data->registered;
  		$_SESSION['email'] = $db_data->email;
  		$_SESSION['login'] = $db_data->login;
  		$_SESSION['user_role'] = $db_data->role;
  	}
  	unset($pdo,$sql_query,$db_data);
  	return 0;
	}

	function change_name_surname($id) {
		$pdo = db_connect();
		$surname = htmlspecialchars($_POST['t_surname']);
		$name = htmlspecialchars($_POST['t_name']);		
		$pass = md5($_POST['tns_pass']);
		$sql_query = $pdo -> prepare("SELECT password FROM users WHERE id = {$id}");
		$sql_query -> execute();
		$db_data = $sql_query->fetchobject();
		if ($pass == $db_data->password) {
			$sql_query = $pdo -> prepare("UPDATE users_data SET name = '{$name}', surname = '{$surname}' WHERE id = {$id}");	
			$sql_query -> execute();
		}
  	unset($pdo,$sql_query,$name,$surname,$id);		
  	return 0;
	}

	function change_role($id) {
		$role = $_POST['user_role'];
		$pdo = db_connect();
		$sql_query = $pdo->prepare("UPDATE users SET role = {$role} WHERE id = {$id}");
		$sql_query->execute();
		return 0;
	}

	function change_email($text, $id) {
		$pdo = db_connect();
		$email = htmlspecialchars($_POST['t_email']);
		$pass = md5($_POST['t_epass']);
		$sql_query = $pdo -> prepare("SELECT password FROM users WHERE id = {$id}");
		$sql_query -> execute();
		$db_data = $sql_query->fetchobject();
		if (!preg_match("^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^",$email)) {
			return $text['r_emale_er'];
		}			
		if ($pass != $db_data->password) {
			return $text['er_pass'];
		}
			$sql_query = $pdo -> prepare("UPDATE users SET email = '{$email}' WHERE id = {$_GET['id']}");
			$sql_query -> execute();
			return '';
	}

	function change_pass($text, $id) {
		$pdo = db_connect();
		$npass = ($_POST['t_npass']);
		$rpass = $_POST['t_rpass'];
		$pass = htmlspecialchars($_POST['t_pass']);
		if ($npass != $rpass) {
			return $text['r_pass_er'];
		}
		$npass = md5($npass);
		$pass = md5($pass);
		$sql_query = $pdo -> prepare("SELECT password FROM users WHERE id = {$id}");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		if ($pass != $db_data->password) {
			return $text['er_pass'];
		}
		$sql_query = $pdo -> prepare("UPDATE users SET password = '{$npass}' WHERE id = {$id}");
		$sql_query->execute();
		return '';
	}

function change_name_surname_a($id) {
		$pdo = db_connect();
		$surname = htmlspecialchars($_POST['t_surname']);
		$name = htmlspecialchars($_POST['t_name']);		
			$sql_query = $pdo -> prepare("UPDATE users_data SET name = '{$name}', surname = '{$surname}' WHERE id = {$id}");	
			$sql_query -> execute();
  	unset($pdo,$sql_query,$name,$surname,$id);		
  	return 0;
	}

	function change_email_a($text, $id) {
		$pdo = db_connect();
		$email = htmlspecialchars($_POST['t_email']);
		if (!preg_match("^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^",$email)) {
			return $text['r_emale_er'];
		}			
			$sql_query = $pdo -> prepare("UPDATE users SET email = '{$email}' WHERE id = {$_GET['id']}");
			$sql_query -> execute();
			return '';
	}

	function change_pass_a($text, $id) {
		$pdo = db_connect();
		$npass = htmlspecialchars($_POST['t_npass']);
		$rpass = $_POST['t_rpass'];
		if ($npass != $rpass) {
			return $text['r_pass_er'];
		}
		$npass = md5($npass);
		$pass = md5($pass);
		$sql_query = $pdo -> prepare("UPDATE users SET password = '{$npass}' WHERE id = {$id}");
		$sql_query->execute();
		return '';
	}

	function upload_image_a() {
		$path = $_FILES['userfile']['name'];
		$pos = strrpos($path,'.');
		$format = substr($path,$pos);
		if ($_FILES['userfile']['size'] <= 1024*1024*10) {
				$name = gen_name($format);
				$path = '/var/www/html/test/testSite/images/users/'.$name;
				if (move_uploaded_file($_FILES['userfile']['tmp_name'],$path)) {					
					$pdo = db_connect();
					$sql_query = $pdo -> prepare("INSERT INTO `images`(id_user,path) VALUES (?,?)");
					$db_data = array($_SESSION['id_page'],$path);
					$sql_query -> execute($db_data);	
					$db_data = 'images/users/'.$name;
					$sql_query = $pdo->prepare("UPDATE `users_data` SET `photo` = '{$db_data}' WHERE id = {$_SESSION['id_page']}");
					$sql_query -> execute();
				}						
		}
		unset($pdo,$sql_query,$db_data,$path,$pos,$format);
		return 0;
	}

	function delete_user($id_user) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("DELETE FROM `users` WHERE `users`.`id` = {$id_user}");
		$sql_query->execute();
		$sql_query = $pdo->prepare("DELETE FROM `users_data` WHERE `users_data`.`id` = {$id_user}");
		$sql_query->execute();
		$sql_query = $pdo->prepare("DELETE FROM `messages` WHERE `messages`.`id_page` = {$id_user}");
		$sql_query->execute();
		$sql_query = $pdo->prepare("DELETE FROM `images` WHERE `images`.`id_user` = {$id_user}");
		$sql_query->execute();
		$sql_query = $pdo->prepare("DELETE FROM `ban_list` WHERE `ban_list`.`id` = {$id_user}");
		$sql_query->execute();
		return 0;
	}
?>