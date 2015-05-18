<?php
	$config = parse_ini_file("supported_games_list.ini", true);
	foreach ($config as $key => $game) {
		$game_more = explode("-", $game);
		echo $key.": ".$game_more[0]." - ".$game_more[1];
	}
?>