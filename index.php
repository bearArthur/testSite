<?php
	session_start();
	mb_internal_encoding("UTF-8");
	include('db.php');
	include('log_reg.php');
	if (!isset($_GET['lg'])) {
		if (isset($_GET['home'])) {
			if ($_SESSION['lang']) {
				$_SESSION['link'] = "index.php?home=login&";
				header("Location: {$_SESSION['link']}lg={$_SESSION['lang']}");
			}
			else {
				$_SESSION['link'] = "index.php?home=login&";
				header("Location: {$_SESSION['link']}lg=ua");
			}
		}
		else{
			if ($_SESSION['lang']) {
				$_SESSION['link'] = "index.php?";
				header("Location: {$_SESSION['link']}lg={$_SESSION['lang']}");
			}
			else {
				$_SESSION['link'] = "index.php?";
				header("Location: {$_SESSION['link']}lg=ua");
			}
		}
	}
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");
	$error_r = '';
	$error_l = '';	
	if(isset($_POST['reg_ok'])){
		$error_r = register($text);
		if ($error_r == $text['r_access']) {	
			header("Location: user.php");	
		}
	}
	if(isset($_POST['log_ok'])){
		$error_l = log_in($text);	
		if ($error_l == '') {					
			header("Location: user.php");		
		}		
	}
	if (isset($_POST['login'])) {
		if (!$_SESSION['id']) {
			header("Location: index.php?home=login&lg={$_SESSION['lang']}");
		}
		else {			
			log_out();
		}
	}
	if (isset($_POST['home'])) {
		header("Location: index.php?lg={$_SESSION['lang']}");
	}
	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['friends'])) {
		header("Location: users.php");
	}
	if (isset($_POST['tools'])) {
		header("Location: tools.php");
	}	
?>

<!DOCTYPE HTML5>

<html>
	
	<head>
		<title> index </title>
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>
	
	<body>
		
		<div id="back">

			<form method="POST" action="<?php echo $_SESSION['link']; ?>lg=<?php echo $_SESSION['lang']; ?>" id="menu">
				<a  href="<?php echo $_SESSION['link']; ?>lg=ua"><img src="images/ua.gif" class="lang"></img></a>
				<a  href="<?php echo $_SESSION['link']; ?>lg=en"><img src="images/en.gif" class="lang"></img></a>
				<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>

				<?php if (isset($_SESSION['id'])): ?>
					<button type="submit" name="tools"><?php echo $text['tools']; ?></button>
					<button type="submit" name="friends"><?php echo $text['people']; ?></button>
					<button type="submit" name="profile"><?php echo $text['profile']; ?></button>
				<?php endif; ?>

				<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
			</form>

			<div id="info">
				<?php if (isset($_SESSION['id'])): ?>
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['name'].'  '.$_SESSION['surname']; ?></a>
					<img src="<?php echo $_SESSION['photo']; ?>"></img>
				<?php elseif (!isset($_SESSION['id'])): ?>
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1" class="name"><?php echo $text['name'].'  '.$text['surname']; ?></a>
					<img src="images/users/none.jpg"></img>
				<?php endif; ?>			
			</div>
		
			<div id="content">
				
				<?php if (isset($_GET['home'])): ?>

					<form id="register" method="POST" action="index.php?lg=<?php echo $_SESSION['lang']; ?>">
						<p class="headd"><?php echo $text['register']; ?></p>
						<table>
							<tr>
								<td> <p><?php echo $text['login']; ?></p> </td>
								<td> <input type="text" name="reg_login" /required> </td>
							</tr>
							<tr>
								<td> <p><?php echo $text['email']; ?></p> </td>
								<td> <input type="text" name="reg_email" /required> </td>
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

					<form id="login" method="POST" action="index.php?lg=<?php echo $_SESSION['lang']; ?>">
						<p class="headd"><?php echo $text['author']; ?></p>
						<table>
							<tr>
								<td> <p><?php echo $text['email']; ?></p> </td>
								<td> <input type="text" name="log_email" /required> </td>
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
						$db_data = get_users();
						foreach ($db_data as $key):
					?>
						<div class="users">		
							<img src="<?php echo $key['photo']; ?>" class="users"></img>	
							<a href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="head"><?php echo $key['name'].' '.$key['surname']; ?></a>	
						</div>		
					<?php
						endforeach;
					?>	

				<?php endif; ?>

			</div>
			
			<div id="ank"></div>

		</div>	
	
	</body>

</html>