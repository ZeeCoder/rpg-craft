<?php
	session_start();
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();
	
	include_once("../mysql_handler.php");
	
	
	try {
		if (!(  isset($_REQUEST["game_id"]) && isset($sess_user_id)  ))
			throw new Exception("1");
			
			$handler = fopen("../game/game_folders/".$_REQUEST["game_id"]."/user_".$sess_user_id.".txt", "w");
			fclose($handler);
			

			$db = new MysqlHandler();
			$db->start();
				$game_id = $db->clean($_REQUEST["game_id"]);
				$query = "DELETE FROM `usersmessages` WHERE `toID` = '".$sess_user_id."' AND `gameRequestID` = '".$game_id."'";
				echo (mysql_query($query))?0:1;
			$db->stop();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>