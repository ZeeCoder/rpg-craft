<?php
	session_start();
		$query = "
			(SELECT usersMessagesID, toID, fromID, title, message, gameRequestID, friendRequestID, date FROM usersmessages WHERE gameRequestID IS NOT NULL AND seen = '0' AND toID = '".$_SESSION["user_id"]."' ORDER BY usersMessagesID)
			UNION
			(SELECT usersMessagesID, toID, fromID, title, message, gameRequestID, friendRequestID, date FROM usersmessages WHERE friendRequestID IS NOT NULL AND seen = '0' AND toID = '".$_SESSION["user_id"]."')
		";
	session_write_close();
	include_once("../mysql_handler.php");
	$db = new MysqlHandler();
	$db->start();
	
		$result = mysql_query($query);
		if (mysql_num_rows($result)>0) {
			header("Content-type: application/json");
			$response = array();
			$requests = array();
			while ($row = mysql_fetch_assoc($result)) {
				$usersMessagesID = $row['usersMessagesID'];
				$toID = $row['toID'];
				$fromID = $row['fromID'];
				$title = $row['title'];
				$message = $row['message'];
				$gameRequestID = $row['gameRequestID'];
				$friendRequestID = $row['friendRequestID'];
				
				$requests[] = array('spec_id' => $usersMessagesID, 'toID' => $toID, 'fromID' => $fromID, 'title' => $title, 'message' => $message, 'gameRequestID' => $gameRequestID, 'friendRequestID' => $friendRequestID);
			}
			
			$response['requests'] = $requests;
			echo json_encode($response);
		} else {
			echo "0";
		}
	$db->stop();
	
?>