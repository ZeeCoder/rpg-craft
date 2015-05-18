<?php
	/* Saved session. */
	$s = $sess_saved_reg_data;
?>

<h2 id="register"><?php echo $l_config["registration_title"]; ?></h2>
<div class="register" id="registerBox">
	<form id="registerForm" method="post" class="form" action="<?php echo PAGE_ROOT; ?>call/register.php">
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_email"]; ?></div>
			<input name="registerMail" id="registerMail" type="text" class="input round5" maxlength="30" value="<?php echo $s['mail']; ?>" />
		</div>
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_nick"]; ?></div>
			<input name="registerName" id="registerName" type="text" class="input round5" maxlength="20" value="<?php echo $s['nick']; ?>" />
		</div>
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_born"]; ?></div>
			<div id="registerBornWrapper"><input name="registerBorn" id="registerBorn" type="text" class="input round5" readonly="readonly" value="<?php echo $s['born']; ?>" /></div>
		</div>
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_gender"]; ?></div>
			<input name="registerGender" id="registerGender" type="radio" value="1" <?php echo (!isset($sess_saved_reg_data) || $s['gender']===1)?' checked="checked"':''; ?> /> <?php echo $l_config["input_label_gender_man"]; ?>
			<input name="registerGender" id="registerGender2" type="radio" value="0" <?php echo ($s['gender']===0)?' checked="checked"':''; ?>" /> <?php echo $l_config["input_label_gender_women"]; ?>
		</div>
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_pass"]; ?></div>
			<input name="registerPass" id="registerPass" type="password" class="input round5" />
		</div>
		<div class="rowContainer">
			<div class="label"><?php echo $l_config["input_label_pass_confirm"]; ?></div>
			<input name="registerPassConfirm" id="registerPassConfirm" type="password" class="input round5" />
		</div>
		<div class="rowContainer rowSubmit"><input name="submitRegisterForm" type="submit" value="<?php echo $l_config["input_submit_register"]; ?>" /></div>
	</form>
</div>