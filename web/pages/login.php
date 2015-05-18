<h2 id="login"><?php echo $l_config["login_title"]; ?></h2>
<div class="login" id="loginBox">

	<form id="loginForm" method="post" class="form" action="<?php echo PAGE_ROOT; ?>call/login.php">
		<div id="loginMenu1" class="loginLeftSide">
			<div class="rowContainer">
				<div class="label"><?php echo $l_config["input_label_email"]; ?></div>
				<input name="loginMail" id="loginMail" type="text" class="input round5" maxlength="30" />
			</div>
			<div class="rowContainer">
				<div class="label"><?php echo $l_config["input_label_pass"]; ?></div>
				<input name="loginPass" id="loginPass" type="password" class="input round5" maxlength="20" />
			</div>
			<div class="rowContainer rowSubmit"><input name="submitLoginForm" type="submit" value="<?php echo $l_config["input_submit_login"]; ?>" /></div>
		</div>
	</form>
	
	<div id="loginMenu2" class="loginLeftSide">
		<form id="forgottenForm" method="post" class="form" action="<?php echo PAGE_ROOT; ?>call/forgottenPass.php">
			<div class="rowContainer">
				<div class="label"><?php echo $l_config["input_label_email"]; ?></div>
				<input name="forgottenMail" id="forgottenMail" type="text" class="input round5" maxlength="30" />
			</div>
			<div class="rowContainer rowSubmit"><input name="submitForgottenForm" type="submit" value="<?php echo $l_config["require_new_pass_submit"]; ?>" /></div>
		</form>
	</div>
	
	<div id="loginMenu3" class="loginLeftSide">
		<form id="activationForm" method="post" class="form" action="<?php echo PAGE_ROOT; ?>call/activationResend.php">
			<div class="rowContainer">
				<div class="label"><?php echo $l_config["input_label_email"]; ?></div>
				<input name="activationMail" id="activationMail" type="text" class="input round5" maxlength="30" />
			</div>
			<div class="rowContainer rowSubmit"><input name="submitActivateForm" type="submit" value="<?php echo $l_config["resend_activation_link"]; ?>" /></div>
		</form>
	</div>
	
	<div class="loginRightSide">
		<div class="menu" onclick="loginMenu(1);"><?php echo $l_config["sidemenu_login"]; ?></div>
		<div class="menu" onclick="loginMenu(2);"><?php echo $l_config["sidemenu_forgotten"]; ?></div>
		<div class="menu" onclick="loginMenu(3);"><?php echo $l_config["sidemenu_activation"]; ?></div>
	</div>
	
</div>
