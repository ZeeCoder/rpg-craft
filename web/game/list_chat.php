<?php
	session_start();
		$sess_game_id = $_SESSION["game_id"];
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();

	include("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$query = "SELECT *, (SELECT `nick` FROM `users` WHERE users.usersID = chat_".$sess_game_id.".user_id) AS `user_name` FROM `chat_".$sess_game_id."`";
		$result = mysql_query($query);
		if (mysql_num_rows($result)>0) {
			header("Content-type: application/json");
			$response = array();
			$posts = array();
			while ($row = @mysql_fetch_assoc($result)) {
				$private = $row["private"];
				if ($private==""||strpos($private, $sess_user_id)) {
					$posts[] = array('chat_id' => $row["chat_id"], 'user_id' => $row["user_id"], 'user_name' => $row["user_name"], 'message' => $row["message"], 'date' => $row["date"]);
				}
			} 
			
			$response['posts'] = $posts;
			echo (empty($posts))?0:json_encode($response);
		} else {
			echo "0";
		}
	$db->stop();
?>