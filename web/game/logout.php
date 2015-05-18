<?php
	/* !!Deprecated, not needed anymore!! */

	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
	session_write_close();
	
	include("../mysql_handler.php");
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id)  ))
			throw new Exception("1");
		
		$query = "UPDATE `games` SET `active` = '0' WHERE `gamesID` = '".$sess_game_id."'";
		$db = new MysqlHandler();
		$db->start();
			if (!@mysql_query($query)) throw new Exception("2");
		$db->stop();
	
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>