<?php
	session_start();
	mb_internal_encoding("UTF-8");
	include('db.php');
	include('log_reg.php');
	if (!isset($_GET['lg'])) {
		if (empty($_SESSION['lang'])) {
			header("Location: index.php?lg=ua");
		}
		else {
			header("Location: index.php?lg={$_SESSION['lang']}");
		}
	}
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");
	$error_r = '';
	$error_l = '';

	if(isset($_POST['reg_ok'])){
		$error_r = register($text);
		if ($error_r == $text['r_access']) {
			header("Location: index.php?lg={$_SESSION['lang']}");
		}
	}

	if(isset($_POST['log_ok'])){
		$error_l = log_in($text);	
		if ($error_l == '') {					
			header("Location: user.php");		
		}		
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

			<div id="menu">
				<a  href="index.php?lg=ua"><img src="images/ua.gif" class="lang"></img></a>
				<a  href="index.php?lg=en"><img src="images/en.gif" class="lang"></img></a>
			</div>

			<div id="info">
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1" class="name"><?php echo $text['name'].'  '.$text['surname']; ?></a>
				<img src="images/users/none.jpg"></img>
			</div>
		
			<div id="content">
		
				<form id="register" method="POST" action="index.php?lg=<?php echo $_SESSION['lang']; ?>">
					<p class="headd"><?php echo $text['register']; ?></p>
					<table>
						<tr>
							<td> <p><?php echo $text['name']; ?></p> </td>
							<td> <input type="text" name="reg_name" /required> </td>
						</tr>
						<tr>
							<td> <p><?php echo $text['surname']; ?></p> </td>
							<td> <input type="text" name="reg_surname" /required> </td>
						</tr>
						<tr>
							<td> <p><?php echo $text['email']; ?></p> </td>
							<td> <input type="text" name="reg_login" /required> </td>
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
							<td> <input type="text" name="log_login" /required> </td>
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

			</div>
			
			<div id="ank"></div>

		</div>	
	
	</body>

</html>