
<div class="container">
	<div class="main_remove_button"></div>
	<div id="intro">
		<?php if(!isset($_SESSION['user_id'])): ?>
		<a href="../More/" class="main_learn_more" ><img src="images/learnmore.png" width="207" height="29" alt="Learnmore" border="0"></a>
		<?php endif ?>
		
		<img src="images/mission.png" width="900" height="83" alt="Mission">
		
	</div>
	
	<?php if(!isset($_SESSION['user_id'])): ?>
	<div id="left">
		<img src="images/guitar_example.png" width="506" height="367" alt="Guitar Example">
	</div>
	<div id="right">
		<div id="login" style="display:{$login};">
			<div class="login_notice" style="display:{$login_status};">Username or Password is incorrect!</div>
			<form method="post" action="?action=login">
				<img src="images/login_here.png" width="203" height="38" alt="Login Here">
				<table border = "0" cellspacing="20">
					<tr>
						<td><img src="images/email_lbl.png" class = "main_label" width="86" height="25" alt="Email Lbl"></td>
						<td><input class="login_email_input" name="email" type="text" /></td>
					</tr>
					<tr>
						<td><img src="images/password_lbl.png" class = "main_label"  width="156" height="25" alt="Password Lbl"></td>
						<td><input class="login_pass_input" name="password" type="password" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value = " " class="main_login_button" onfocus="blur()"/></td>
					</tr>
				</table>
			</form>
		</div>
		
		<div id="signup" style="display:{$signup};">
			<img src="images/signup_here.png" width="230" height="39" alt="Signup Here">
			<form method="post" action = "?action=signup" onsubmit="return false;">
				<table border = "0" cellspacing="10">
					<tr>
						<td><img src="images/name_lbl.png" class = "main_label"  width="98" height="25" alt="Name Lbl"></td>
						<td><input class = "signup_name_input" name="name" type="text" /></td>
					</tr>
					<tr>
						<td colspan = "2" class="signup_name_check">* Please enter a name</td>
					</tr>
					<tr>
						<td><img src="images/email_lbl.png" class = "main_label" width="86" height="25" alt="Email Lbl"></td>
						<td><input class = "signup_email_input" name="email" type="text" /></td>
					</tr>
					<tr>
						<td colspan = "2" class="signup_email_check">* Please enter a valid email</td>
					</tr>
					<tr>
						<td><img src="images/password_lbl.png" class = "main_label" width="156" height="25" alt="Password Lbl"></td>
						<td><input class = "signup_pass_input" name="password" type="password" /></td>
					</tr>
					<tr>
						<td colspan = "2" class="signup_pass_check">* Your password is too short!</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value = "" class="main_signup_button" onfocus="blur()"/></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php else: ?>
	<div class="main_box">
		<div class="main_search_big_button"></div>
		<div class="main_help_big_button"></div>
		<div class="main_create_big_button"></div>
		
	</div>
	
	<?php endif ?>
</div>