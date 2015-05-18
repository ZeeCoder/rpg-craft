<?php
	session_start();
		$sess_game_id = $_SESSION["game_id"];
		$sess_watched_user_id = $_SESSION["watched_user_id"];
	session_write_close();
	include("../mysql_handler.php");
	$hiba = 0;
	if (isset($_POST)) {
		$query = "INSERT INTO `".$_POST["dbname"]."`";
		$names = " (`gamesID`, `usersID`";
		$values = " VALUES('".$sess_game_id."', '".$sess_watched_user_id."'";
		$first = true;
		foreach (array_keys($_POST) as $key) {
			if ($key != "dbname" && $key != "specID" && substr($key, 0, 3) != "vis") {
				$visible = isset($_POST["vis_".$key]);
				$names .= ", `".$key."`, `vis_".$key."`";
				$values .= ", '".$_POST[$key]."', '$visible'";
				$first = false;
			}
		}
		$names .= ")";
		$values .= ")";
		$query .= $names.$values;
		
		$db = new MysqlHandler();
		$db->start();
			if (!mysql_query($query)) $hiba = 2;
			else $hiba = mysql_insert_id();
		$db->stop();
	} else {
		$hiba = 1; //Hiányzó post tömbb
	}
	echo $hiba;
?>