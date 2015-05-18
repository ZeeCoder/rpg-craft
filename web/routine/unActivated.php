<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Törli a 3 napnál régebbi, aktiválatlan felhasználókat.
	*/
	include_once("../mysql_handler.php");
	
	$db = new MysqlHandler();
	$db->start();
		$query = "DELETE FROM users WHERE active = '0' AND joined < DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
		mysql_query($query);
	$db->stop();
	/*
	
	$message = "valami!";
	
	$headers .= "From: RPG <rpg@hv-web.hu>\r\n";
	$headers .= "Organization: RPG\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf8\r\n";
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
	
	$subject = "valami";
	
	mail("rpgmorpheus@gmail.com", $subject, $message, $headers, "-frpg@hv-web.hu");
	*/
?>