<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");

	$err_em = '';
	$err_pass = '';

	if (!$_SESSION['id']) {
		header("Location: index.php");
	}

	if ((empty($_GET['id'])) || (empty($_GET['lg']))) {
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_SESSION['lang']}");
	}

	$text = get_language($_GET['lg']);	
	get_page_data($_SESSION['id_page']);


	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_GET['lg']}");
	}	

	if (isset($_POST['tools'])) {
		$_SESSION['id_page'] = $_SESSION['id'];
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_GET['lg']}");
	}	

	if (isset($_POST['home'])) {
		header("Location: index.php");
	}

	if (isset($_POST['login'])) {
		if (!$_SESSION['id']) {
			header("Location: index.php?home=login&lg={$_GET['lg']}");
		}
		else {			
			log_out();
			header("Location: index.php?home=login&lg={$_GET['lg']}");
		}
	}

	if (isset($_POST['ns_ok'])) {
		if ($_SESSION['role'] != 3) {
			change_name_surname($_GET['id']);
		}
		else {
			change_name_surname_a($_GET['id']);
		}
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['email_ok'])) {
		if ($_SESSION['role'] != 3) {
			$err_em = change_email($text, $_GET['id']);
		}
		else {
			$err_em = change_email_a($text, $_GET['id']);			
		}
		if ($err_em == '') {
			header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
		}
	}
	if (isset($_POST['pass_ok'])) {
		if ($_SESSION['role'] != 3) {
			$err_pass = change_pass($text, $_GET['id']);
		}
		else {
			$err_pass = change_pass_a($text, $_GET['id']);
		}
		if ($err_pass == '') {
			header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
		}
	}
	if (isset($_POST['up_ok'])) {
		if ($_SESSION['role'] != 3) {
			upload_image($_GET['id']);
		}
		else {
			upload_image_a($_GET['id']);
		}
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}
	if (isset($_POST['role_ok'])) {
		change_role($_GET['id']);
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}
	if (isset($_POST['lock'])) {
		lock_user($_GET['id']);
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}
	if (isset($_POST['unlock'])) {
		unlock_user($_GET['id']);
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['edit_user'])) {
		$_SESSION['id_page'] = $_POST['edit_user'];
		header("Location: tools.php?id={$_SESSION['id_page']}&lg={$_GET['lg']}");
	}	

	if (isset($_POST['delete_user'])):
	$_SESSION['delete_id'] = $_POST['delete_user'];
	?>
		<form class="check" method="POST" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg']; ?>">
			<p class="check_mess"><?php echo $text['check_user']; ?></p>
			<button type="submit" name="del_no" class="check_b"><?php echo $text['not']; ?></button>
			<button type="submit" name="del_ok" class="check_b"><?php echo $text['ok']; ?></button>
		</form>
	<?php
	endif;

	if (isset($_POST['del_ok'])) {
		delete_user($_SESSION['delete_id']);
		header("Location: index.php");
	}

	if (isset($_POST['del_no'])){
		header("Location: tools.php?id={$_GET['id']}&lg={$_GET['lg']}");
	}
	
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
		<title> <?php echo $text['tools']; ?> </title>
		<link rel="shortcut icon" href="images/toolsl.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/Style.css">
		<link rel="stylesheet" type="text/css" href="style/menu.css">
		<link rel="stylesheet" type="text/css" href="style/info.css">
		<link rel="stylesheet" type="text/css" href="style/register.css">
		<link rel="stylesheet" type="text/css" href="style/login.css">
		<link rel="stylesheet" type="text/css" href="style/users.css">
		<link rel="stylesheet" type="text/css" href="style/send.css">
		<link rel="stylesheet" type="text/css" href="style/pager.css">
		<link rel="stylesheet" type="text/css" href="style/message.css">
		<meta charset="utf8"/>
	</head>

	<body>

		

			<div id="menu_back">
				<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="menu">	
					<?php
						if (isset($_SESSION['id'])): 
					?>
						<button type="submit" name="profile" class="menu_button_1"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="menu_button_1" disabled="disabled"></button>			
					<?php endif; ?>				
					<button type="submit" name="home" class="menu_button_2"></button>
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="tools" class="menu_button_3"></button>
					<?php	if ($_SESSION['role'] == 3): ?>
						<button  type="submit" name="site_tools" class="menu_button_4"></button>
					<?php 
						endif; 
						elseif (!isset($_SESSION['id'])): 
					?>
						<button type="submit" name="tools" class="menu_button_3" disabled="disabled"></button>						
					<?php endif; ?>
					<button type="submit" name="login" class="menu_button_5"></button>
				</form>
				<a class="lang1" href="tools.php?id=<?php echo $_GET['id']; ?>&lg=ua">ua</a>
				<a class="lang2" href="tools.php?id=<?php echo $_GET['id']; ?>&lg=en">en</a>		
		</div>

		<div id="back">
			
			<div id="info">
					<?php if (isset($_SESSION['id'])): ?>
						<img class="info" src="<?php echo $_SESSION['photo']; ?>"></img>
						<div class="img"><a href="user.php?id=<?php echo $_GET['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="info"><?php echo $_SESSION['login']; ?></a></div>
						<table class="info">
						<?php if (isset($_SESSION['id']) && ($_SESSION['name'])): ?>
							<tr>
								<td class="info"><p class="info_b"><?php echo $text['name']; ?></p></td>
								<td><p class="info"><?php echo $_SESSION['name']; ?></p></td>
							</tr>
						<?php 
							endif;
							if (isset($_SESSION['id']) && ($_SESSION['surname'])): 
						?>
							<tr>
								<td class="info"><p class="info_b"><?php echo $text['surname']; ?></p></td>
								<td><p class="info"><?php echo $_SESSION['surname']; ?></p></td>
							</tr>
						<?php 
							endif;
							if (isset($_SESSION['id'])):
						?>
							<tr>
								<td class="info"><p class="info_b"><?php echo $text['email']; ?></p></td>
								<td><p class="info"><?php echo $_SESSION['email']; ?></p></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td class="info"><p class="info_b"><?php echo $text['date_reg']; ?></p></td>
							<td><p class="info"><?php echo $_SESSION['registered']; ?></p></td>
						</tr>
						<tr>
							<td class="info"><p class="info_b"><?php echo $text['date_log']; ?></p></td>
							<td><p class="info"><?php echo $_SESSION['last_login']; ?></p></td>
						</tr>
					</table>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<img class="info" src="images/users/none.jpg"></img>
						<div class="img"><a href="index.php" class="info"><?php echo $text['gest']; ?></a></div>
					<?php endif; ?>			
				</div>

			<div id="content">			

				<p class="headd"><?php echo $text['tools_user']; ?></p>
				
				<form method="POST" enctype="multipart/form-data" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg'] ?>" class="mess">				
					<table class="tool">
						<tr>
							<td><p class="tool"><?php echo $text['ch_ava']; ?></p></td>
							<td><input name="userfile" type="file"></td>
						</tr>	
						<tr>
							<td></td>
							<td><button class="tool" type="submit" name="up_ok"><?php echo $text['save']; ?></button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg'] ?>" class="mess">
					<table class="tool">
						<tr>
							<td><p class="tool"><?php echo $text['name']; ?></p></td>
							<td><input type="text" size="30" name="t_name" value="<?php echo $_SESSION['name']; ?>"></td>
						</tr>
						<tr>
							<td><p class="tool"><?php echo $text['surname']; ?></p></td>
							<td><input type="text" size="30" name="t_surname" value="<?php echo $_SESSION['surname']; ?>"></td>
						</tr>	
						<tr>
							<td></td>
							<td><button class="tool" type="submit" name="ns_ok"><?php echo $text['save']; ?></button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg'] ?>" class="mess">
					<table class="tool">	
						<tr>
							<td><p class="tool"><?php echo $text['email']; ?></p></td>
							<td><input type="text" size="30" name="t_email"/required value="<?php echo $_SESSION['email'] ?>"></td>
						</tr>	
						<?php if ($_SESSION['role'] != 3): ?>						
							<tr>
								<td><p class="tool"><?php echo $text['pass']; ?></p></td>
								<td><input type="password" size="30" name="t_epass" /required></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td><p class="tool"><?php echo $err_em; ?></p></td>
							<td><button class="tool" type="submit" name="email_ok"><?php echo $text['save']; ?></button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg'] ?>" class="mess">
					<table class="tool">
						<tr>
							<td><p class="tool"><?php echo $text['npass']; ?></p></td>
							<td><input type="password" size="30" name="t_npass" /required></td>
						</tr>							
						<tr>
							<td><p class="tool"><?php echo $text['rpass']; ?></p></td>
							<td><input type="password" size="30" name="t_rpass" /required></td>
						</tr>	
						<?php if ($_SESSION['role'] != 3): ?>				
							<tr>
								<td><p class="tool"><?php echo $text['pass']; ?></p></td>
								<td><input type="password" size="30" name="t_pass" /required></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td><p><?php echo $err_pass; ?></p></td>
							<td><button class="tool" type="submit" name="pass_ok"><?php echo $text['save']; ?></button></td>
						</tr>
					</table>
				</form> 


					<?php if (($_SESSION['role'] == 3) && ($_SESSION['id'] != $_GET['id'])): ?>
						<form method="POST" action="tools.php?id=<?php echo $_GET['id']; ?>&lg=<?php echo $_GET['lg'] ?>" class="mess">
						<table class="tool">
						<tr>
							<td><p class="tool"><?php echo $text['role_lock']; ?></p></td>
							<?php if ($_SESSION['user_role'] == 0): ?>
								<td><input type="radio" name="user_role" value="0" checked="checked"></></td>
							<?php elseif ($_SESSION['user_role'] != 0): ?>								
								<td><input type="radio" name="user_role" value="0"></td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><p class="tool"><?php echo $text['role_user']; ?></p></td>
							<?php if ($_SESSION['user_role'] == 1): ?>
								<td><input type="radio" name="user_role" value="1" checked="checked"></></td>
							<?php elseif ($_SESSION['user_role'] != 1): ?>								
								<td><input type="radio" name="user_role" value="1"></td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><p class="tool"><?php echo $text['role_editor']; ?></p></td>
							<?php if ($_SESSION['user_role'] == 2): ?>
								<td><input type="radio" name="user_role" value="2" checked="checked"></td>
							<?php elseif ($_SESSION['user_role'] != 2): ?>								
								<td><input type="radio" name="user_role" value="2"></td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><p class="tool"><?php echo $text['role_admin']; ?></p></td>
							<?php if ($_SESSION['user_role'] == 3): ?>
								<td><input type="radio" name="user_role" value="3" checked="checked"></td>
							<?php elseif ($_SESSION['user_role'] != 3): ?>								
								<td><input type="radio" name="user_role" value="3"></td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><p><?php echo $err_pass; ?></p></td>
							<td><button class="tool" type="submit" name="role_ok"><?php echo $text['save']; ?></button></td>
						</tr>
						</table>
						</form>
					<?php endif; ?>

			</div>

			<div id="ank"></div>
			
		</div>

	</body>

</html>