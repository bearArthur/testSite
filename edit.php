<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");
	if ((empty($_GET['page'])) || (empty($_GET['lg']))) {
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	$id_page = $_GET['id'];
	$nn = $_GET['page'];
	if (isset($_GET['message'])) {
		$id_mess = $_GET['message'];
		$link = "edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&lg={$_GET['lg']}";
	}
	elseif (isset($_GET['edit'])) {
		$id_mess = $_GET['edit'];
		$link = "edit.php?id={$_GET['id']}&page={$_GET['page']}&edit={$_GET['edit']}&lg={$_GET['lg']}";
	}
	get_page_data($id_page);

	$_SESSION['id_page'] = $_GET['id'];
	$_SESSION['lang'] = $_GET['lg'];
	$nn = $_GET['page'];
	$text = get_language($_GET['lg']);	

	if (isset($_POST['mess_del_ok'])) {		
		message_del($_GET['message']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['mess_del_no'])) {
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['send_ok'])) {
		message_update($_GET['edit'], $_POST['send_mess'], $_POST['send_capt'], $_POST['send_mess_en'], $_POST['send_capt_en']);   
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['tools'])) {
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_GET['lg']}");
	} 

	if (isset($_POST['mess_ed'])) {
			header("Location: edit.php?id={$_SESSION['id_page']}&page={$_GET['page']}&edit={$_POST['mess_ed']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
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
	<form class="check" method="POST" action="<?php echo $link; ?>">
	<p class="check_mess"><?php echo $text['check_mess']; ?></p>
	<button type="submit" name="mess_del_no" class="check_b"><?php echo $text['not']; ?></button>
	<button type="submit" name="mess_del_ok" class="check_b"><?php echo $text['ok']; ?></button>
	</form>
<?php
	endif;
	
	if (isset($_POST['site_tools'])):
	?>
		<form class="check" method="POST" action="">
			<p class="check_mess"><?php echo $text['check_page']; ?></p>
			<a href="edit_page.php?template=home&lg=<?php echo $_GET['lg']; ?>"><img src="images/homec.png"></a>
			<a href="edit_page.php?template=user&lg=<?php echo $_GET['lg']; ?>"><img src="images/userc.png"></a>
			<a href="edit_page.php?template=tools&lg=<?php echo $_GET['lg']; ?>"><img src="images/toolsc.png"></a>
			<a href="edit_page.php?template=edit&lg=<?php echo $_GET['lg']; ?>"><img src="images/pencilc.png"></a>
			<a href="edit_page.php?template=message&lg=<?php echo $_GET['lg']; ?>"><img src="images/pencilc.png"></a>
			<a href="edit_page.php?template=register&lg=<?php echo $_GET['lg']; ?>"><img src="images/doorsc.png"></a>
			<button type="submit" name="close" class="check_b"><?php echo $text['close']; ?></button>
		</form>
	<?php
	endif;
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title> <?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?> </title>
		<link rel="shortcut icon" href="images/pencil.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">

			<div id="menu">
				<form method="POST" action="<?php echo $link; ?>" id="menu_b">			
					<?php
						if (isset($_SESSION['id'])): 
					?>
						<button type="submit" name="profile" class="pic"><img src="images/user.png" class="butt"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic" disabled="disabled"><img src="images/user_disabled.png" class="butt"></button>			
					<?php endif; ?>				
					<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="tools" class="pic"><img src="images/tools.png" class="butt"></button>
					<?php	if ($_SESSION['role'] == 3): ?>
						<button  type="submit" name="site_tools" class="pic"><img src="images/sitetools.png" class="butt"></button>
					<?php 
						endif; 
						elseif (!isset($_SESSION['id'])): 
					?>
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
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['login']; ?></a>
				<table>
					<?php if (isset($_SESSION['id']) && ($_SESSION['name'])): ?>
						<tr>
							<td><p class="info_user_b"><?php echo $text['name']; ?></p></td>
							<td><p class="info_user"><?php echo $_SESSION['name']; ?></p></td>
						</tr>
					<?php 
						endif;
						if (isset($_SESSION['id']) && ($_SESSION['surname'])): 
					?>
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
					$message = get_message($_GET['edit']);
				?>			
				<form method="POST" action="<?php echo $link; ?>" id="edit">     
					<p class="headd"><?php echo $text['edit_mess']; ?></p>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
					<p class="info_user_e"><?php echo $text['ukraine']; ?></p>
					<textarea class="edit_text" name="send_capt" rows="1" cols="54" /required><?php echo strip_tags($message->capt); ?></textarea>
					<textarea class="edit_text" name="send_mess" rows="15" cols="54" /required><?php echo strip_tags($message->message); ?></textarea>	
					<p class="info_user_e"><?php echo $text['english']; ?></p>						
					<textarea class="edit_text" name="send_capt_en" rows="1" cols="54" /required><?php echo strip_tags($message->capt_en); ?></textarea>
					<textarea class="edit_text" name="send_mess_en" rows="15" cols="54" /required><?php echo strip_tags($message->message_en); ?></textarea>							
					<p class="date"><?php echo $message->date; ?></p> 
					<button class="edit" name="send_ok" type="submit"><?php echo $text['edit']; ?></button>
				</form>   			
				<?php 
					elseif (isset($_GET['message'])): 
					$message = get_message($_GET['message']);
				?>
				<form method="POST" action="<?php echo $link; ?>" id="edit">   
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
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
					<?php if ($_GET['lg'] == 'ua'): ?>
						<p class="head"><?php echo $message->capt; ?></p>     
						<p class="mess"><?php echo $message->message; ?></p>   
					<?php elseif ($_GET['lg'] == 'en'): ?>  
						<p class="head"><?php echo $message->capt_en; ?></p>     
						<p class="mess"><?php echo $message->message_en; ?></p>   
					<?php endif; ?>
					<p class="date"><?php echo $message->date; ?></p>
				</form>
				<?php endif; ?>
				
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>