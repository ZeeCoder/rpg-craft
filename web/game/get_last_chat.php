<?php
	session_start();
		$sess_game_id = $_SESSION["game_id"];
	session_write_close();

	
	$fp = @fopen("../game/game_folders/".$sess_game_id."/last_chat.txt", "r");
	if ($fp!==false) {
		if((($buffer = @fgets($fp, 4096)) !== false)) {
			echo $buffer;
		} else echo 0;
		fclose($fp);
	} else echo 0;
?>