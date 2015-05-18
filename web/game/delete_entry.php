<?php
	session_start();
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
	session_write_close();

	include("../mysql_handler.php");
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		if (!isset($_POST["eventID"]))
			throw new Exception("2");
			
		/*Bejegyzés törlése az adatbázisból*/
		$db = new MysqlHandler();
		$db->start();
			$query = "DELETE FROM events WHERE eventID = '".$_POST["eventID"]."'";
			
			$folder_arr = glob('game_folders/'.$sess_game_id."/".$_POST["eventID"]."/*");
			if (is_array($folder_arr)) {
				foreach ($folder_arr as $filename) unlink($filename);
			}
			$to_rmdir = 'game_folders/'.$sess_game_id."/".$_POST["eventID"];
			if (is_dir($to_rmdir)) rmdir($to_rmdir);
			
			if (!@mysql_query($query)) throw new Exception("3");
		$db->stop();
		
		/*Jelzés a törlésről a kliensek felé.*/
		$fp = @fopen('game_folders/'.$sess_game_id."/last_entry.txt", "w");
			@fwrite($fp, time());
		@fclose($fp);
		
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>