<?php
	session_start();
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];
	session_write_close();

	
	include("../mysql_handler.php");
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id) && isset($sess_user_id)  ))
			throw new Exception("1");
		
		$db = new MysqlHandler();
		$db->start();
			$query = "SELECT * FROM `events` WHERE `gameID` = '".$sess_game_id."'";
			$result = @mysql_query($query);
			if ($result==false) throw new Exception("2");
			if (@mysql_num_rows($result)>0) {
				header("Content-type: application/json");
				$response = array();
				$posts = array();
				while ($row = @mysql_fetch_assoc($result)) {
					$file = @fopen('game_folders/'.$sess_game_id."/".$row['eventID']."/allowed.txt", "a+");
					$line = @fgets($file);
					$allowed = explode(";", $line);
					if ($line == "" || ($sess_act_user_is_gm) || in_array($sess_user_id, $allowed)) {
						$eventID = $row['eventID'];
						$label = $row['label'];
						$description = $row['description'];
						$last_updated = strtotime($row['last_updated']);
						
						$posts[] = array('eventID' => $eventID, 'label' => $label, 'description' => $description, 'last_updated' => $last_updated);
					}
					@fclose($file);
				} 
				
				$response['posts'] = $posts;
				echo (empty($posts))?0:json_encode($response);
			} else {
				echo "0";
			}
		$db->stop();
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>