<?php
	session_start();
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();

	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$user_id = $db->clean($_REQUEST["user_id"]);
		$query = "INSERT INTO friendconnections (initiaterID, accepterID, approved) VALUES('$user_id', '".$sess_user_id."', '1')";
		mysql_query($query) or die(mysql_error());
		$spec_id = $db->clean($_REQUEST["spec_id"]);
		$query = "DELETE FROM usersmessages WHERE usersMessagesID = '$spec_id'";
		echo mysql_query($query) or die(mysql_error());
	$db->stop();
?>