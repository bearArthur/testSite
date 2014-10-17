<?php
	session_start();
	mb_internal_encoding("UTF-8");	
	include('db.php');
	include ('log_reg.php');

	if (empty($_GET['lg']) || empty($_GET['page']) || empty($_GET['id'])) {
		if (isset($_SESSION['id_page']) && isset($_SESSION['lang'])) {
			header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
		}
		else {
			header("Location: index.php");
		}
	}

	$_SESSION['id_page'] = $_GET['id'];
	$_SESSION['lang'] = $_GET['lg'];
	get_page_data($_GET['id']);
	$text = get_language($_GET['lg']);	
	
	if (isset($_POST['tools'])) {
		$_SESSION['id_page'] = $_SESSION['id'];
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_GET['lg']}");
	}	

	if (isset($_POST['home'])) {
		header("Location: index.php");
	}

	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
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

	if (isset($_POST['send_ok'])) {
		message_in($_GET['id'], $_SESSION['id']);
		header("Location: user.php?id={$_GET['id']}&page=1&lg={$_GET['lg']}");
	}

	if (isset($_POST['mess_ed'])) {
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&edit={$_POST['mess_ed']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['lock'])) {
		lock_user($_GET['id']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['unlock'])) {
		unlock_user($_GET['id']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mess_ok'])):
		$_SESSION['id_mess'] = $_POST['mess_ok'];
	?>
		<form class="check" method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>">
			<p class="check_mess"><?php echo $text['check_mess']; ?></p>
			<button type="submit" name="mess_del_no" class="check_b"><?php echo $text['not']; ?></button>
			<button type="submit" name="mess_del_ok" class="check_b"><?php echo $text['ok']; ?></button>
		</form>
	<?php
	endif;

	if (isset($_POST['mess_del_no'])) {
		unset($_SESSION['id_mess']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mess_del_ok'])) {		
		message_del($_SESSION['id_mess']);
		unset($_SESSION['id_mess']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['edit_user'])) {
		$_SESSION['id_page'] = $_POST['edit_user'];
		header("Location: tools.php?id={$_SESSION['id_page']}&lg={$_GET['lg']}");
	}	

	if (isset($_POST['delete_user'])):
	$_SESSION['delete_id'] = $_POST['delete_user'];
	?>
		<form class="check" method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>">
			<p class="check_mess"><?php echo $text['check_user']; ?></p>
			<button type="submit" name="del_no" class="check_b"><?php echo $text['not']; ?></button>
			<button type="submit" name="del_ok" class="check_b"><?php echo $text['ok']; ?></button>
		</form>
	<?php
	endif;

	if (isset($_POST['del_ok'])) {
		delete_user($_SESSION['delete_id']);
		header("Location: user.php");
	}

	if (isset($_POST['del_no'])){
		header("Location: user.php");
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
		<title><?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?></title>
		<link rel="shortcut icon" href="images/userl.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/Style.css">
		<link rel="stylesheet" type="text/css" href="style/menu.css">
		<link rel="stylesheet" type="text/css" href="style/info.css">
		<link rel="stylesheet" type="text/css" href="style/register.css">
		<link rel="stylesheet" type="text/css" href="style/login.css">
		<link rel="stylesheet" type="text/css" href="style/users.css">
		<link rel="stylesheet" type="text/css" href="style/send.css">
		<link rel="stylesheet" type="text/css" href="style/pager.css">
		<link rel="stylesheet" type="text/css" href="style/message.css">
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/photo.js"></script>
		<meta charset="utf8">
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
				<a class="lang1" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=ua">ua</a>
				<a class="lang2" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=en">en</a>		
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

				<?php if (isset($_SESSION['id']) && $_SESSION['role'] != 1): ?>	
					<p class="headd"><?php echo $text['write_mess']; ?></p>	
					<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="send">						
						<p class="send"><?php echo $text['ukraine']; ?></p>
						<textarea type="text" class="send_capt"  name="send_capt" /required></textarea>	
						<textarea type="text" class="send_mess" name="send_mess" /required></textarea>		
						<p class="send"><?php echo $text['english']; ?></p>
						<textarea type="text" class="send_capt" name="send_capt" /required></textarea>	
						<textarea type="text" class="send_mess" cols="15" name="send_mess_en" /required></textarea>		
						<button class="send_button" name="send_ok" type="submit"><?php echo $text['send']; ?></button>
					</form>		

				<?php elseif (isset($_SESSION['id']) && $_SESSION['role'] == 1 && $_SESSION['id'] == $_GET['id']): ?>			
					<p class="headd"><?php echo $text['write_mess']; ?></p>	
					<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="send">
						<p class="send"><?php echo $text['ukraine']; ?></p>
						<textarea type="text" class="send_capt"  name="send_capt" /required></textarea>	
						<textarea type="text" class="send_mess" name="send_mess" /required></textarea>		
						<p class="send"><?php echo $text['english']; ?></p>
						<textarea type="text" class="send_capt" name="send_capt" /required></textarea>	
						<textarea type="text" class="send_mess" cols="15" name="send_mess_en" /required></textarea>		
						<button class="send_button" name="send_ok" type="submit"><?php echo $text['send']; ?></button>
					</form>		
				<?php endif; ?>

				<p class="headd"><?php echo $text['messages'] ?></p>

				<?php 					
					$temp = get_row_count($_GET['id']);
					$row_count = $temp[0];
					if ($row_count > 10):
				?>

				<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="pager"><<</a>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php if($_GET['page'] > 1){echo $_GET['page'] - 1;}else{echo $_GET['page'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($_GET['id']);
						$row_count = $temp[0];
						if ($row_count % 10 != 0) {
							$page_count = intval($row_count / 10) + 1;
						} 
						else {
							$page_count = intval($row_count / 10);
						}
						$n = 1;
							while ($n <= $page_count) {
								if ($n == $_GET['page']): ?>
									<a class="pager_light" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>								
								<?php	
									$n++;
									else: ?>
									<a class="pager" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>
								<?php
									$n++;
									endif;
							}
					?>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php if ($_GET['page'] + 1 <= $page_count) {echo $_GET['page'] + 1;} else {echo $_GET['page'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">></a>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page_count; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">>></a>
				</form>

				<?php 
					endif; 
					$temp = get_row_count($_GET['id']);
					$row_count = $temp[0];
					if ($row_count != 0):
				?>
			
				<?php				
					$count = ($_GET['page']-1)*10;
					$db_data = message_out($count, $_GET['id']);
					foreach ($db_data as $key): 
					if (strlen($key['message']) > 150) {
						$mess_out = mb_substr($key['message'],0,150).'...';
					}
					else {
						$mess_out = $key['message'];
					}
				?>
				<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="message" id="mess_<?php echo $key['id']; ?>">		
					<img src="<?php echo $key['photo']; ?>" class="message"></img>	
					<?php 
						if (isset($_SESSION['id'])): 
							if ($_SESSION['role'] == 1):
								if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="message_button" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php 
								elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
					 ?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
					<?php 
								endif;
							elseif ($_SESSION['role'] == 2):
								if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="message_button" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
								elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
					?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
					<?php
								elseif ($_SESSION['id'] != $key['id_page'] && $_SESSION['id'] == $key['id_user']):
					?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="message_button" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
								endif;
							elseif ($_SESSION['role'] == 3):
					?>
						<button class="message_button" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
						<button class="message_button" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
					<?php
							endif;
						endif;
					?>
					<a href="user.php?id=<?php echo $key['id_user']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="message_login"><?php echo $key['login']; ?></a><br/>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $key['id']; ?>&cpage=1&lg=<?php echo $_GET['lg']; ?>" class="message_h"><?php echo $key['capt']; ?></a><br/>			
					<p class="message"><?php echo $mess_out; ?></p>	
					<?php if (strlen($key['message']) > 150): ?>
					<a href="edit.php?id=<?php echo $key['id_page']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $key['id']; ?>&cpage=1&lg=<?php echo $_GET['lg']; ?>" class="message_more"><?php echo $text['read_more']; ?></a><br/>
					<?php endif; ?>		
						<p class="data_d"><?php echo $key['date']; ?></p>
					<div class="data_b">
							<?php 
								if (isset($_SESSION['id'])):
								$db_data = get_user_mark($key['id'], $_SESSION['id']);
								if (!empty($db_data)):								
							?>								
								<p class="data"><?php echo $text['your_mark'].": ".$db_data->mark; ?></p>
							<?php 
								endif;
								endif;
							?>
						<p class="data">
							<?php 
								$db_data = get_mark($key['id']);
								$mark = 0;
								$n = 0;
								if (!empty($db_data)) {
									foreach ($db_data as $val) {
										$mark += $val['mark'];
										$n++;
									}
									$mark /= $n;
									echo $text['mark'].": ".round($mark,1);
								} 
								else {
									echo $text['no_mark'];
								}
							?>
						</p>
						<?php if ($n != 0): ?>
							<p class="data"><?php echo $text['rated'].": ".$n; ?></p>
						<?php endif; ?>
						<p class="data_l"><?php $row_count = coment_get_row_count($key['id']); echo $text['coments_count'].": ".$row_count[0]; ?></p>
					</div>
				</form>
				<?php	endforeach;	?>

				<?php elseif ($row_count == 0): ?>
					<p class="message_no"><?php echo $text['no_message'] ?></p>
			
				<?php 					
					endif;
					$temp = get_row_count($_GET['id']);
					$row_count = $temp[0];
					if ($row_count > 10):
				?>

				<form method="POST" action="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=1&lg=<?php echo $_GET['lg']; ?>" class="pager"><<</a>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php if($_GET['page'] > 1){echo $_GET['page'] - 1;}else{echo $_GET['page'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager"><</a>
					<?php
						$temp = get_row_count($_GET['id']);
						$row_count = $temp[0];
						if ($row_count % 10 != 0) {
							$page_count = intval($row_count / 10) + 1;
						} 
						else {
							$page_count = intval($row_count / 10);
						}
						$n = 1;
							while ($n <= $page_count) {
								if ($n == $_GET['page']): ?>
									<a class="pager_light" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>								
								<?php	
									$n++;
									else: ?>
									<a class="pager" href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>
								<?php
									$n++;
									endif;
							}
					?>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php if ($_GET['page'] + 1 <= $page_count) {echo $_GET['page'] + 1;} else {echo $_GET['page'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">></a>
					<a href="user.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page_count; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager">>></a>
				</form>

			<?php endif; ?>
			
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>