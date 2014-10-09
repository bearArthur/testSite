<?php
	session_start();
	mb_internal_encoding("UTF-8");
	include('db.php');
	include('log_reg.php');

	if (isset($_SESSION['id'])) {
		if (!isset($_GET['id']) | !isset($_GET['lg'])) {
			if (isset($_SESSION['lang'])) {
				header("Location: index.php?id={$_SESSION['id']}&lg={$_SESSION['lang']}");
			}
			else {
				header("Location: index.php?id={$_SESSION['id']}&lg=ua");
			}
		}
	}
	else {
		if (!isset($_GET['lg'])) {
			if (isset($_SESSION['lang'])) {
				header("Location: index.php?lg={$_SESSION['lang']}");
			}
			else {
				header("Location: index.php?lg=ua");
			}
		}
	}

	$_SESSION['lang'] = $_GET['lg'];
	if (isset($_GET['id'])) {
		$_SESSION['id_page'] = $_GET['id'];
	}	
	if (isset($_SESSION['id'])) {
		get_page_data($_SESSION['id']);
	}	
	$text = parse_ini_file($_SESSION['lang'].".ini");

	if (isset($_GET['home'])) {
		$link = "index.php?home={$_GET['home']}&";
		$image = "images/doorsl.png";
	}
	else {
		if (isset($_SESSION['id'])) {
			$link = "index.php?id={$_SESSION['id']}&";
		}
		else {
			$link = "index.php?";
		}
		$image = "images/homel.png";
	}

	$error_r = '';
	$error_l = '';	

	if (isset($_POST['home'])) {
		header("Location: index.php");
	}

	if (isset($_POST['profile'])) {
		header("Location: user.php");
	}

	if (isset($_POST['tools'])) {
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_GET['lg']}");
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

	if (isset($_POST['reg_ok'])) {
		$error_r = register($text);
		if ($error_r == $text['r_access']) {	
			unset($_SESSION['reg_login'], $_SESSION['reg_email']);
			header("Location: user.php");	
		}
	}

	if (isset($_POST['log_ok'])) {
		$error_l = log_in($text);	
		if ($error_l == '') {	
			unset($_SESSION['log_email']);	
			header("Location: user.php");	
		}	
	}

	if (isset($_POST['edit_user'])) {
		$_SESSION['id_page'] = $_POST['edit_user'];
		header("Location: tools.php?id={$_SESSION['id_page']}&lg={$_GET['lg']}");
	}	

	if (isset($_POST['delete_user'])):
	$_SESSION['delete_id'] = $_POST['delete_user'];
	?>
		<form class="check" method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>">
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
		header("Location: index.php");
	}
?>

<!DOCTYPE HTML5>

<html>
	
	<head>
		<title><?php echo $text['home']; ?></title>
		<link rel="shortcut icon" href="<?php echo $image; ?>" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>
	
	<body>
		
		<div id="back">

			<div id="menu">

				<form method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>" id="menu_b">
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic"><img src="images/user.png" class="butt"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic" disabled="disabled"><img src="images/user_disabled.png" class="butt"></button>			
					<?php endif; ?>				
					<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="tools" class="pic"><img src="images/tools.png" class="butt"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="tools" class="pic" disabled="disabled"><img src="images/tools_disabled.png" class="butt"></button>						
					<?php endif; ?>
					<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>
				</form>

				<?php if (isset($_GET['home'])): ?>
					<a  href="<?php echo $link; ?>home=login&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
					<a  href="<?php echo $link; ?>home=login&lg=en"><img src="images/en.gif" class="lang2"></img></a>
				<?php elseif (!isset($_GET['home'])): ?>
					<a  href="<?php echo $link; ?>lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
					<a  href="<?php echo $link; ?>lg=en"><img src="images/en.gif" class="lang2"></img></a>
				<?php endif; ?>
			</div>

			<div id="info">
				<?php if (isset($_SESSION['id'])): ?>
					<img src="<?php echo $_SESSION['photo']; ?>"></img></br>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="name"><?php echo $_SESSION['login']; ?></a>
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
				<?php elseif (!isset($_SESSION['id'])): ?>
					<img src="images/users/none.jpg"></img></br>
					<a href="index.php" class="name"><?php echo $text['gest']; ?></a>		
				<?php endif; ?>			
			</div>
		
			<div id="content">
				
				<?php if (isset($_GET['home'])): ?>

					<form id="register" method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>">
						<p class="headd"><?php echo $text['register']; ?></p>
						<table>
							<tr>
								<td> <p><?php echo $text['login']; ?></p> </td>
								<td> <input type="text" name="reg_login" value="<?php if (isset($_SESSION['reg_login'])) {echo $_SESSION['reg_login'];} ?>" /required></td>
							</tr>
							<tr>
								<td> <p><?php echo $text['email']; ?></p> </td>
								<td> <input type="text" name="reg_email" value="<?php if (isset($_SESSION['reg_login'])) {echo $_SESSION['reg_email'];} ?>" /required></td>
							</tr>
							<tr>
								<td> <p><?php echo $text['pass']; ?></p> </td>
								<td> <input type="password" name="reg_pass" /required> </td>
							</tr>
							<tr>
								<td> <p><?php echo $text['rpass']; ?></p> </td>
								<td> <input type="password" name="reg_rpass" /required> </td>
							</tr>
							<tr>
								<td> <p class="error"><?php echo $error_r; ?></p> </td>
								<td> <button type="submit" name="reg_ok"> <?php echo $text['b_reg']; ?></button>
							</tr>
						</table>
					</form>

					<form id="login" method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>">
						<p class="headd"><?php echo $text['author']; ?></p>
						<table>
							<tr>
								<td> <p><?php echo $text['email']; ?></p> </td>
								<td> <input type="text" name="log_email" value="<?php if (isset($_SESSION['log_email'])) {echo $_SESSION['log_email'];} ?>" /required> </td>
							</tr>
							<tr>
								<td> <p><?php echo $text['pass']; ?></p> </td>
								<td> <input type="password" name="log_pass" /required> </td>
							</tr>
							<tr>
								<td> <p class="error"><?php echo $error_l; ?></p> </td>
								<td> <button type="submit" name="log_ok"> <?php echo $text['b_author']; ?></button>
							</tr>
						</table>
					</form>

				<?php elseif (!isset($_GET['home'])): ?>

					<p class="headd"><?php echo $text['users']; ?></p>

					<?php 
						if (isset($_SESSION['id']) && $_SESSION['role'] == 3):
						$db_data = get_users();
						foreach ($db_data as $key):
					?>	
						<form method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>" class="users">		
							<img src="<?php echo $key['photo']; ?>" class="users"></img>	
							<a href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="head"><?php echo $key['login']; ?></a>
							<button class="mess_b" name="delete_user" value="<?php echo $key['id']; ?>" type="submit">x</button>	
							<button class="mess_b" name="edit_user" value="<?php echo $key['id']; ?>" type="submit" >!</button>
						</form>		
					<?php
						endforeach;
						elseif (!isset($_SESSION['id']) | isset($_SESSION['id'])):
						$db_data = get_users();
						foreach ($db_data as $key):
					?>	
						<div class="users">		
							<img src="<?php echo $key['photo']; ?>" class="users"></img>	
							<a href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="head"><?php echo $key['login']; ?></a>	
						</div>	
					<?php
						endforeach;
						endif;
						endif;
					?>

			</div>
			
			<div id="ank"></div>

		</div>	
	
	</body>

</html>