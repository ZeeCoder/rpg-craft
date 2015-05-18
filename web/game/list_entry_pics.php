<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
	session_write_close();
	
	include("../mysql_handler.php");
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id)  ))
			throw new Exception("1");
		if (!(  isset($_POST["entry_id"])  ))
			throw new Exception("2");
		
		$response = array();
		$posts = array();
		$folder_arr = glob('game_folders/'.$sess_game_id."/".$_POST["entry_id"]."/"."[0-9]*.jpg");
		if (is_array($folder_arr)&&!empty($folder_arr)) {	
			header("Content-type: application/json");
			usort($folder_arr, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
			foreach ($folder_arr as $filename) $files[] = array("id" => basename($filename, ".jpg"));
			$response['posts'] = $files;
			echo json_encode($response);
		} else {
			echo 0;
		}
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>