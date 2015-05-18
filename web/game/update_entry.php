<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
	session_write_close();
	
	include("../mysql_handler.php");
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		if (!(  isset($_POST["label"]) && isset($_POST["description"]) && isset($_POST["entry_id"]) && isset($_POST["allowed"])  ))
			throw new Exception("2");
			
		/*Bejegyzés frissítése az adatbázisban és a fájlrendszerben*/
		$db = new MysqlHandler();
		$db->start();
			$query = "UPDATE `events` SET `label` = '".$_POST["label"]."', `description` = '".$_POST["description"]."', `last_updated` = NOW() WHERE `eventID` = '".$_POST["entry_id"]."'";
			if (!@mysql_query($query)) throw new Exception("3");
			
			$fp = @fopen('game_folders/'.$sess_game_id."/".$_POST["entry_id"]."/allowed.txt", "w");
				if ($_POST["select_type"]=="all") @fwrite($fp, "");
				else if ($_POST["select_type"]=="none") @fwrite($fp, ";");
				else if ($_POST["select_type"]=="other") @fwrite($fp, $_POST["allowed"]);
			@fclose($fp);
		$db->stop();
		
		/*Jelzés a frissítésről a kliensek felé.*/
		$fp = @fopen('game_folders/'.$sess_game_id."/last_entry.txt", "w");
			@fwrite($fp, microtime(true));
		@fclose($fp);
		
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>