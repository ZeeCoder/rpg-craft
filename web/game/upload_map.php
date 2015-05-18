<?php
	session_start();
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
	session_write_close();
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_act_user_is_gm) && isset($sess_game_id)  ))
			throw new Exception("1");
		
		/*Fájl feltöltése*/
		
		include("simple_image.php");
		$target = 'game_folders/'.$sess_game_id."/";
		if (!opendir($target)) mkdir($target);
		$time = time();
		$i = 1;
		foreach ($_FILES['files']['tmp_name'] as $tmp_dir) {
			$id_bonus = sprintf("%05d", $i++);
			$new_name = $target.$time.$id_bonus.".jpg";
			$tumb_name = $target."tumb_".$time.$id_bonus.".jpg";
			if (!move_uploaded_file($tmp_dir, $new_name)) throw new Exception("3");
			
			$image = new SimpleImage();
			$image->load($new_name);
			$image->resize(15, 15);
			$image->save($tumb_name);
		}
		
		/*az ideiglenes mappából átteszi a fájlt a végleges mappába (a $target . $file_name összeilleszti a két stringet, így uploads/fajlnev-et kapunk)*/
		/*Ha minden OK, jelzés a frissítésről a kliensek felé.*/
		$fp = @fopen('game_folders/'.$sess_game_id."/last_map.txt", "w");
			@fwrite($fp, microtime(true));
		@fclose($fp);
		
		/*Hibamentes visszatérés*/
		echo "0";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	setcookie("ready", 1, time()+60*60*24*30, "/");
?>