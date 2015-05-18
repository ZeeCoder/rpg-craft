<?php
	session_start();
		if (isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];
	session_write_close();

	include_once("../mysql_handler.php");

	function create_chat_room() {
	}
	
	try {
		if (!(  isset($sess_user_id) && isset($_POST["type"]) && isset($_POST["title"]) && isset($_POST["description"])  ))
			throw new Exception("-1");
	
		$db = new MysqlHandler();
		$db->start();
		
			/*Játék elkészítése*/
				session_start();
					$_SESSION["gameStarted"] = "on";
				session_write_close();

			$type = $db->clean($_POST["type"]);
			$title = $db->clean($_POST["title"]);
			$description = $db->clean($_POST["description"]);
			
			$query = "INSERT INTO `games` (`usersID`, `title`, `description`, `type`, `started`) VALUES ('".$sess_user_id."', '$title', '$description', '$type', CURDATE())";
			if (!@mysql_query($query)) throw new Exception("-2");
			$game_id = mysql_insert_id();
			
			$query = "
			CREATE TABLE `chat_".$game_id."` (
				`chat_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`user_id` INT NOT NULL,
				`message` VARCHAR( 500 ) NOT NULL,
				`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`private` VARCHAR( 50 ) NOT NULL
			)
			";
			if (!@mysql_query($query)) throw new Exception("-3");
			
			$gameFolder = "../game/game_folders/".$game_id;
			if (!is_dir($gameFolder)) mkdir($gameFolder);
			
			
			$handler = fopen($gameFolder."/user_".$sess_user_id.".txt", "w");
			fclose($handler);
			
			$handler = fopen($gameFolder."/gm.txt", "w");
			fwrite($handler, $sess_user_id);
			fclose($handler);
			
			$handler = fopen($gameFolder."/type.txt", "w");
			fwrite($handler, $type);
			fclose($handler);
			
		$db->stop();
		
		echo $game_id;
	
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
?>