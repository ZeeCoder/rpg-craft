<?php
	// session_start();
	include("../mysql_handler.php");
	$hiba = 0;
	if (isset($_POST)) {
		$query = "DELETE FROM `".$_POST["dbname"]."` ";
		$query .= "WHERE `specID` = '".$_POST["specID"]."'";
		
		$db = new MysqlHandler();
		$db->start();
			if (!mysql_query($query)) $hiba = 2;
		$db->stop();
	} else {
		$hiba = 1; //Hiányzó post tömbb
	}
	echo $hiba;
?>