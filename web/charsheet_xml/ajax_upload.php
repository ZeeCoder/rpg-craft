<?php
	// session_start();
	include("../mysql_handler.php");
	$hiba = 0;
	if (isset($_POST)) {
		$first = true;
		$query = "UPDATE `".$_POST["dbname"]."` SET ";
		foreach (array_keys($_POST) as $key) {
			if ($key != "dbname" && $key != "specID" && $key != "usersID" && substr($key, 0, 3) != "vis") {
				if ($first) $first = false;
				else $query .= ", ";
				if (substr($key, 0, 4) == "stat") $visible = isset($_POST["vis_".substr($key, 5)]);
				else $visible = isset($_POST["vis_".$key]);
				
				if (substr($key, 0, 4) != "stat") $$key = $_POST[$key];
				
				if (substr($key, 0, 4) == "stat") $query .= "`vis_".substr($key, 5)."` = '$visible' ";
				else $query .= "`".$key."` = '${$key}', `vis_".$key."` = '$visible' ";
			}
		}
		$query .= "WHERE `specID` = '".$_POST["specID"]."'";
		
		$db = new MysqlHandler();
		$db->start();

			ini_set("error_log", "asd.log");
			error_log($query);
			
			if (!mysql_query($query)) $hiba = 2;
		$db->stop();
	} else {
		$hiba = 1; //Hiányzó post tömbb
	}
	echo $hiba;
?>