<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
	session_write_close();
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id)  ))
			throw new Exception("0");
		
		$fp = @fopen('game_folders/'.$sess_game_id."/last_map.txt", "r");
		if ($fp===false) throw new Exception("0");
		if((($buffer = @fgets($fp, 4096)) === false)) throw new Exception("0");
		echo $buffer;
		fclose($fp);
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>