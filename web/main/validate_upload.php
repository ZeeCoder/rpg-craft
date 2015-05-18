<?php
	session_start();
		$sess_lang = $_SESSION["lang"];
	session_write_close();
	
	$game_type = $_POST["game_type"];
	if (!empty($_POST["install_game_name"])) $game_type = $_POST["install_game_name"];
	
	$target = "../charsheet_xml/".$game_type."/";
	if (!is_dir($target)) mkdir($target);
	
		/*XML feltöltése*/
		$tmp_dir = $_FILES["xml_to_check"]["tmp_name"];
		$new_name = $game_type.".xml";
		move_uploaded_file($tmp_dir, $target.$new_name);
		
		/*config INI-fájl feltöltése*/
		if (!empty($_FILES["conf_ini"]["tmp_name"])) {
			$tmp_dir = $_FILES["conf_ini"]["tmp_name"];
			$new_name = "conf_".$game_type.".ini";
			move_uploaded_file($tmp_dir, $target.$new_name);
		}
		
		/*nyelvi INI-fájl feltöltése*/
		if (!empty($_FILES["lang_ini_to_check"]["tmp_name"])) {
			$tmp_dir = $_FILES["lang_ini_to_check"]["tmp_name"];
			$new_name = "lang_".$game_type."_".$sess_lang.".ini";
			move_uploaded_file($tmp_dir, $target.$new_name);
		}
		
	setcookie("ready", 1, time()+60*60*24*30, "/");
?>