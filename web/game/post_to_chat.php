<?php
	session_start();
		$sess_game_id = $_SESSION['game_id'];
		$sess_user_id = $_SESSION['user_id'];
	session_write_close();

	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$message = $db->clean($_POST["message"]);
		$private = $db->clean($_POST["private"]);
		
		$query = "INSERT INTO `chat_".$sess_game_id."` (`user_id`, `message`, `private`, `date`) VALUES ('".$sess_user_id."', '$message', '$private', NOW())";
		echo @mysql_query($query);
		
		$fp = @fopen("../game/game_folders/".$sess_game_id."/last_chat.txt", "w");
			@fwrite($fp, time());
		@fclose($fp);
		
	$db->stop();
?>