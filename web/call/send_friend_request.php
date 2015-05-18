<?php
	session_start();
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();
	
	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$user_id = $db->clean($_REQUEST["user_id"]);
		$query = "SELECT COUNT(*) AS num FROM usersmessages WHERE fromID = '".$sess_user_id."' AND toID = '$user_id'";
		$result = mysql_fetch_assoc(mysql_query($query));
		if ($result["num"] > 0) {
			echo "2";
		} else {
			$query = "SELECT COUNT(*) AS num FROM friendconnections WHERE (initiaterID = '".$sess_user_id."' AND accepterID = '$user_id') OR (accepterID = '".$sess_user_id."' AND initiaterID = '$user_id')";
			$result = mysql_fetch_assoc(mysql_query($query));
			if ($result["num"] > 0) {
				echo "3";
			} else {
				$query = "INSERT INTO usersmessages (fromID, toID, title, message, date, friendRequestID) VALUES('".$sess_user_id."', '$user_id', '-', '-', NOW(), '".$sess_user_id."')";
				echo mysql_query($query) or die(mysql_error());
			}
		}
	$db->stop();
?>