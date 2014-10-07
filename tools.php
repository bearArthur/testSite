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
	if ((!isset($_GET['id'])) | (!isset($_GET['lg']))) {
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_SESSION['lang']}");
	}
	$id_page = $_GET['id'];
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");
	if (isset($_POST['ns_ok'])) {
		change_name_surname();
		header("Location: tools.php?id={$id_page}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['email_ok'])) {
		$err_em = change_email($text);
		if ($err_em == '') {
			header("Location: tools.php?id={$id_page}&lg={$_SESSION['lang']}");
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
	if (isset($_POST['pass_ok'])) {
		$err_pass = change_pass($text);
		if ($err_pass == '') {
			header("Location: tools.php?id={$id_page}&lg={$_SESSION['lang']}");
		}
	}
	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}	
	if (isset($_POST['tools'])) {
		header("Location: tools.php");
	}	
	if (isset($_POST['home'])) {
		header("Location: index.php");
	}
	if (isset($_POST['up_ok'])) {
		upload_image();
		header("Location: tools.php");
	}
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title> <?php echo $text['tools']; ?> </title>
		<link rel="stylesheet" type="text/css" href="Style.css"/>
		<meta charset="utf8"/>
	</head>

	<body>

		<div id="back">

			<div id="menu">
				<form method="POST"  action="tools.php?id=<?php echo $id_page; ?>&lg=<?php echo $_SESSION['lang'] ?>" id="menu_b">
					<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
					<button type="submit" name="profile"><?php echo $text['profile']; ?></button>
					<button type="submit" name="tools"><?php echo $text['tools']; ?></button>
					<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>
				</form>
				<a  href="tools.php?id=<?php echo $id_page; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
				<a  href="tools.php?id=<?php echo $id_page; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>
			</div>

			
			<div id="info">
				<img src="<?php echo $_SESSION['photo']; ?>"></img></br>
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['login']; ?></a>
				<table>
					<?php if (isset($_SESSION['id'])): ?>
						<tr>
							<td><p><?php echo $text['email']; ?></p></td>
							<td><p><?php echo $_SESSION['email']; ?></p></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td><p><?php echo $text['date_reg']; ?></p></td>
						<td><p><?php echo $_SESSION['registered']; ?></p></td>
					</tr>
					<tr>
						<td><p><?php echo $text['date_log']; ?></p></td>
						<td><p><?php echo $_SESSION['last_login']; ?></p></td>
					</tr>
				</table>
			</div>

			<div id="content">			

				<p class="headd"><?php echo $text['tools_user']; ?></p>
				
				<form method="POST" enctype="multipart/form-data" action="tools.php?id=<?php echo $id_page; ?>&lg=<?php echo $_SESSION['lang'] ?>">				
					<table>
						<tr>
							<td><?php echo $text['ch_ava']; ?></td>
							<td><input name="userfile" type="file"></td>
						</tr>
						<tr>
							<td></td>
							<td><button type="submit" name="up_ok">ok</button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $id_page; ?>&lg=<?php echo $_SESSION['lang'] ?>">
					<table>
						<tr>
							<td><?php echo $text['name']; ?></td>
							<td><input type="text" name="t_name"></td>
						</tr>
						<tr>
							<td><?php echo $text['surname']; ?></td>
							<td><input type="text" name="t_surname"></td>
						</tr>
						<tr>
							<td></td>
							<td><button type="submit" name="ns_ok">ok</button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $id_page; ?>&lg=<?php echo $_SESSION['lang'] ?>">
					<table>	
						<tr>
							<td><p><?php echo $text['email']; ?></p></td>
							<td><input type="text" name="t_email"/required></td>
						</tr>						
						<tr>
							<td><p><?php echo $text['pass']; ?></p></td>
							<td><input type="password" name="t_epass" /required></td>
						</tr>
						<tr>
							<td><p><?php echo $err_em; ?></p></td>
							<td><button type="submit" name="email_ok">ok</button></td>
						</tr>
					</table>
				</form>

				<form method="POST" action="tools.php?id=<?php echo $id_page; ?>&lg=<?php echo $_SESSION['lang'] ?>">
					<table>
						<tr>
							<td><p><?php echo $text['npass']; ?></p></td>
							<td><input type="password" name="t_npass" /required></td>
						</tr>							
						<tr>
							<td><p><?php echo $text['rpass']; ?></p></td>
							<td><input type="password" name="t_rpass" /required></td>
						</tr>				
						<tr>
							<td><p><?php echo $text['pass']; ?></p></td>
							<td><input type="password" name="t_pass" /required></td>
						</tr>
						<tr>
							<td><p><?php echo $err_pass; ?></p></td>
							<td><button type="submit" name="pass_ok">ok</button></td>
						</tr>
					</table>
				</form> 

			</div>

			<div id="ank"></div>
			
		</div>

	</body>

</html>