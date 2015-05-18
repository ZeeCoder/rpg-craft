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
		
		$response = array();
		$posts = array();
		$file_num = 0;
		
		$folder_arr = glob('game_folders/'.$sess_game_id."/[0-9]*.jpg");
		if (is_array($folder_arr)&&!empty($folder_arr)) {
			foreach ($folder_arr as $filename) {
				$posts[] = array('id' => md5($filename), 'name' => basename($filename), 'src' => $filename, 'tumbsrc' => "game_folders/".$sess_game_id."/tumb_".basename($filename)); $file_num++;
			}
		} else throw new Exception("0");
		
		if ($file_num > 0) {
			header("Content-type: application/json");
			$response['posts'] = $posts;
			echo json_encode($response);
		} else {
			echo "0";
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>