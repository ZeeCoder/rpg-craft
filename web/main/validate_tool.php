<?php
	
	/*PHP nyelvi fájl betöltése*/
	require_once("../config_loader.php");
	$l = config_loader("../langs/l_checker");
	
	session_start();
		if (isset($_SESSION["user_id"]))
			$sess_user_id = $_SESSION["user_id"];
		$sess_user_rights = $_SESSION["user_rights"];
		$sess_lang = $_SESSION["lang"];
	session_write_close();
	
	/*JS támogatott nyelv betöltése*/
	$js_lang = get_available_js_lang("l_checker");
	
	/*Ha nem regisztrált user*/
	if (!isset($sess_user_id)) {echo $l["not_authorized"]; exit();}
	/*Ideiglenes karakterlap név.*/
	$valname = "validate_".$sess_user_id;
	
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $l["title"]; ?></title>
    <link rel="stylesheet" type="text/css" href="../plugins/hv_ajax_loader/hv_ajax_loader.css" />
    <link rel="stylesheet" type="text/css" href="checker.css" />
	<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
</head>
<body>
<div id="validation_upload_form">
	<h3><?php echo $l["title"]; ?></h3>
	<div class="desc"><?php echo $l["desc"]; ?></div>
	<form id="upload_xml" method="post" action="validate_upload.php" enctype="multipart/form-data" target="val_tool_upload">
		<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
		<input type="hidden" id="game_type" name="game_type" value="<?php echo $valname; ?>" />
		<?php echo $l["xml"]; ?><input id="xml_to_check" type="file" name="xml_to_check" /><br/>
		<?php echo $l["conf_ini"]; ?><input id="conf_ini" type="file" name="conf_ini" /><br/>
		<?php echo $l["lang_ini"]; ?><input id="lang_ini_to_check" type="file" name="lang_ini_to_check" />
		<?php echo $l["or_download_lang"]; ?>
		<input id="generate_ini" type="checkbox" name="generate_ini" value="true" />
		<span id="ini_download_link"><?php echo $l["ini_download_link"]; ?></span>
		<br/>
		<br/>
		<?php
			if ($sess_user_rights==2) {
				echo "----------".$l["install_mode"]."----------<br/>";
				echo $l["install_short_name"];
				echo "<input type='text' name='install_game_name' id='install_game_name' />";
				echo $l["install_full_name"];
				echo "<input type='text' name='install_game_full_name' id='install_game_full_name' /><br/>".$l["install_mode"];
				echo "<input id='install_mode' type='checkbox' name='install_mode' value='D0n0Tv4L1d4T3' />".$l["forced_mode"];
				echo "<input id='forced_install' type='checkbox' name='forced_install' value='D0n0Tv4L1d4T3' /><br/><br/>";
			}
		?>
		<input type="submit" value="<?php echo $l["submit"]; ?>" />
	</form>
	<iframe id="val_tool_upload" name="val_tool_upload" src=""></iframe>
	<div id="pass_validated"></div>
	<div id="language"><?php echo $sess_lang; ?></div>
</div>

<script type="text/javascript" src="l_checker_<?php echo $js_lang; ?>.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="../plugins/hv_ajax_loader/hv_ajax_loader.js"></script>
<script type="text/javascript" src="checker.js"></script>

</body>
</html>