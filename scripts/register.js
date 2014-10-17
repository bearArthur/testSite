var regular = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			
						
			function emailcheck() {
				if (document.all.item('reg_email').value) {
					if (!document.all.item('reg_email').value.match(regular)) {
						document.all.item('email_err').innerHTML = "Неправильний формат пошти";
						check1 = false;
					}
					else {
						document.all.item('email_err').innerHTML = "";
						check1 = true;
					}
				}
				else {
					document.all.item('email_err').innerHTML = "*";
					check1 = false;
				}
			}

			function logincheck() {
				if (document.all.item('reg_login').value == '') {
					document.all.item('login_err').innerHTML = "*";
					check2 = false;
				}
				else {
					document.all.item('login_err').innerHTML = "";
					check2 = true;
				}
			}

			function passcheck() {
				if (document.all.item('reg_pass').value) {
					if (document.all.item('reg_pass').value.length < 5) {
						document.all.item('pass_err').innerHTML = "Пароль не може бути менший 5 символів";
						check3 = false;
						pass = document.all.item('reg_pass').value;
					}
					else {
						document.all.item('pass_err').innerHTML = "";
						pass = document.all.item('reg_pass').value;
						check3 = true;
					}
				}
				else {
					document.all.item('pass_err').innerHTML = "*";
					check3 = false;
				}
			}

			function rpasscheck() {
				if (document.all.item('reg_rpass').value) {
					if (document.all.item('reg_rpass').value != pass) {
						document.all.item('rpass_err').innerHTML = "Паролі не співпадають";
						check4 = false;
					}
					else {
						document.all.item('rpass_err').innerHTML = "";
						check4 = true;
					}
				}
				else {
					document.all.item('rpass_err').innerHTML = "*";
					check4 = false;
				}
			}

			function buttoncheck() {
					if ((check1 == false) || (check2 == false) || (check3 == false) || (check4 == false)) {
						document.all.item('reg_ok').disabled = true;
					}
					else {
						document.all.item('reg_ok').disabled = false;
					}
			}

			function submitcheck() {
				if ((document.all.item('log_email').value) && (document.all.item('log_pass').value)) {
					document.all.item('log_ok').disabled = false;
				}
				else {
					document.all.item('log_ok').disabled = true;
				}
			}