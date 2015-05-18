<?php
	session_start();
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];
	session_write_close();
	
	include("../mysql_handler.php");
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!( isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		if (!( isset($_POST['entry_id'])  ))
			throw new Exception("2");
		
		$folder_arr = glob('game_folders/'.$sess_game_id."/user_*.txt");
		$in_search = "";
		$first = true;
		if (!is_array($folder_arr)) throw new Exception("3");
		foreach ($folder_arr as $filename) {
			if (basename($filename, ".txt")==("user_".$sess_user_id)) continue;
			$in_search .= (($first)?"":",").str_replace("user_", "", basename($filename, ".txt"));
			$first = false;
		}
		$db = new MysqlHandler();
		$db->start();
			$query = "SELECT * FROM users WHERE usersID IN(".$in_search.")";
			$result = @mysql_query($query);
			if ($result==false) throw new Exception("4");
			
			if ($_POST["entry_id"]!="-1") {
				$file = @fopen('game_folders/'.$sess_game_id."/".$_POST['entry_id']."/allowed.txt", "a+");
				$line = @fgets($file);
				if ($line==";") echo "<span id='privacy_group'>none</span>";
				else if ($line=="") echo "<span id='privacy_group'>all</span>";
				else echo "<span id='privacy_group'>other</span>";
				@fclose($file);
			}
			while($row=@mysql_fetch_assoc($result)){
				$checked=false;
				if ($_POST["entry_id"]!="-1") {
					if (strpos($line,$row["usersID"])!==false) $checked=true;
					else $checked=false;
				}
				echo "<input type='checkbox'".(($checked)?" checked='checked'":"")." class='allowed' name='allowed[]' value='".$row["usersID"]."' /><div>".$row["nick"]."</div>";
			}
		$db->stop();
	} catch (Exception $e) {
		if ( $e->getMessage()==4 ) {
			include_once( '../config_loader.php' );
			
			$l_config = config_loader( array(
				'../langs/l_game'
			) );
			
			echo $l_config['entry_share_no_participants'];
		}
	}
?>