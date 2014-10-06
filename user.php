<?php
	session_start();
	mb_internal_encoding("UTF-8");	
	include('db.php');
	include ('log_reg.php');
	if (!$_SESSION['id']) {
		header("Location: index.php");
	}
	if ((!isset($_GET['lg'])) | (!isset($_GET['id'])) | (!isset($_GET['page']))) {
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	$id_page = $_GET['id'];
	$_SESSION['id_page'] = $id_page;
	$nn = $_GET['page'];
	get_page_data($id_page);
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");	
	if (isset($_POST['tools'])) {
		header("Location: tools.php");
	}	
	if (isset($_POST['send_ok'])) {
		message_in($id_page);
		header("Location: user.php?id={$id_page}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_ok'])) {		
		message_del();
		header("Location: user.php?id={$id_page}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_ed'])) {
		$_SESSION['id_mess'] = $_POST['mess_ed'];
		$message = get_message($_SESSION['id_mess']);
		if ($_SESSION['id'] == $message->id_user) {
			header("Location: edit.php?id={$_SESSION['id_page']}&page={$nn}&edit={$_SESSION['id_mess']}&lg={$_SESSION['lang']}");
		}
	}
	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['friends'])) {
		header("Location: users.php");
	}
	// $likn = "user.php";
	if (isset($_POST['lock'])) {
		lock_user($id_page);
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['unlock'])) {
		unlock_user($id_page);
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
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
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title><?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?></title>
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">

			<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="menu">
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=ua"><img src="images/ua.gif" class="lang"></img></a>
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=en"><img src="images/en.gif" class="lang"></img></a>
				<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>
				<button type="submit" name="tools"><?php echo $text['tools']; ?></button>
				<button type="submit" name="friends"><?php echo $text['people']; ?></button>
				<button type="submit" name="profile"><?php echo $text['profile']; ?></button>
				<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
			</form>

			
			<form id="info" method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>">
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['name'].'  '.$_SESSION['surname']; ?></a>
				<img src="<?php echo $_SESSION['photo']; ?>"></img>
				<table>
					<tr>
						<td><p><?php echo $text['email']; ?></p></td>
						<td><p><?php echo $_SESSION['email']; ?></p></td>
					</tr>
					<tr>
						<td><p><?php echo $text['date_reg']; ?></p></td>
						<td><p><?php echo $_SESSION['registered']; ?></p></td>
					</tr>
					<tr>
						<td><p><?php echo $text['date_log']; ?></p></td>
						<td><p><?php echo $_SESSION['last_login']; ?></p></td>
					</tr>
				</table>

				<?php 
					$check_b = user_check_b($_GET['id']);
					if ($check_b == true): 
				?>
					<p class="ban_error"><?php echo $text['ban_mess']; ?></p>
				<?php 
					endif; 
					$check_a = user_check_a($_GET['id']);
					if (($check_a == false) && ($_SESSION['admin'] == true) && ($check_b == false)): 
				?>
					<button name="lock" type="submit"><?php echo $text['ban']; ?></button>
				<?php elseif (($check_a == false) && ($_SESSION['admin'] == true) && ($check_b == true)): ?>
					<button name="unlock" type="submit"><?php echo $text['dis_ban']; ?></button>
				<?php endif; ?>
			</form>

			<div id="content">							

				<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="send">			
					<p class="headd"><?php echo $text['write_mess']; ?></p>	
					<textarea name="send_capt" rows="1" cols="68" /required></textarea>	
					<textarea name="send_mess" rows="4" cols="68" /required></textarea>			
					<button name="send_ok" type="submit"><?php echo $text['send']; ?></button>
				</form>			

				<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager_b">
					<a href="user.php?id=<?php echo $id_page; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><<</a>
					<a href="user.php?id=<?php echo $id_page; ?>&page=<?php if($nn > 1){echo $nn-1;}else{echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($id_page);
						$row_count = $temp[0];
						if ($row_count % 10 != 0) {
							$page_count = intval($row_count / 10) + 1;
						} 
						else {
							$page_count = intval($row_count / 10);
						}
						$n = 1;
							while ($n <= $page_count) {
								if ($n == $nn): ?>
									<a class="pager_light" href="user.php?id=<?php echo $id_page; ?>&page=<?php echo $n; ?>&lg=<?php echo $_SESSION['lang']; ?>"><?php echo $n; ?></a>								
								<?php	
									$n++;
									else: ?>
									<a class="pager" href="user.php?id=<?php echo $id_page; ?>&page=<?php echo $n; ?>&lg=<?php echo $_SESSION['lang']; ?>"><?php echo $n; ?></a>
								<?php
									$n++;
									endif;
							}
					?>
					<a href="user.php?id=<?php echo $id_page; ?>&page=<?php if ($nn + 1 <= $page_count) {echo $nn+1;} else {echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager">></a>
					<a href="user.php?id=<?php echo $id_page; ?>&page=<?php echo $page_count; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager">>></a>
				</form>
			
				<?php				
					$count = ($_GET['page']-1)*10;
					$db_data = message_out($count,$id_page);
					foreach ($db_data as $key): 
					if (strlen($key['message']) > 150) {
						$mess_out = mb_substr($key['message'],0,150).'...';
					}
					else {
						$mess_out = $key['message'];
					}
				?>
				<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="mess" id="mess_<?php echo $key['id']; ?>">		
					<img src="<?php echo $key['photo']; ?>" class="mess"></img>	
					<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
					<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<a href="user.php?id=<?php echo $key['id_user']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="capt"><?php echo $key['name'].' '.$key['surname']; ?></a><br/><br/>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $key['id']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="capth"><?php echo $key['capt']; ?></a><br/>			
					<p class="mess"><?php echo $mess_out; ?></p>	
					<?php if (strlen($key['message']) > 150): ?>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $key['id']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="captl"><?php echo $text['read_more']; ?></a><br/>
					<?php endif; ?>		
					<p class="date"><?php echo $key['date']; ?></p>
				</form>
				<?php	endforeach;	?>
			
				<form method="POST" action="user.php?id=<?php echo $id_page; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager_b">
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><<</a>
					<a href="user.php?page=<?php if($nn > 1){echo $nn-1;}else{echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($id_page);
						$row_count = $temp[0];
						if ($row_count % 10 != 0) {
							$page_count = intval($row_count / 10) + 1;
						} 
						else {
							$page_count = intval($row_count / 10);
						}
						$n = 1;
							while ($n <= $page_count) {
								if ($n == $nn): ?>
									<a class="pager_light" href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_SESSION['lang']; ?>"><?php echo $n; ?></a>								
								<?php	
									$n++;
									else: ?>
									<a class="pager" href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_SESSION['lang']; ?>"><?php echo $n; ?></a>
								<?php
									$n++;
									endif;
							}
					?>
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php if ($nn + 1 <= $page_count) {echo $nn+1;} else {echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager">></a>
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $page_count; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager">>></a>
				</form>
			
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>