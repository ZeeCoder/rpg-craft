<?php
	session_start();
			$sess_user_id = $_SESSION['user_id'];
	session_write_close();
	
	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$user_id = $db->clean($_REQUEST["user_id"]);
		$query = "DELETE FROM `friendconnections` WHERE (`initiaterID` = '".$sess_user_id."' AND `accepterID` = '$user_id') OR (`accepterID` = '".$sess_user_id."' AND `initiaterID` = '$user_id')";
		echo mysql_query($query) or die(mysql_error());
	$db->stop();
?>