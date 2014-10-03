<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");
	if (!$_SESSION['id']) {
		header("Location: index.php");
	}
	if (!isset($_GET['lg'])) {
		header("Location: users.php?lg={$_SESSION['lang']}");
	}
	$id_page = $_SESSION['id'];
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");	
	if (isset($_POST['tools'])) {
		header("Location: tools.php");
	}	
	if (isset($_POST['home'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['exit'])) {
		log_out();
	}
	if (isset($_POST['friends'])) {
		header("Location: users.php");
	}
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title><?php echo $text['people']; ?></title>
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">
			<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="menu">
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=ua"><img src="images/ua.gif" class="lang"></img></a>
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=en"><img src="images/en.gif" class="lang"></img></a>
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
			</div>

			<div id="ank"></div>

		</div>

	</body>
	
</html>