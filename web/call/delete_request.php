<?php
	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$id = $db->clean($_REQUEST["id"]);
		$query = "DELETE FROM `usersmessages` WHERE `usersMessagesID` = '$id'";
		echo mysql_query($query);
	$db->stop();
?>