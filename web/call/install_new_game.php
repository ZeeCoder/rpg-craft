<?php
	session_start();
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();

	$handle = fopen("../charsheet_xml/installed_games_list_".$sess_user_id.".ini", "a");
	echo (fwrite($handle, "\r\n".$_REQUEST["content"]) > 0) ? 1:0;
	fclose($handle);
?>