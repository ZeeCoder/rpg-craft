<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];
		if (isset($_SESSION['user_name']))
			$sess_user_name = $_SESSION['user_name'];
		if (isset($_SESSION['user_is_spec']))
			$sess_user_is_spec = $_SESSION['user_is_spec'];
	session_write_close();
	
	include_once("../custom_log.php");
	
	$user_log = new custom_log(3);
	$user_log->message("microtime (update_normal): ".microtime(true));
	$start = microtime(true);
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id) && isset($sess_user_id) && isset($sess_user_name)  ))
			throw new Exception("1");
		
		/*Aktuális játékos frissítése*/
		$handler = fopen('game_folders/'.$sess_game_id."/user_".$sess_user_id.".txt", "w");
		fwrite($handler, $sess_user_id.";".microtime(true).";".$sess_user_name.";;".((isset($sess_user_is_spec))?$sess_user_is_spec:""));
		fclose($handler);
		
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	$user_log->message("microtime (update_normal) Length: ".(microtime(true) - $start)." End: ".microtime(true).")");
?>