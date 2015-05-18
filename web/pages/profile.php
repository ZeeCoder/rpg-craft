<h2><?php echo $l_config["options_title"]; ?></h2>
<div class="settings_tab" style="float: none; display: block; width: auto;">
	<h3><?php echo $l_config["profile_title"]; ?></h3>
    
    <div class="register" id="registerBox">
        <form id="registerForm" method="post" class="form" action="">
            <div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_nick"]; ?></div>
                <input name="registerName" id="registerName" type="text" class="input round5" value="<?php echo $sess_user_name; ?>" maxlength="20" disabled="disabled" />
            </div>
            <div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_email"]; ?></div>
                <input name="registerMail" id="registerMail" type="text" class="input round5" value="<?php echo $sess_user_mail; ?>" maxlength="30" />
            </div><div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_born"]; ?></div>
                <div id="registerBornWrapper"><input name="registerBorn" id="registerBorn" type="text" value="<?php echo $sess_user_born; ?>" class="input round5" readonly="readonly" /></div>
            </div>
            <div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_gender"]; ?></div>
                <input name="registerGender" id="registerGender" type="radio" <?php echo (($sess_user_gender==1)?"checked='checked' ":""); ?>value="1" /> <?php echo $l_config["input_label_gender_man"]; ?>
                <input name="registerGender" id="registerGender2" type="radio" <?php echo (($sess_user_gender==0)?"checked='checked' ":""); ?>value="0" /> <?php echo $l_config["input_label_gender_women"]; ?>
            </div>
            <div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_pass"]; ?></div>
                <input name="registerPass" id="registerPass" type="password" value="" class="input round5" />
            </div>
            <div class="rowContainer">
                <div class="label"><?php echo $l_config["input_label_pass_confirm"]; ?></div>
                <input name="registerPassConfirm" id="registerPassConfirm" type="password" value="" class="input round5" />
            </div>
            <div class="rowContainer rowSubmit"><input name="submitRegisterForm" type="submit" value="<?php echo $l_config["update_profile_sub"]; ?>" /></div>
        </form>
    </div>
    
</div><!-- 
<div class="settings_tab">
	<h3><?php echo $l_config["questions_title"]; ?></h3>
</div> -->
<br clear="all" />