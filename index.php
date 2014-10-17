<?php
	session_start();
	mb_internal_encoding("UTF-8");
	include('db.php');
	include('log_reg.php');

	if (isset($_SESSION['id'])) {
		if (empty($_GET['id']) || empty($_GET['lg'])) {
			if (isset($_SESSION['lang'])) {
				header("Location: index.php?id={$_SESSION['id']}&lg={$_SESSION['lang']}");
			}
			else {
				header("Location: index.php?id={$_SESSION['id']}&lg=ua");
			}
		}
	}
	else {
		if (empty($_GET['lg'])) {
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
	$text = get_language($_GET['lg']);	

	if (isset($_GET['home'])) {
		$link = "index.php?home={$_GET['home']}&";
		$image = "images/doorsl.png";
		$title = $text['go_in'];
	}
	else {
		if (isset($_SESSION['id'])) {
			$link = "index.php?id={$_SESSION['id']}&";
		}
		else {
			$link = "index.php?";
		}
		$image = "images/homel.png";		
		$title = $text['home'];
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

<!DOCTYPE HTML5>

<html>
	
	<head>
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" href="<?php echo $image; ?>" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/Style.css">
		<link rel="stylesheet" type="text/css" href="style/menu.css">
		<link rel="stylesheet" type="text/css" href="style/info.css">
		<link rel="stylesheet" type="text/css" href="style/register.css">
		<link rel="stylesheet" type="text/css" href="style/login.css">
		<link rel="stylesheet" type="text/css" href="style/users.css">
		<link rel="stylesheet" type="text/css" href="style/message.css">
		<script src="scripts/register.js"></script>
		<meta charset="utf8">
	</head>
	
	<body>		
		
			<div id="menu_back">
				<form method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>" class="menu">
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

				<?php if (isset($_GET['home'])): ?>
					<a  class="lang1" href="<?php echo $link; ?>home=login&lg=ua">ua</a>
					<a  class="lang2" href="<?php echo $link; ?>home=login&lg=en">en</a>
				<?php elseif (!isset($_GET['home'])): ?>
					<a class="lang1" href="<?php echo $link; ?>lg=ua">ua</a>
					<a class="lang2" href="<?php echo $link; ?>lg=en">en</a>
				<?php endif; ?>
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
					
					<?php if (isset($_GET['home'])): ?>
						<p class="headd"><?php echo $text['register']; ?></p>

						<form class="register" method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>">
							<p class="register"><?php echo $text['login']; ?></p>
							<p class="register_error" id="login_err">*</p>
							<input class="register" oninput="logincheck();buttoncheck();" type="text" name="reg_login" value="<?php if (isset($_SESSION['reg_login'])) {echo $_SESSION['reg_login'];} ?>">

							<p class="register"><?php echo $text['email']; ?></p> 
							<p class="register_error" id="email_err">*</p> 
							<input class="register" oninput="emailcheck();buttoncheck();" type="text" name="reg_email" value="<?php if (isset($_SESSION['reg_login'])) {echo $_SESSION['reg_email'];} ?>" >
								
							<p class="register"><?php echo $text['pass']; ?></p>
							<p class="register_error" id="pass_err">*</p>
							<input class="register" oninput="passcheck();buttoncheck();"  type="password" name="reg_pass" >
							
							<p class="register"><?php echo $text['rpass']; ?></p>
							<p class="register_error" id="rpass_err">*</p>
							<input class="register" oninput="rpasscheck();buttoncheck();" type="password" name="reg_rpass" > 
							
							<p class="register"></p>
							<p class="register_error" ></p>
							<button class="register_button" type="submit" name="reg_ok"  id="reg_ok" disabled="disabled"><?php echo $text['b_reg']; ?> </button>						
						</form>

						<p class="headd"><?php echo $text['author']; ?></p>

						<form class="login" method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>">						
							<p class="login"><?php echo $text['email']; ?></p> 
							<p class="login_error">*</p> 
							<input oninput="submitcheck()" class="login" type="text" name="log_email" value="<?php if (isset($_SESSION['log_email'])) {echo $_SESSION['log_email'];} ?>"> 
							
							<p class="login"><?php echo $text['pass']; ?></p> 
							<p class="login_error">*</p> 
							<input oninput="submitcheck()" class="login" type="password" name="log_pass" >
						
							<p class="login"></p> 
							<p class="login_error"><?php echo $error_l; ?></p> 
							<button class="login_button" type="submit" name="log_ok" disabled="disabled"> <?php echo $text['b_author']; ?></button>					
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
								<a href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="users"><?php echo $key['login']; ?></a>
								<button class="message_button" name="delete_user" value="<?php echo $key['id']; ?>" type="submit">x</button>	
								<button class="message_button" name="edit_user" value="<?php echo $key['id']; ?>" type="submit" >!</button>
							</form>		
						<?php
							endforeach;
							elseif (!isset($_SESSION['id']) || isset($_SESSION['id'])):
							$db_data = get_users();
							foreach ($db_data as $key):
						?>	
							<form class="users">		
								<img src="<?php echo $key['photo']; ?>" class="users"></img>	
								<a href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="users"><?php echo $key['login']; ?></a>	
							</form>	
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