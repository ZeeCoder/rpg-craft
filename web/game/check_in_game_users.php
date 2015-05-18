<?php
	session_start();
		if (isset($_SESSION["game_id"])) {
			$sess_game_id = $_SESSION["game_id"];
		}
	session_write_close();

	include_once("../custom_log.php");
	
	$user_log = new custom_log(2);
	$user_log->message("TIME: ".time());
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id)  ))
			throw new Exception("1");
		
		/*Játékban lévő játékosok listázása*/
		header("Content-type: application/json");
		$arr = glob('game_folders/'.$sess_game_id."/user_*.txt");
		if (is_array($arr)) {
			$response = array();
			$posts = array();
			foreach($arr as $user) {
				$handle = @fopen($user, "r");
				$buffer = @fgets($handle, 4096);
				$datas = explode(";", $buffer);
				$posts[] = array('usersID' => $datas[0], 'time' => $datas[1], 'name' => $datas[2], 'ready' => $datas[3], 'spectator' => $datas[4]);
			}
			$response['posts'] = $posts;
			echo json_encode($response);
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>