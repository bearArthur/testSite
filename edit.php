<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");
	if ((!isset($_GET['id'])) | (!isset($_GET['page'])) | (!isset($_GET['lg']))) {
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	if (!$_SESSION['id']) {
		header("Location: index.php");
	}
	$id_page = $_GET['id'];
	$nn = $_GET['page'];
	if (isset($_GET['message'])) {
		$id_mess = $_GET['message'];
	}
	elseif (isset($_GET['edit'])) {
		$id_mess = $_GET['edit'];
	}
	get_page_data($id_page);
	$_SESSION['id_page'] = $_GET['id'];
	$_SESSION['lang'] = $_GET['lg'];
	$nn = $_GET['page'];
	$text = parse_ini_file($_SESSION['lang'].".ini");	
	if (isset($_POST['mess_ok'])) {		
		message_del();
		header("Location: user.php?id={$id_page}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['send_ok'])) {
		message_update($id_mess,$_POST['send_mess'],$_POST['send_capt']);   
		header("Location: user.php?id={$_SESSION['id_page']}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['tools'])) {
		header("Location: user.php?id={$_SESSION['id']}&page={$nn}&lg={$_SESSION['lang']}");
	} 
	if (isset($_POST['mess_ed'])) {
		$_SESSION['id_mess'] = $_POST['mess_ed'];
		$message = get_message($_SESSION['id_mess']);
		if ($_SESSION['id'] == $message->id_user) {
			header("Location: edit.php?id={$_SESSION['id_page']}&page={$nn}&edit={$_SESSION['id_mess']}&lg={$_SESSION['lang']}");
		}
	}
	if (isset($_POST['home'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['exit'])) {
		log_out();
	}
	if (isset($_POST['friends'])) {
		header("Location: users.php?&lg={$_SESSION['lang']}");
	}
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title> <?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?> </title>
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">

			<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="menu">
				<?php if (isset($_GET['message'])): ?>
					<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_SESSION['id_mess']; ?>&lg=ua"><img src="images/ua.gif" class="lang"></img></a>
					<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_SESSION['id_mess']; ?>&lg=en"><img src="images/en.gif" class="lang"></img></a>
				<?php elseif (isset($_GET['edit'])): ?>
					<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_SESSION['id_mess']; ?>&lg=ua"><img src="images/ua.gif" class="lang"></img></a>
					<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_SESSION['id_mess']; ?>&lg=en"><img src="images/en.gif" class="lang"></img></a>
				<?php endif; ?>
				<button type="submit" name="exit"><?php echo $text['exit']; ?></button>
				<button type="submit" name="tools"><?php echo $text['tools']; ?></button>
				<button type="submit" name="friends"><?php echo $text['people']; ?></button>
				<button type="submit" name="home"><?php echo $text['profile']; ?></button>
			</form>
			
			<div id="info">
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['name'].'  '.$_SESSION['surname']; ?></a>
				<img src="<?php echo $_SESSION['photo']; ?>"></img>
			</div>

			<div id="content">  

				<?php  
					if (isset($_GET['edit'])):
					$message = get_message($id_mess);
				?>			
				<form method="POST" action="edit.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="edit">     
					<p class="headd"><?php echo $text['edit_mess']; ?></p>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->name.' '.$message->surname; ?></a>     <br/><br/>
					<textarea class="edit_text" name="send_capt" rows="1" cols="54" /required><?php echo strip_tags($message->capt); ?></textarea>
					<textarea class="edit_text" name="send_mess" rows="15" cols="54" /required><?php echo strip_tags($message->message); ?></textarea>							
					<p class="date"><?php echo $message->date; ?></p> 
					<button class="edit" name="send_ok" type="submit"><?php echo $text['edit']; ?></button>
				</form>   			
				<?php 
					elseif (isset($_GET['message'])): 
					$message = get_message($id_mess);
				?>
				<form method="POST" action="edit.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&message=<?php echo $_GET['message']; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="edit">   
					<p class="headd"><?php echo $text['more']; ?></p>
					<button class="edit_b" type="submit" name="mess_ok" value="<?php echo $id_mess; ?>">x</button>
					<button class="edit_b" type="submit" name="mess_ed" value="<?php echo $id_mess; ?>">!</button>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->name.' '.$message->surname; ?></a>     <br/><br/>
					<p class="head"><?php echo $message->capt; ?></p>     
					<p class="mess"><?php echo $message->message; ?></p>      
					<p class="date"><?php echo $message->date; ?></p>
				</form>
				<?php endif; ?>
				
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>