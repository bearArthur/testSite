<?php
	session_start();
	include('db.php');
	include ('log_reg.php');
	mb_internal_encoding("UTF-8");
	if ((empty($_GET['page'])) || (empty($_GET['lg']))) {
		header("Location: user.php?id={$_SESSION['id_page']}&page=1&lg={$_SESSION['lang']}");
	}
	$id_page = $_GET['id'];
	$nn = $_GET['page'];
	if (isset($_GET['message'])) {
		$id_mess = $_GET['message'];
		$link = "edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}";
	}
	elseif (isset($_GET['edit'])) {
		$id_mess = $_GET['edit'];
		$link = "edit.php?id={$_GET['id']}&page={$_GET['page']}&edit={$_GET['edit']}&lg={$_GET['lg']}";
	}
	get_page_data($id_page);

	$_SESSION['id_page'] = $_GET['id'];
	$_SESSION['lang'] = $_GET['lg'];
	$nn = $_GET['page'];
	$text = get_language($_GET['lg']);	

	if (isset($_POST['mess_del_ok'])) {		
		message_del($_GET['message']);
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['mess_del_no'])) {
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['send_ok'])) {
		message_update($_GET['edit'], $_POST['send_mess'], $_POST['send_capt'], $_POST['send_mess_en'], $_POST['send_capt_en']);   
		header("Location: user.php?id={$_GET['id']}&page={$_GET['page']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['tools'])) {
		header("Location: tools.php?id={$_SESSION['id']}&lg={$_GET['lg']}");
	} 

	if (isset($_POST['mess_ed'])) {
			header("Location: edit.php?id={$_SESSION['id_page']}&page={$_GET['page']}&edit={$_POST['mess_ed']}&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['profile'])) {
		header("Location: user.php?id={$_SESSION['id']}&page={$_GET['page']}&lg={$_GET['lg']}");
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
		header("Location: user.php?id={$_SESSION['id']}&page=1&lg={$_SESSION['lang']}");
	}

	if (isset($_POST['friends'])) {
		header("Location: users.php?&lg={$_SESSION['lang']}");
	}
	
	if (isset($_POST['mess_ok'])):
		$_SESSION['id_mess'] = $_POST['mess_ok'];
?>
	<form class="check" method="POST" action="<?php echo $link; ?>">
	<p class="check_mess"><?php echo $text['check_mess']; ?></p>
	<button type="submit" name="mess_del_no" class="check_b"><?php echo $text['not']; ?></button>
	<button type="submit" name="mess_del_ok" class="check_b"><?php echo $text['ok']; ?></button>
	</form>
<?php
	endif;

	if (isset($_POST['com_del_ok'])) {
		coment_delete($_SESSION['id_com']);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['coment_ok'])):
		$_SESSION['id_com'] = $_POST['coment_ok'];
?>
	<form class="check" method="POST" action="<?php echo $link; ?>">
	<p class="check_mess"><?php echo $text['check_com']; ?></p>
	<button type="submit" name="com_del_no" class="check_b"><?php echo $text['not']; ?></button>
	<button type="submit" name="com_del_ok" class="check_b"><?php echo $text['ok']; ?></button>
	</form>
<?php
	endif;
	
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

	if (isset($_POST['send_coment'])) {
		coment_in($_POST['coment'], $_POST['coment_capt']);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mark_1'])) {
		set_mark($_SESSION['id'], $_POST['mark_1'], 1);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mark_2'])) {
		set_mark($_SESSION['id'], $_POST['mark_2'], 2);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");		
	}

	if (isset($_POST['mark_3'])) {
		set_mark($_SESSION['id'], $_POST['mark_3'], 3);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mark_4'])) {
		set_mark($_SESSION['id'], $_POST['mark_4'], 4);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['mark_5'])) {
		set_mark($_SESSION['id'], $_POST['mark_5'], 5);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}

	if (isset($_POST['del_mark'])) {
		del_mark($_POST['del_mark'], $_SESSION['id']);
		header("Location: edit.php?id={$_GET['id']}&page={$_GET['page']}&message={$_GET['message']}&cpage={$_GET['cpage']}&lg={$_GET['lg']}");
	}
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title> <?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?> </title>
		<link rel="shortcut icon" href="images/pencil.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<div id="back">

			<div id="menu">
				<form method="POST" action="<?php echo $link; ?>" id="menu_b">			
					<?php
						if (isset($_SESSION['id'])): 
					?>
						<button type="submit" name="profile" class="pic"><img src="images/user.png" class="butt"></button>
					<?php elseif (!isset($_SESSION['id'])): ?>
						<button type="submit" name="profile" class="pic" disabled="disabled"><img src="images/user_disabled.png" class="butt"></button>			
					<?php endif; ?>				
					<button type="submit" name="home" class="pic"><img src="images/home.png" class="butt"></button>
					<?php if (isset($_SESSION['id'])): ?>
						<button type="submit" name="tools" class="pic"><img src="images/tools.png" class="butt"></button>
					<?php	if ($_SESSION['role'] == 3): ?>
						<button  type="submit" name="site_tools" class="pic"><img src="images/sitetools.png" class="butt"></button>
					<?php 
						endif; 
						elseif (!isset($_SESSION['id'])): 
					?>
						<button type="submit" name="tools" class="pic" disabled="disabled"><img src="images/tools_disabled.png" class="butt"></button>						
					<?php endif; ?>
					<button type="submit" name="login" class="pic"><img src="images/doors.png" class="butt"></button>
				</form>
					<?php if (isset($_GET['message'])): ?>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>
					<?php elseif (isset($_GET['edit'])): ?>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
						<a  href="edit.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $nn; ?>&edit=<?php echo $_GET['edit']; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>
					<?php endif; ?>
			</div>
			
			<div id="info">
				<img src="<?php echo $_SESSION['photo']; ?>"></img></br>
				<a href="user.php?id=<?php echo $_SESSION['id_page']; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="name"><?php echo $_SESSION['login']; ?></a>
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
			</div>

			<div id="content">  

				<?php  
					if (isset($_GET['edit'])):
					$message = get_message($_GET['edit']);
				?>			
				<form method="POST" action="<?php echo $link; ?>" id="edit">     
					<p class="headd"><?php echo $text['edit_mess']; ?></p>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=1&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
					<p class="info_user_e"><?php echo $text['ukraine']; ?></p>
					<textarea class="edit_text" name="send_capt" rows="1" cols="54" /required><?php echo strip_tags($message->capt); ?></textarea>
					<textarea class="edit_text" name="send_mess" rows="15" cols="54" /required><?php echo strip_tags($message->message); ?></textarea>	
					<p class="info_user_e"><?php echo $text['english']; ?></p>						
					<textarea class="edit_text" name="send_capt_en" rows="1" cols="54" /required><?php echo strip_tags($message->capt_en); ?></textarea>
					<textarea class="edit_text" name="send_mess_en" rows="15" cols="54" /required><?php echo strip_tags($message->message_en); ?></textarea>							
					<p class="data_d"><?php echo $message->date; ?></p> 
					<button class="edit" name="send_ok" type="submit"><?php echo $text['edit']; ?></button>
				</form>   			
				<?php 
					elseif (isset($_GET['message'])): 
					$message = get_message($_GET['message']);
				?>
				<p class="headd"><?php echo $text['more']; ?></p>
				<form method="POST" action="<?php echo $link; ?>" id="edit">   					
					<?php 
						if (isset($_SESSION['id'])): 
							if ($_SESSION['role'] == 1):
								if ($_SESSION['id'] == $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php 
								elseif ($_SESSION['id'] == $message->id_page && $_SESSION['id'] != $message->id_user):
					 ?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
					<?php 
								endif;
							elseif ($_SESSION['role'] == 2):
								if ($_SESSION['id'] == $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
								elseif ($_SESSION['id'] == $message->id_page && $_SESSION['id'] != $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
					<?php
								elseif ($_SESSION['id'] != $message->id_page && $_SESSION['id'] == $message->id_user):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
								endif;
							elseif ($_SESSION['role'] == 3):
					?>
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
					<?php
							endif;
						endif;
					?>
					<img src="<?php echo $message->photo; ?>" class="edit_photo"></img> 
					<a href="user.php?id=<?php echo $message->id_user; ?>&page=<?php echo $_GET['page']; ?>&lg=<?php echo $_SESSION['lang']; ?>" class="edit_capt"><?php echo $message->login; ?></a>     <br/><br/>
					<?php if ($_GET['lg'] == 'ua'): ?>
						<p class="head"><?php echo $message->capt; ?></p>     
						<p class="mess"><?php echo $message->message; ?></p>   
					<?php elseif ($_GET['lg'] == 'en'): ?>  
						<p class="head"><?php echo $message->capt_en; ?></p>     
						<p class="mess"><?php echo $message->message_en; ?></p>   
					<?php endif; ?>
						<p class="data_d"><?php echo $message->date; ?></p>
					<div class="data_b">
							<?php 
								if (isset($_SESSION['id'])):
								$db_data_c = get_user_mark($message->id, $_SESSION['id']);
								if (empty($db_data_c)):
							?>
								<div class="mark">
									<p class="data_l"><?php echo $text['give_mark'].": "; ?></p>
									<button class="mark" type="submit" name="mark_1" value="<?php echo $message->id; ?>">1</button>
									<button class="mark" type="submit" name="mark_2" value="<?php echo $message->id; ?>">2</button>
									<button class="mark" type="submit" name="mark_3" value="<?php echo $message->id; ?>">3</button>
									<button class="mark" type="submit" name="mark_4" value="<?php echo $message->id; ?>">4</button>
									<button class="mark_l" type="submit" name="mark_5" value="<?php echo $message->id; ?>">5</button>
								</div>
							<?php 
								elseif (!empty($db_data_c)):								
							?>								
								<button class="mark" type="submit" name="del_mark" value="<?php echo $message->id; ?>">X</button>
								<p class="data"><?php echo $text['your_mark'].": ".$db_data_c->mark; ?></p>
							<?php 
								endif;
								endif;
							?>
						<p class="data">
							<?php 
								$db_data_c = get_mark($message->id);
								$mark = 0;
								$n = 0;
								if (!empty($db_data_c)) {
									foreach ($db_data_c as $val) {
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
						<p class="data_l"><?php $row_count = coment_get_row_count($message->id); echo $text['coments_count'].": ".$row_count[0]; ?></p>
					</div>
					</form>
					<?php if (isset($_SESSION['id'])): ?>
						<form class="coment_send" method="POST" action="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=<?php echo $_GET['lg']; ?>">
							<p class="coment_send"><?php echo $text['write_coment'] ?></p>
							<textarea class="coment_send_h" name="coment_capt"></textarea>
							<textarea class="coment_send_c" name="coment"></textarea>
							<button class="coment_send" name="send_coment"><?php echo $text['send'] ?></button>
						</form>
					<?php endif; ?>

						<div class="coment">

						<p class="coment_coments"><?php echo $text['coments'] ?></p>
						
						<?php
								$temp = coment_get_row_count($_GET['message']);
								$row_count = $temp[0];
								if ($row_count > 10):
						?>

						<form method="POST" action="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_up_com">
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=1&lg=<?php echo $_GET['lg']; ?>" class="pager_com"><<</a>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php if ($_GET['cpage'] > 1) {echo $_GET['cpage'] - 1;}else{echo $_GET['cpage'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com"><</a>
							<?php
								$temp = coment_get_row_count($_GET['message']);
								$row_count = $temp[0];
								if ($row_count % 10 != 0) {
									$page_count = intval($row_count / 10) + 1;
								} 
								else {
									$page_count = intval($row_count / 10);
								}
								$n = 1;
									while ($n <= $page_count) {
										if ($n == $_GET['cpage']): ?>
											<a class="pager_com_l" href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>								
										<?php	
											$n++;
											else: ?>
											<a class="pager_com" href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>
										<?php
											$n++;
											endif;
									}
							?>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php if (($_GET['cpage'] +1) <= $page_count) {echo $_GET['cpage'] + 1;}else{echo $_GET['cpage'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com">></a>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $page_count; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com">>></a>
						</form>

						<?php  
							endif;
							$row_count = ($_GET['cpage'] - 1) * 10;
							$db_data = coment_out($_GET['id'], $row_count, $_GET['message']);
							foreach ($db_data as $key):
						?>
							<form class="coment" method="POST" action="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=<?php echo $_GET['lg']; ?>">
								<img class="coment" src="<?php echo $key['photo']; ?>">
								<?php 
									if (isset($_SESSION['id'])): 
										if ($_SESSION['role'] == 1):
											if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id']):
								?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php 
											elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
								 ?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php 
											endif;
										elseif ($_SESSION['role'] == 2):
											if ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] == $key['id_user']):
								?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php
											elseif ($_SESSION['id'] == $key['id_page'] && $_SESSION['id'] != $key['id_user']):
								?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php
											elseif ($_SESSION['id'] != $key['id_page'] && $_SESSION['id'] == $key['id']):
								?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php
											endif;
										elseif ($_SESSION['role'] == 3):
								?>
									<button class="coment" type="submit" name="coment_ok" value="<?php echo $key['cid']; ?>">x</button>
								<?php
										endif;
									endif;
								?>
								<a class="coment" href="user.php?id=<?php echo $key['id']; ?>&page=1&lg=<?php echo $_GET['lg'] ?>"><?php echo $key['login']; ?></a>
								<p class="coment_h"><?php echo $key['capt']; ?></p>
								<p class="coment_c"><?php echo $key['coment']; ?></p>
								<p class="coment_d"><?php echo $key['date']; ?></p>
							</form>
						<?php
							endforeach;
							$temp = coment_get_row_count($_GET['message']);
							$row_count = $temp[0];
							if ($row_count > 10):
						?>

						<form method="POST" action="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $_GET['cpage']; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_down_com">
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=1&lg=<?php echo $_GET['lg']; ?>" class="pager_com"><<</a>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php if ($_GET['cpage'] > 1) {echo $_GET['cpage'] - 1;}else{echo $_GET['cpage'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com"><</a>
							<?php
								$temp = coment_get_row_count($_GET['message']);
								$row_count = $temp[0];
								if ($row_count % 10 != 0) {
									$page_count = intval($row_count / 10) + 1;
								} 
								else {
									$page_count = intval($row_count / 10);
								}
								$n = 1;
									while ($n <= $page_count) {
										if ($n == $_GET['cpage']): ?>
											<a class="pager_com_l" href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>								
										<?php	
											$n++;
											else: ?>
											<a class="pager_com" href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $n; ?>&lg=<?php echo $_GET['lg']; ?>"><?php echo $n; ?></a>
										<?php
											$n++;
											endif;
									}
							?>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php if (($_GET['cpage'] +1) <= $page_count) {echo $_GET['cpage'] + 1;}else{echo $_GET['cpage'];} ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com">></a>
							<a href="edit.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $_GET['page']; ?>&message=<?php echo $_GET['message']; ?>&cpage=<?php echo $page_count; ?>&lg=<?php echo $_GET['lg']; ?>" class="pager_com">>></a>
						</form>
						<?php elseif ($row_count == 0): ?>
							<p class="coment_r"><?php echo $text['no_coment'] ?></p>
						<?php endif; ?>
						</div>
						
				<?php endif; ?>
				
			</div>

			<div id="ank"></div>

		</div>

	</body>

</html>