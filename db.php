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

	function message_in($id_page){
		$message = htmlspecialchars($_POST['send_mess']);
		$message = nl2br($message);
		$capt = substr(htmlspecialchars($_POST['send_capt']),0,20);
		$pdo = db_connect();
		echo $id_page;
		$sql_query = $pdo -> prepare("INSERT INTO messages(id_page,id_user,message,capt,date) VALUES (?,?,?,?,NOW())");
		$db_data = array($id_page,$_SESSION['id'],$message,$capt);
		$sql_query -> execute($db_data);
		unset($pdo,$message,$sql_query,$db_data);
		return 0;
	}

	function message_out($count,$id_page){	
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("SELECT users_data.name, users_data.surname, users_data.photo, messages.message,messages.capt, messages.id, messages.id_user, messages.id_page, messages.date FROM users_data, messages WHERE messages.id_user = users_data.id and messages.id_page = {$id_page} ORDER BY `date` DESC LIMIT {$count},10 ");
		$sql_query -> execute();
		$db_data = $sql_query -> fetchAll();
		unset($pdo,$sql_query);
		return $db_data;
	}	

	function message_del(){
		$pdo = db_connect();
		$id = $_POST['mess_ok'];
		$sql_query = $pdo -> prepare("SELECT id_page,id_user FROM messages WHERE id = {$id}");
		$sql_query -> execute();
		$db_data = $sql_query -> fetchobject();
		if ($db_data->id_page == $_SESSION['id']) {
			$sql_query = $pdo -> prepare("DELETE FROM `messages` WHERE `messages`.`id` = {$id}");
			$sql_query -> execute();
		}
		elseif ($db_data->id_user == $_SESSION['id']) {
			$sql_query = $pdo -> prepare("DELETE FROM `messages` WHERE `messages`.`id` = {$id}");
			$sql_query -> execute();
		}
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
		$sql_query = $pdo -> prepare("SELECT * FROM users, users_data WHERE users.id = users_data.id and users.id != {$_SESSION['id']}");
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

	function upload_image() {
		$path = $_FILES['userfile']['name'];
		$pos = strrpos($path,'.');
		$format = substr($path,$pos);
		if ($_FILES['userfile']['size'] <= 1024*1024*10) {
				$name = gen_name($format);
				$path = '/var/www/html/test/images/users/'.$name;
				move_uploaded_file($_FILES['userfile']['tmp_name'],$path);						
		}
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("INSERT INTO `images`(id_user,path) VALUES (?,?)");
		$db_data = array($_SESSION['id'],$path);
		$sql_query -> execute($db_data);	
		$db_data = 'images/users/'.$name;
		$sql_query = $pdo->prepare("UPDATE `users_data` SET `photo` = '{$db_data}' WHERE id = {$_SESSION['id']}");
		$sql_query -> execute();
		unset($pdo,$sql_query,$db_data,$path,$pos,$format);
		return 0;
	}

	function get_message($id_mess) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT `messages`.`message`,`messages`.`id`,`messages`.`id_user`,`name`,`surname`,`date`,`photo`,`messages`.`capt` FROM `messages`,`users_data` WHERE `messages`.`id` = {$id_mess} and `messages`.`id_user` = `users_data`.id");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		return $db_data;
	}


	function message_update($id_mess,$message,$capt) {
		$pdo = db_connect();
		$message = htmlspecialchars($message);
		$message = nl2br($message);
		$capt = substr(htmlspecialchars($capt),0,20);
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

	function user_check_a($id) {
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("SELECT id FROM admin WHERE id = {$id}");
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

	function unlock_user($id) {
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("DELETE FROM ban_list WHERE id = {$id}");
		$sql_query->execute();
		unset($pdo,$sql_query);
		return 0;
	}

	function lock_user($id) {
		$pdo = db_connect();
		$sql_query = $pdo -> prepare("INSERT INTO ban_list (id) VALUES ({$id})");
		$sql_query->execute();
		unset($pdo,$sql_query);
		return 0;
	}

	function get_page_data($id_page) {
		$pdo = db_connect();
		$sql_query = $pdo->prepare("SELECT name,surname,photo FROM users_data WHERE id = {$id_page}");
		$sql_query->execute();
		$db_data = $sql_query->fetchobject();
		$_SESSION['name'] = $db_data->name;
  	$_SESSION['surname'] = $db_data->surname;
  	$_SESSION['photo'] = $db_data->photo;
  	unset($pdo,$sql_query,$db_data);
  	return 0;
	}
?>