<?php
	session_start();
		$sess_user_id = $_SESSION["user_id"];
	session_write_close();
	
	header("Content-type: application/json");
	$folder_arr = glob("../game/game_folders/[0-9]*/user_".$sess_user_id.".txt");
	if (is_array($folder_arr)&&!empty($folder_arr)) {
		//print_r($folder_arr);
		$ids = array();
		foreach ($folder_arr as $filename) {
			$game_id = explode("/", $filename);
			$ids[] = $game_id[3];
		}
		
		$first = true;
		echo "{";
		foreach ($ids as $game_id) {
			$f = @fopen("../game/game_folders/".$game_id."/gm.txt", "r");
			if((($gm_id = @fgets($f, 4096)) === false)) throw new Exception();
			fclose($f);
			if ($gm_id==$sess_user_id) continue;
			
			$f = @fopen("../game/game_folders/".$game_id."/user_".$gm_id.".txt", "r");
			if((($gm_time = @fgets($f, 4096)) === false)) throw new Exception();
			$gm_time = explode(";", $gm_time); $gm_time = $gm_time[1];
			fclose($f);
			
			$gm_active = (time()-10)<$gm_time;
			echo (($first)?"":",")."\"$game_id\": ".(($gm_active)?"true":"false");
			$first = false;
		}
		echo "}";
	}
	
?>