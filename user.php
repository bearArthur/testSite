<?php
	session_start();
	mb_internal_encoding("UTF-8");	
	include('db.php');
	include ('log_reg.php');
	if (!isset($_GET['lg']) && !isset($_GET['page']) && !isset($_GET['id'])) {
		if (isset($_SESSION['id'])) {
			if (isset($_SESSION['lang'])) {
				$_SESSION['link'] = "user.php?id={$_SESSION['id']}&page=1&";
				header("Location: {$_SESSION['link']}lg={$_SESSION['lang']}");
			}
			else {
				$_SESSION['link'] = "user.php?id={$_SESSION['id']}&page=1&";
				header("Location: {$_SESSION['link']}lg=ua");
			}
		}
		else {
			if (isset($_SESSION['lang'])) {
				$_SESSION['link'] = "index.php?";
				header("Location: {$_SESSION['link']}lg={$_SESSION['lang']}");
			}
			else {
				$_SESSION['link'] = "index.php?";
				header("Location: {$_SESSION['link']}lg=ua");
			}
		}
	}
	// if (!isset($_GET['lg']) | !isset($_GET['page']) | !isset($_GET['id'])) {
	// 	header("Location: user.php");
	// }
	$_SESSION['id_page'] = $_GET['id'];
	$nn = $_GET['page'];
	get_page_data($_SESSION['id_page']);
	$_SESSION['lang'] = $_GET['lg'];
	$text = parse_ini_file($_SESSION['lang'].".ini");	
	if (isset($_POST['tools'])) {
		$_SESSION['id_page'] = $_SESSION['id'];
		header("Location: tools.php");
	}	
	if (isset($_POST['send_ok'])) {
		message_in();
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_del_ok'])) {		
		message_del($_SESSION['id_mess']);
		header("Location: user.php?id={$_SESSION['id_page']}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_ed'])) {
		header("Location: edit.php?id={$_SESSION['id_page']}&page={$nn}&edit={$_POST['mess_ed']}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['mess_del_no'])) {
		header("Location: user.php?id={$_SESSION['id_page']}&page={$nn}&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}
	// $likn = "user.php";
	if (isset($_POST['lock'])) {
		lock_user();
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	if (isset($_POST['unlock'])) {
		unlock_user();
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
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
		header("Location: index.php");
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
		<title><?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?></title>
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">

			<div id="menu">
				<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="menu_b">					
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
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
				<a  href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>				
			</div>
			
			<form id="info" method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>">
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

				<?php 
					$check_b = user_check_b($_GET['id']);
					if ($check_b == true): 
				?>
					<p class="ban_error"><?php echo $text['ban_mess']; ?></p>
				<?php 
					endif; 
					if ((isset($_SESSION['role'])) && ($_SESSION['role'] == 3) && ($_SESSION['id'] != $_SESSION['id_page']) && ($check_b == FALSE)): 
				?>
					<button name="lock" type="submit"><?php echo $text['ban']; ?></button>
				<?php elseif ((isset($_SESSION['role'])) && ($_SESSION['role'] == 3) && ($_SESSION['id'] != $_SESSION['id_page']) && ($check_b == TRUE)): ?>
					<button name="unlock" type="submit"><?php echo $text['dis_ban']; ?></button>
				<?php endif; ?>
			</form>

			<div id="content">							

				<?php if (isset($_SESSION['id']) && $_SESSION['role'] != 1): ?>	
					<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="send">			
						<p class="headd"><?php echo $text['write_mess']; ?></p>	
						<textarea name="send_capt" rows="1" cols="68" /required></textarea>	
						<textarea name="send_mess" rows="4" cols="68" /required></textarea>			
						<button name="send_ok" type="submit"><?php echo $text['send']; ?></button>
					</form>		
				<?php elseif (isset($_SESSION['id']) && $_SESSION['role'] == 1 && $_SESSION['id'] == $_SESSION['id_page']): ?>
					<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" id="send">			
						<p class="headd"><?php echo $text['write_mess']; ?></p>	
						<textarea name="send_capt" rows="1" cols="68" /required></textarea>	
						<textarea name="send_mess" rows="4" cols="68" /required></textarea>			
						<button name="send_ok" type="submit"><?php echo $text['send']; ?></button>
					</form>		
				<?php endif; ?>

				<p class="headd1"><?php echo $text['messages'] ?></p>

				<?php 					
					$temp = get_row_count($_SESSION['id_page']);
					$row_count = $temp[0];
					if ($row_count > 10):
				?>

				<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager_b">
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><<</a>
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php if($nn > 1){echo $nn-1;}else{echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($_SESSION['id_page']);
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

				<?php 
					endif; 
					$temp = get_row_count($_SESSION['id_page']);
					$row_count = $temp[0];
					if ($row_count != 0):
				?>
			
				<?php				
					$count = ($_GET['page']-1)*10;
					$db_data = message_out($count, $_SESSION['id_page']);
					foreach ($db_data as $key): 
					if (strlen($key['message']) > 150) {
						$mess_out = mb_substr($key['message'],0,150).'...';
					}
					else {
						$mess_out = $key['message'];
					}
				?>
				<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="mess" id="mess_<?php echo $key['id']; ?>">		
					<img src="<?php echo $key['photo']; ?>" class="mess"></img>	
					<?php 
						if (isset($_SESSION['id'])): 
							if ($_SESSION['role'] == 1):
								if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php 
								elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
					 ?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
					<?php 
								endif;
							elseif ($_SESSION['role'] == 2):
								if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
								elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
					<?php
								elseif ($_SESSION['id'] != $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
								endif;
							elseif ($_SESSION['role'] == 3):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
							endif;
						endif;
					?>
					<a href="user.php?id=<?php echo $key['id_user']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="capt"><?php echo $key['login']; ?></a><br/><br/>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $key['id']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="capth"><?php echo $key['capt']; ?></a><br/>			
					<p class="mess"><?php echo $mess_out; ?></p>	
					<?php if (strlen($key['message']) > 150): ?>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $key['id']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="captl"><?php echo $text['read_more']; ?></a><br/>
					<?php endif; ?>		
					<p class="date"><?php echo $key['date']; ?></p>
				</form>
				<?php	endforeach;	?>

				<?php elseif ($row_count == 0): ?>
					<p class="no_message"><?php echo $text['no_message'] ?></p>
			
				<?php 					
					endif;
					$temp = get_row_count($_SESSION['id_page']);
					$row_count = $temp[0];
					if ($row_count > 10):
				?>

				<form method="POST" action="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager_b">
					<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><<</a>
					<a href="user.php?page=<?php if($nn > 1){echo $nn-1;}else{echo $nn;} ?>&lg=<?php echo $_SESSION['lang']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($_SESSION['id_page']);
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

			<?php endif; ?>
			
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>