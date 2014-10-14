<?php
	session_start();
	mb_internal_encoding("UTF-8");	
	include('db.php');
	include ('log_reg.php');

	if (isset($_SESSION['id']) && ($_SESSION['role'] == 3)) {
		if (empty($_GET['lg']) || empty($_GET['template'])) {
			if (isset($_SESSION['lang'])) {
				header("Location: edit_page.php?template=user&lg={$_SESSION['lang']}");
			}
			else {
				header("Location: edit_page.php?template=user&lg=ua");
			}
		}
	}else {
		header("Location: index.php");
	}

	
	$_SESSION['lang'] = $_GET['lg'];
	$text = get_language($_GET['lg']);	
	


	if (isset($_POST['site_tools'])) {
		$name = $_POST['edit_name'];
		change_user_names($_GET['lg'], $_SESSION['mass']);
		header("Location: user.php");
	}
?>

<!DOCTYPE HTMl5>

<html>

	<head>
		<title><?php echo $_SESSION['name'].' '.$_SESSION['surname']; ?></title>
		<link rel="shortcut icon" href="images/userl.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="Style.css">
		<meta charset="utf8">
	</head>

	<body>

		<form id="back" method="POST" action="edit_page.php?template=<?php echo $_GET['template']; ?>&lg=<?php echo $_GET['lg']; ?>" >

			<div id="menu">
				<div id="menu_b">
					<button type="submit" name="profile" disabled="disabled" class="pic"><img src="images/user_disabled.png" class="butt"></button>
					<button type="submit" disabled="disabled" name="home" class="pic"><img src="images/home_disabled.png" class="butt"></button>
					<button type="submit" disabled="disabled" name="tools" class="pic"><img src="images/tools_disabled.png" class="butt"></button>
					<button  type="submit" name="site_tools" class="pic"><img src="images/sitetools.png" class="butt"></button>
					<button type="submit" disabled="disabled" name="login" class="pic"><img src="images/doors_disabled.png" class="butt"></button>
				</div>
				<a  href="edit_page.php?template=<?php echo $_GET['template']; ?>&lg=ua"><img src="images/ua.gif" class="lang1"></img></a>
				<a  href="edit_page.php?template=<?php echo $_GET['template']; ?>&lg=en"><img src="images/en.gif" class="lang2"></img></a>	
				</div>
			
			<div id="info">
				<button class="mess_b" name="edit_user" value="<?php echo $_GET['id']; ?>" type="submit" >!</button>
				<button class="mess_b_x" name="delete_user" value="<?php echo $_GET['id']; ?>" type="submit">x</button>	</br></br>
				<img src="images/users/none.jpg"></img></br>
				<input class="edit_blue_l" name="edit_gest" type="text" value="<?php echo $text['gest']; ?>">
				<table>
						<tr>
							<td><input name="edit_name" class="edit_blue" type="text" value="<?php echo $text['name']; ?>"></td>
						</tr>
						<tr>
							<td><input class="edit_blue" name="edit_surname" type="text" value="<?php echo $text['surname']; ?>"></td>
						</tr>
						<tr>
							<td><input class="edit_blue" name="edit_email" type="text" value="<?php echo $text['email']; ?>"></td>
						</tr>
					<tr>
						<td><input class="edit_blue" name="edit_date_reg" type="text" value="<?php echo $text['date_reg']; ?>"></td>
					</tr>
					<tr>
						<td><input class="edit_blue" name="edit_date_log" type="text" value="<?php echo $text['date_log']; ?>"></td>
					</tr>
				</table>

					<input class="edit_err" size="30" name="edit_ban_mess" type="text" value="<?php echo $text['ban_mess']; ?>">

					<div class="edit_check">
						<input class="edit_question" name="edit_check_mess" size="30" type="text" value="<?php echo $text['check_mess']; ?>"></br>
						<input class="edit_question_button" name="edit_not" size="10" type="text" value="<?php echo $text['not']; ?>">
						<input class="edit_question_button" name="edit_ok" size="10" type="text" value="<?php echo $text['ok']; ?>">
					</div>

					<div class="edit_check">
						<input class="edit_question" name="edit_check_user" size="30" type="text" value="<?php echo $text['check_user']; ?>"></br>
						<input class="edit_question_button" name="edit_not" size="10" type="text" value="<?php echo $text['not']; ?>">
						<input class="edit_question_button" name="edit_ok" size="10" type="text" value="<?php echo $text['ok']; ?>">
					</div>

					<div class="edit_check">
						<input class="edit_question" name="edit_check_page" size="30" type="text" value="<?php echo $text['check_page']; ?>"></br>
							<a class="check_page" href=""><img src="images/homec.png"></img></a>
							<a class="check_page" href=""><img src="images/userc.png"></img></a>
							<a class="check_page" href=""><img src="images/toolsc.png"></img></a>
							<a class="check_page" href=""><img src="images/pencilc.png"></img></a>
							<a class="check_page" href=""><img src="images/pencilc.png"></img></a>
							<a class="check_page" href=""><img src="images/doorsc.png"></img></a>
						<input class="edit_question_button" name="edit_close" size="10" type="text" value="<?php echo $text['close']; ?>">
					</div>

			</div>

			<div id="content">			

				<?php if ($_GET['template'] == 'user'): ?>			
					<div id="send">	
						<input class="edit_head" name="edit_write_mess" size="69" type="text" value="<?php echo $text['write_mess']; ?>"></br></br>
						<input class="edit_blue" name="edit_ukraine" type="text" value="<?php echo $text['ukraine']; ?>">
						<textarea name="send_capt" rows="1" cols="68" ></textarea>	
						<textarea name="edit_blue" rows="4" cols="68" ></textarea>		
						<input class="edit_blue" name="edit_english" type="text" value="<?php echo $text['english']; ?>">
						<textarea name="send_capt_en" rows="1" cols="68" ></textarea>	
						<textarea name="send_mess_en" rows="4" cols="68" ></textarea></br>	
						</br><input class="edit_button" name="edit_send" type="text" value="<?php echo $text['send']; ?>">
					</div>	

							
			
					<div class="mess">
						<img src="images/users/none.jpg" class="edit_mess"></img>	
						<input name="edit_messages" class="edit_head" type="text" size="69" value="<?php echo $text['messages']; ?>">
							<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $key['id']; ?>">x</button>
							<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $key['id']; ?>">!</button>
						<a href="" class="capt"><?php echo $text['login']; ?></a><br/><br/>
						<a href="" class="capth"><?php echo $text['capt']; ?></a><br/>			
						<p class="mess"><?php echo $text['test_message']; ?></p>	
						<?php if (strlen($text['test_message']) > 150): ?>
						<input name="edit_read_more" class="edit_button_blue" type="text" value="<?php echo $text['read_more']; ?>">
						<?php endif; ?>		
						<p class="date"><?php echo $text['test_date']; ?></p>
					</div>

				
						</br><input name="edit_no_message" size="69" type="text" value="<?php echo $text['no_message']; ?>">
	
			<?php elseif ($_GET['template'] == 'home'): ?>

				<input class="edit_head" name="edit_users" size="69" type="text" value="<?php echo $text['users']; ?>">

					<?php 
						if (isset($_SESSION['id']) && $_SESSION['role'] == 3):
						$db_data = get_users();
						foreach ($db_data as $key):
					?>	
						<div method="POST" action="<?php echo $link; ?>lg=<?php echo $_GET['lg']; ?>" class="users">		
							<img src="<?php echo $key['photo']; ?>" class="users"></img>	
							<a href="" class="head"><?php echo $key['login']; ?></a>
							<button class="mess_b" name="delete_user" value="<?php echo $key['id']; ?>" type="submit">x</button>	
							<button class="mess_b" name="edit_user" value="<?php echo $key['id']; ?>" type="submit" >!</button>
						</div>		
					<?php
						endforeach;
						elseif (!isset($_SESSION['id']) || isset($_SESSION['id'])):
						$db_data = get_users();
						foreach ($db_data as $key):
					?>	
						<div class="users">		
							<img src="<?php echo $key['photo']; ?>" class="users"></img>	
							<a href="" class="head"><?php echo $key['login']; ?></a>	
						</div>	
					<?php
						endforeach;
						endif;
					?>

			<?php elseif ($_GET['template'] == 'tools'): ?>
				<input class="edit_head" name="edit_tools_user" size="69" type="text" value="<?php echo $text['tools_user']; ?>">
				
				<div class="mess">				
					<table class="tool">
						<tr>
							<td><input class="edit_blue" name="edit_ch_ava" type="text" value="<?php echo $text['ch_ava']; ?>"></td>
							<td><input name="userfile" type="file"></td>
						</tr>	
						<tr>
							<td></td>
							<td><input class="edit_button_t" name="edit_save" type="text" value="<?php echo $text['save']; ?>"></td>
						</tr>
					</table>
				</div>

				<div class="mess">
					<table class="tool">
						<tr>
							<td><input class="edit_blue" name="edit_name" type="text" value="<?php echo $text['name']; ?>"></td>
							<td><input type="text" size="30" name="t_name" ></td>
						</tr>
						<tr>
							<td><input class="edit_blue" name="edit_surname" type="text" value="<?php echo $text['surname']; ?>"></td>
							<td><input type="text" size="30" name="t_surname" ></td>
						</tr>	
						<tr>
							<td></td>
							<td><input class="edit_button_t" name="edit_save" type="text" value="<?php echo $text['save']; ?>"></td>
						</tr>
					</table>
				</div>

				<div class="mess">
					<table class="tool">	
						<tr>
							<td><input class="edit_blue" name="edit_email" type="text" value="<?php echo $text['email']; ?>"></td>
							<td><input type="text" size="30" name="t_email"></td>
						</tr>						
						<tr>
							<td><input class="edit_blue" name="edit_pass" type="text" value="<?php echo $text['pass']; ?>"></td>
							<td><input type="password" size="30" name="t_epass" ></td>
						</tr>
						<tr>
							<td>
								<input class="edit_err" name="edit_r_emale_er" type="text" value="<?php echo $text['r_emale_er']; ?>"></br>
								<input class="edit_err" name="edit_er_pass" type="text" value="<?php echo $text['er_pass']; ?>">
							</td>
							<td><input class="edit_button_t" name="edit_save" type="text" value="<?php echo $text['save']; ?>"></td>
						</tr>
					</table>
				</div>

				<div class="mess">
					<table class="tool">
						<tr>
							<td><input class="edit_blue" name="edit_npass" type="text" value="<?php echo $text['npass']; ?>"></td>
							<td><input type="password" size="30" name="t_npass" ></td>
						</tr>							
						<tr>
							<td><input class="edit_blue" name="edit_rpass" type="text" value="<?php echo $text['rpass']; ?>"></td>
							<td><input type="password" size="30" name="t_rpass" ></td>
						</tr>				
							<tr>
								<td><input class="edit_blue" name="edit_pass" type="text" value="<?php echo $text['pass']; ?>"></td>
								<td><input type="password" size="30" name="t_pass" ></td>
							</tr>
						<tr>
							<td>
								<input class="edit_err" name="edit_r_pass_er" type="text" value="<?php echo $text['r_pass_er']; ?>"></br>
								<input class="edit_err" name="edit_er_pass" type="text" value="<?php echo $text['er_pass']; ?>">
							</td>
							<td><input class="edit_button_t" name="edit_save" type="text" value="<?php echo $text['save']; ?>"></td>
						</tr>
					</table>
				</div> 


					<div class="mess">
						<table class="tool">
							<tr>
								<td><input class="edit_blue" name="edit_role_lock" type="text" value="<?php echo $text['role_lock']; ?>"></td>
									<td><input type="radio" name="user_role" value="0"></td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_role_user" type="text" value="<?php echo $text['role_user']; ?>"></td>
									<td><input type="radio" name="user_role" value="1"></td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_role_editor" type="text" value="<?php echo $text['role_editor']; ?>"></td>
									<td><input type="radio" name="user_role" value="2"></td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_role_admin" type="text" value="<?php echo $text['role_admin']; ?>"></td>
									<td><input type="radio" name="user_role" value="3" checked="checked"></td>
							</tr>
							<tr>
								<td></td>
								<td><input class="edit_button_t" name="edit_save" type="text" value="<?php echo $text['save']; ?>"></td>
							</tr>
						</table>
					</div>

			<?php elseif ($_GET['template'] == 'register'): ?>
				<div id="register">
						<input class="edit_head" name="edit_register" size="69" type="text" value="<?php echo $text['register']; ?>">
						<table>
							<tr>
								<td><input class="edit_blue" name="edit_login" type="text" value="<?php echo $text['login']; ?>"></td>
								<td> <input type="text" name="reg_login"></td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_email" type="text" value="<?php echo $text['email']; ?>"></td>
								<td> <input type="text" name="reg_email"></td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_pass" type="text" value="<?php echo $text['pass']; ?>"></td>
								<td> <input type="password" name="reg_pass" > </td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_rpass" type="text" value="<?php echo $text['rpass']; ?>"></td>
								<td> <input type="password" name="reg_rpass" > </td>
							</tr>
							<tr>
								<td>
									<input class="edit_err" name="edit_r_pass_er" type="text" value="<?php echo $text['r_pass_er']; ?>">
									<input class="edit_err" name="edit_r_emale_is" type="text" value="<?php echo $text['r_emale_is']; ?>">
									<input class="edit_err" name="edit_r_emale_er" type="text" value="<?php echo $text['r_emale_er']; ?>">
									<input class="edit_err" name="edit_r_access" type="text" value="<?php echo $text['r_access']; ?>">
								</td>
								<td><input class="edit_button_t" name="edit_b_reg" type="text" value="<?php echo $text['b_reg']; ?>"></td>
							</tr>
						</table>
					</div>

					<div id="login">
						<input class="edit_head" name="edit_author" size="69" type="text" value="<?php echo $text['author']; ?>">
						<table>
							<tr>
								<td><input class="edit_blue" name="edit_login" type="text" value="<?php echo $text['login']; ?>"></td>
								<td> <input type="text" name="log_email"> </td>
							</tr>
							<tr>
								<td><input class="edit_blue" name="edit_pass" type="text" value="<?php echo $text['pass']; ?>"></td>
								<td> <input type="password" name="log_pass" > </td>
							</tr>
							<tr>
								<td>
									<input class="edit_err" name="edit_l_ban" type="text" value="<?php echo $text['l_ban']; ?>">
									<input class="edit_err" name="edit_l_log_er" type="text" value="<?php echo $text['l_log_er']; ?>">
								</td>
								<td><input class="edit_button_t" name="edit_b_author" type="text" value="<?php echo $text['b_author']; ?>"></td>
							</tr>
						</table>
					</div>
				<?php elseif ($_GET['template'] == 'edit'): ?>
					<img src="images/users/none.jpg" class="edit_mess"></img> 
					<div id="edit">     
					<input class="edit_head" name="edit_edit_mess" size="69" type="text" value="<?php echo $text['edit_mess']; ?>">
					<a href="" class="edit_capt"><?php echo $text['login']; ?></a>     <br/><br/>
					<input class="edit_blue_e" name="edit_ukraine" type="text" value="<?php echo $text['ukraine']; ?>">
					<textarea class="edit_text" name="send_capt" rows="1" cols="54" ><?php echo strip_tags($text['capt']); ?></textarea>
					<textarea class="edit_text" name="send_mess" rows="15" cols="54" ><?php echo strip_tags($text['test_message']); ?></textarea>	
					<input class="edit_blue_e" name="edit_english" type="text" value="<?php echo $text['english']; ?>">						
					<textarea class="edit_text" name="send_capt_en" rows="1" cols="54" ><?php echo strip_tags($text['capt']); ?></textarea>
					<textarea class="edit_text" name="send_mess_en" rows="15" cols="54" ><?php echo strip_tags($text['test_message']); ?></textarea>							
					<p class="date"><?php echo $text['test_date']; ?></p> 
					<input class="edit_button" name="edit_edit" type="text" value="<?php echo $text['edit']; ?>">
				</div>
				<?php elseif ($_GET['template'] == 'message'): ?>
					<div id="edit">   
						<img src="images/users/none.jpg" class="edit_mess"></img> 
						<input class="edit_head" name="edit_more" size="69" type="text" value="<?php echo $text['more']; ?>">				
						<button class="mess_b" type="submit" name="mess_ok" value="<?php echo $message->id; ?>">x</button>
						<button class="mess_b" type="submit" name="mess_ed" value="<?php echo $message->id; ?>">!</button>
						<a href="" class="edit_capt"><?php echo $text['login']; ?></a>     <br/><br/>
							<p class="head"><?php echo $text['capt']; ?></p>     
							<p class="mess"><?php echo $text['test_message']; ?></p>   
						<p class="date"><?php echo $text['test_date']; ?></p>
					</div>
				<?php endif; ?>
			
			
			</div>

			<div id="ank"></div>

		</form>

	</body>

</html>