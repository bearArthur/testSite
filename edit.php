<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");
	if ((!isset($_GET['page'])) | (!isset($_GET['lg']))) {
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
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
	if (isset($_POST['del_mess_ok'])) {		
		message_del($_SESSION['id_mess']);








		
		// header("Location: user.php?id={$id_page}&page={$nn}&lg={$_SESSION['lang']}");









	}
	if (isset($_POST['mess_del_no'])) 













		// header("Location: edit.php?id={$_SESSION['id_page']}&page={$nn}&message={$_SESSION['id_mess']}&lg={$_SESSION['lang']}");















	}
	if (isset($_POST['send_ok'])) {
		message_update($_GET['edit'], $_POST['send_mess'], $_POST['send_capt']);   
		header("Location: user.php?id={$_SESSION['id_page']}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['tools'])) {
		$_SESSION['id_page'] = $_SESSION['id'];
		header("Location: user.php?id={$_SESSION['id']}&page={$nn}&lg={$_SESSION['lang']}");
	} 
	if (isset($_POST['mess_ed'])) {
			header("Location: edit.php?id={$_SESSION['id_page']}&page={$nn}&edit={$_POST['mess_ed']}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['login'])) {
		if (!$_SESSION['id']) {
			header("Location: index.php?home=login&lg={$_SESSION['lang']}");
		}
		else {			
			log_out();
			header("Location: index.php?home=login&lg={$_SESSION['lang']}");
		}
	}
	if (isset($_POST['home'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['friends'])) {
		header("Location: users.php?&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_ok'])):
		$_SESSION['id_mess'] = $_POST['mess_ok'];
?>
	<form class="check" method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>">
	<p class="check_mess"><?php echo $text['check_mess']; ?></p>
	<button type="submit" name="mess_del_no" class="check_b"><?php echo $text['not']; ?></button>
	<button type="submit" name="mess_del_ok" class="check_b"><?php echo $text['ok']; ?></button>
	</form>
<?php
	endif;
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

			<div id="menu">
				<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="menu_b">			
					<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic"><img src="images/user.png" class="butt"></button>
						<button type="submit" name="tools" class="pic"><img src="images/tools.png" class="butt"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic" disabled="disabled"><img src="images/user_disabled.png" class="butt"></button>
						<button type="submit" name="tools" class="pic" disabled="disabled"><img src="images/tools_disabled.png" class="butt"></button>						
					<?php endif; ?>
					<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>
				</form>
					<?php if (isset($_GET['message'])): ?>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_GET['message']; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_GET['message']; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>
					<?php elseif (isset($_GET['edit'])): ?>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>
					<?php endif; ?>
			</div>
			
			<div id="info">
				<img src="<?php echo $_SESSION['photo']; ?>"></img></br>
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['login']; ?></a>
				<table>
					<?php if (isset($_SESSION['id']) && ($_SESSION['name']) && ($_SESSION['surname'])): ?>
						<tr>
							<td><p class="info_user_b"><?php echo $text['name']; ?></p></td>
							<td><p class="info_user"><?php echo $_SESSION['name']; ?></p></td>
						</tr>
						<tr>
							<td><p class="info_user_b"><?php echo $text['surname']; ?></p></td>
							<td><p class="info_user"><?php echo $_SESSION['surname']; ?></p></td>
						</tr>
					<?php 
						endif;
						if (isset($_SESSION['id'])):
					?>
						<tr>
							<td><p class="info_user_b"><?php echo $text['email']; ?></p></td>
							<td><p class="info_user"><?php echo $_SESSION['email']; ?></p></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td><p class="info_user_b"><?php echo $text['date_reg']; ?></p></td>
						<td><p class="info_user"><?php echo $_SESSION['registered']; ?></p></td>
					</tr>
					<tr>
						<td><p class="info_user_b"><?php echo $text['date_log']; ?></p></td>
						<td><p class="info_user"><?php echo $_SESSION['last_login']; ?></p></td>
					</tr>
				</table>
			</div>

			<div id="content">  

				<?php  
					if (isset($_GET['edit'])):
					$message = get_message($id_mess);
				?>			
				<form method="POST" action="edit.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="edit">     
					<p class="headd"><?php echo $text['edit_mess']; ?></p>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
					<textarea class="edit_text" name="send_capt" rows="1" cols="54" /required><?php echo strip_tags($message->capt); ?></textarea>
					<textarea class="edit_text" name="send_mess" rows="15" cols="54" /required><?php echo strip_tags($message->message); ?></textarea>							
					<p class="date"><?php echo $message->date; ?></p> 
					<button class="edit" name="send_ok" type="submit"><?php echo $text['edit']; ?></button>
				</form>   			
				<?php 
					elseif (isset($_GET['message'])): 
					$message = get_message($id_mess);
				?>
				<form method="POST" action="edit.php?id=<?php echo $_GET['message']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_SESSION['id_mess'] ?>&lg=<?php echo $_SESSION['lang']; ?>" id="edit">   
					<p class="headd"><?php echo $text['more']; ?></p>
					<?php 
						if (isset($_SESSION['id'])): 
							if ($_SESSION['role'] == 1):
								if ($_SESSION['id'] == $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php 
								elseif ($_SESSION['id'] == $message->id_page && $_SESSION['id'] != $message->id_user):
					 ?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
					<?php 
								endif;
							elseif ($_SESSION['role'] == 2):
								if ($_SESSION['id'] == $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
								elseif ($_SESSION['id'] == $message->id_page && $_SESSION['id'] != $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
					<?php
								elseif ($_SESSION['id'] != $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
								endif;
							elseif ($_SESSION['role'] == 3):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
							endif;
						endif;
					?>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
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