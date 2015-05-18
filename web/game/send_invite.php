<?php
	session_start();
		$sess_game_id = $_SESSION['game_id'];
		$sess_user_id = $_SESSION['user_id'];
	session_write_close();
	
	include_once("../mysql_handler.php");
	try {
		if (!(  isset($sess_user_id) && isset($_POST["id"]) && isset($sess_game_id) ))
			throw new Exception("1");
			
		$db = new MysqlHandler();
		$db->start();
			$query = "INSERT INTO `usersmessages` (`toID`, `fromID`, `gameRequestID`) VALUES ('".$_POST["id"]."', '".$sess_user_id."', '".$sess_game_id."')";
			if (!mysql_query($query)) throw new Exception("2");
		$db->stop();
		
		echo 0;
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>