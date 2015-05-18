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
			throw new Exception("-2");
		if (!(  isset($_POST["select_type"]) && isset($_POST["title"]) && isset($_POST["desc"])  ))
			throw new Exception("-3");
		
		/*Bejegyzés felvitele az adatbázisba és a fájlrendszerbe*/
		$db = new MysqlHandler();
		$db->start();
			$query = "INSERT INTO `events` (`gameID`, `label`, `description`, `last_updated`) VALUES ('".$sess_game_id."', '".$_POST["title"]."', '".$_POST["desc"]."', NOW())";
			if (!@mysql_query($query)) throw new Exception("-4");
			$inserted = mysql_insert_id();
			echo $inserted;
			
			mkdir('game_folders/'.$sess_game_id."/".$inserted);
			$fp = @fopen('game_folders/'.$sess_game_id."/".$inserted."/allowed.txt", "w");
			if ($_POST["select_type"]=="all") @fwrite($fp, "");
			else if ($_POST["select_type"]=="none") @fwrite($fp, ";");
			else if ($_POST["select_type"]=="other") {
				$ruling = "";
				if(sizeof($_POST["allowed"])) {
					foreach($_POST["allowed"] as $user_id) $ruling .= $user_id.";";
				} else $ruling = ";";
				@fwrite($fp, $ruling);
			};
			@fclose($fp);
		$db->stop();
		
		/*Jelzés a frissítésről a kliensek felé.*/
		$fp = @fopen('game_folders/'.$sess_game_id."/last_entry.txt", "w");
			@fwrite($fp, microtime(true));
		@fclose($fp);
		
		/*Hibamentes visszatérés*/
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>