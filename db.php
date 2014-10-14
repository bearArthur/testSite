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
		$message_en = htmlspecialchars($_POST['send_mess_en']);
		$message_en = nl2br($message_en);
		$capt = substr(htmlspecialchars($_POST['send_capt']), 0, 100);
		$capt_en = substr(htmlspecialchars($_POST['send_capt_en']), 0, 100);
		$pdo = db_connect();
		$sql_query = $pdo->prepare("INSERT INTO messages(id_page, id_user, message, capt, message_en, capt_en, date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
		$db_data = array($id_page, $id_user, $message, $capt, $message_en, $capt_en);
		$sql_query->execute($db_data);
		unset($pdo, $message, $sql_query, $db_data);
		return 0;
	}

	function message_out($count, $id){	
		$pdo = db_connect();
		if ($_GET['lg'] == 'ua') {
			$sql_query = $pdo -> prepare("SELECT users.login, users_data.photo, messages.message, messages.capt, messages.id, messages.id_user, messages.id_page, messages.date FROM users_data, messages, users WHERE messages.id_user = users_data.id and messages.id_page = {$id} and users.id = messages.id_user ORDER BY `date` DESC LIMIT {$count},10 ");
		}
		elseif ($_GET['lg'] == 'en') {
			$sql_query = $pdo -> prepare("SELECT users.login, users_data.photo, messages.message_en as message, messages.capt_en as capt, messages.id, messages.id_user, messages.id_page, messages.date FROM users_data, messages, users WHERE messages.id_user = users_data.id and messages.id_page = {$id} and users.id = messages.id_user ORDER BY `date` DESC LIMIT {$count},10 ");
		}
		$sql_query -> execute();
		$db_data = $sql_query -> fetchAll();
		unset($pdo,$sql_query);
		return $db_data;
	}	

	function message_del($id){
		$pdo = db_connect();
		$sql_query = $pdo->prepare("DELETE FROM `messages` WHERE `messages`.`id` = {$id}");
		$sql_query->execute();
		$sql_query = $pdo->prepare("DELETE FROM `coments` WHERE id_message = {$id}");
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

	function coment_get_row_count($id_mess){	
		$pdo = db_connect();	
		$sql_query = $pdo->prepare("SELECT COUNT(1) FROM `coments` WHERE `coments`.id_message = {$id_mess}");
		$sql_query->execute();
		$row_count = $sql_query->fetch();
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

	function get_language($lg) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT id, keyid, `{$lg}` FROM language");
		$sql_query->execute();
		$db_data = $sql_query->fetchall();
		$text = array();
		unset($_SESSION['mass']);
		foreach ($db_data as $key) {
			$text[$key['keyid']] = $key[$lg];
			$_SESSION['mass'][] = $key['keyid'];
		}
		return $text;
	}

	function coment_in($coment, $capt) {
		if (empty($capt)) {
			$capt = substr($coment, 0, 15);
			$t = 'a';
			$n = 14;
			while ($t != ' ') {
				$t = substr($capt, $n, 1);
				$n--;
			}
			$capt = substr($coment, 0, $n);
		}
		$pdo = db_connect();
		$sql_query = $pdo->prepare("INSERT INTO coments (id_user, id_page, capt, coment, date, id_message) VALUES (?, ?, ?, ?, NOW(), ?)");
		$sql_query->execute(array($_SESSION['id'], $_GET['id'], $capt, $coment, $_GET['message']));
		return 0;
	}

	function coment_out($id_page, $count, $id_mess) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT `coments`.id as cid, `users_data`.photo, `users`.login, `users`.id, `coments`.capt, `coments`.coment, `coments`.date FROM users, coments, users_data WHERE `users_data`.id = `users`.id AND `users`.id = `coments`.id_user AND `coments`.id_page = {$id_page} AND `coments`.id_message = {$id_mess} ORDER BY `coments`.`date` DESC LIMIT {$count},10");
		$sql_query->execute();
		$db_data = $sql_query->fetchall();
		return $db_data;
	}

	function coment_delete($id) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("DELETE FROM `coments` WHERE id = {$id}");
		$sql_query->execute();
		return 0;
	}

	function change_user_names($lg, $mass) {
		$pdo = db_connect();

		foreach ($_SESSION['mass'] as $key) {
			$link = "edit_".$key;
			if (isset($_POST["{$link}"])) {
				$t = $_POST["{$link}"];
				$sql_query = $pdo->prepare("UPDATE language SET `{$lg}` = :n WHERE `keyid` = '{$key}' ");
				$sql_query->bindparam(':n',$t);
				$sql_query->execute();	
			}		
		}
		return 0;
	}

	function upload_image($id) {
		$pdo = db_connect();
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

	function get_message($id_mess) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT `messages`.`message`, `messages`.`message_en`,`messages`.`id`,`messages`.`id_user`,`messages`.`id_page` ,`date`,`photo`,`messages`.`capt`, `messages`.`capt_en`,`users`.`login` FROM `messages`,`users_data`,`users` WHERE `messages`.`id` = {$id_mess} and `users_data`.id = `messages`.`id_user` and `users`.`id` = `messages`.`id_user`");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		return $db_data;
	}


	function message_update($id_mess, $message, $capt, $message_en, $capt_en) {
		$pdo = db_connect();
		$message = htmlspecialchars($message);
		$message = nl2br($message);
		$capt = substr(htmlspecialchars($capt),0,10);
		$message_en = htmlspecialchars($message_en);
		$message_en = nl2br($message_en);
		$capt_en = substr(htmlspecialchars($capt_en),0,10);
		$sql_query = $pdo->prepare("UPDATE messages SET message = '{$message}', capt = '{$capt}', message_en = '{$message_en}', capt_en = '{$capt_en}' WHERE id = {$id_mess}");
		$sql_query->execute();
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
		$sql_query = $pdo -> prepare("UPDATE users_data SET name = '{$name}', surname = '{$surname}' WHERE id = {$id}");	
		$sql_query -> execute();
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
		return 0;
	}
?>