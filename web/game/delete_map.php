<?php
	session_start();
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
	session_write_close();

	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		if (!isset($_POST["name"]))
			throw new Exception("2");
		
		/*Térkép törlése*/
		unlink('game_folders/'.$sess_game_id."/".$_POST["name"]);
		unlink('game_folders/'.$sess_game_id."/tumb_".$_POST["name"]);
		
		/*Jelzés a törlésről a kliensek felé.*/
		$fp = @fopen('game_folders/'.$sess_game_id."/last_map.txt", "w");
			@fwrite($fp, time());
		@fclose($fp);
		
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>