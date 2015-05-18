<?php
	session_start();
		if (!isset($_SESSION["lang"])) {
			if (!isset($_COOKIE["lang"])) {
				$_SESSION["lang"] = $l_supported[0];
				$_COOKIE["lang"] = $l_supported[0];
			} else
				$_SESSION["lang"] = $_COOKIE["lang"];
		}
		$sess_lang = $_SESSION["lang"];
	session_write_close();

	include_once("../mysql_handler.php");
	
	$l_supported = parse_ini_file("../supported_langs.ini", true);
	$l_supported = explode(",", $l_supported["supported_langs"]);
	
	
	$l_config_src = "../langs/l_main_".$sess_lang.".ini";
	$l_def_config_src = "../langs/l_main_".$l_supported[0].".ini";
	$l_config = parse_ini_file($l_config_src, true);
	$l_def_config = parse_ini_file($l_def_config_src, true);
	$l_config = array_merge($l_def_config, $l_config);
	
	$db = new MysqlHandler();
	$db->start();
		
		$keyWord = $db->clean($_POST["keyWord"]);
		
		$query = "SELECT * FROM users WHERE nick LIKE '%".$keyWord."%'";
		
		$table = mysql_query($query);
		if (mysql_num_rows($table) == 0) {
			echo "<div class=\"foreverAlone round5\">".$l_config["friends_search_empty"]."</div>";
		} else {
			while ($row = mysql_fetch_assoc($table)) {
				echo "<div class=\"listedFriend round5\">";
					echo "<div class=\"nick\">".$row["nick"]."</div>";
					// echo "<div class=\"message button\">".$l_config["friends_send_message"]."</div>";
					echo "<div class=\"friend_request button\" onclick=\"send_friend_request('".$row["usersID"]."')\">".$l_config["friends_send_request"]."</div>";
				echo "</div>";
			}
		}
	$db->stop();
?>