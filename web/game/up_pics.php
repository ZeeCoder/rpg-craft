<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
	session_write_close();

	include("simple_image.php");
	
	ini_set('error_log', 'asd.log');
	error_log('in');
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		
		$target = 'game_folders/'.$sess_game_id."/".$_POST["entry_id"]."/";
		if (!opendir($target)) mkdir($target);
		$time = time();
		$i = 1;
		foreach ($_FILES['files']['tmp_name'] as $tmp_dir) {
			$id_bonus = sprintf("%05d", $i++);
			$new_name = $target.$time.$id_bonus.".jpg";
			$tumb_name = $target."tumb_".$time.$id_bonus.".jpg";
			move_uploaded_file($tmp_dir, $new_name);
			
			$image = new SimpleImage();
			$image->load($new_name);
			$image->resize(30, 30);
			$image->save($tumb_name);
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	setcookie("ready", 1, time()+60*60*24*30, "/");
?>