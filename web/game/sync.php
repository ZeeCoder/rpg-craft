<?php
	
	/*
		=============================================================
		HV RPG ENGINE
		=============================================================
	
		sync.php
			Összesíti az eddigi fájlokat egy szinkronizációvá:
				- update_in_game_user.php,
				- check_in_game_users.php,
				- list_maps.php (+get_last_map.php),
				- list_entries.php (+get_last_entry.php).
				
		Paraméterek
			-
		
		Visszatérési értékek:
			1 - Hiányzó Játék ID.
			Egyébként - JSON, összesítve a fent látható fájlok visszatéréseit.
				in_game_users: [], map_list: [], entry_list: []
			
	*/
	
	session_start();
		if (isset($_SESSION['act_user_is_gm']))
			$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];
		if (isset($_SESSION['game_id']))
			$sess_game_id = $_SESSION['game_id'];
		if (isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];
		if (isset($_SESSION['user_name']))
			$sess_user_name = $_SESSION['user_name'];
		if (isset($_SESSION['user_is_spec']))
			$sess_user_is_spec = $_SESSION['user_is_spec'];
		$sess_game_owner_id = $_SESSION['game_owner_id'];
		$sess_act_user_is_gm = $_SESSION['act_user_is_gm'];

	session_write_close();

	header("Content-type: application/json");
	include("../mysql_handler.php");
	
	try {
		/*Szükséges paraméterek ellenőrzése*/
		if (!(  isset($sess_game_id)  ))
			throw new Exception("1");
		
		$response = array(); //A JSON struktúra gyökere.
		
		/*update_in_game_user.php*/
		try {
			/*Szükséges paraméterek ellenőrzése*/
			if (!(  isset($sess_user_id) && isset($sess_user_name)  ))
				throw new Exception();
			
			/*Legutóbbi szinkronizálás ideje*/
			$handler = fopen('game_folders/'.$sess_game_id."/user_".$sess_user_id.".txt", "r");
			$last_sync = @fgets($handler, 4096);
			$last_sync = explode(";", $last_sync);
			$last_sync = floatval($last_sync[1]);
			fclose($handler);
			if ($_POST["first_sync"]=="true") $last_sync = 0;
			
			/*Aktuális játékos frissítése*/
			$handler = fopen('game_folders/'.$sess_game_id."/user_".$sess_user_id.".txt", "w");
			fwrite($handler, $sess_user_id.";".microtime(true).";".$sess_user_name.";;".( (isset($sess_user_is_spec)) ?"true":"false") );
			fclose($handler);
		} catch (Exception $e) {}
		
		
		/*check_in_game_users.php*/
		/*Játékban lévő játékosok listázása*/
		$arr = glob('game_folders/'.$sess_game_id."/user_*.txt");
		if (is_array($arr)) {
			$posts = array();
			foreach($arr as $user) {
				$handle = @fopen($user, "r");
				$buffer = @fgets($handle, 4096);
				$datas = explode(";", $buffer);
				$posts[] = array('usersID' => $datas[0], 'time' => $datas[1], 'name' => $datas[2], 'ready' => $datas[3], 'spectator' => $datas[4], 'active'=>((time()-10)<$datas[1])?"true":"false");
			}
			$response['in_game_users'] = $posts;
		}
		
		
		/*get_last_map.php + list_maps.php*/
		try {
			$fp = @fopen('game_folders/'.$sess_game_id."/last_map.txt", "r");
			if ($fp===false) throw new Exception();
			if((($buffer = @fgets($fp, 4096)) === false)) throw new Exception();
			$last_map = floatval($buffer);
			fclose($fp);
			if ( $last_sync < $last_map ) {
				//Map lista frissítésére van szükség.
				$posts = array();
				
				$folder_arr = glob('game_folders/'.$sess_game_id."/[0-9]*.jpg");
				if (is_array($folder_arr)&&!empty($folder_arr)) {
					foreach ($folder_arr as $filename) {
						$posts[] = array('id' => md5($filename), 'name' => basename($filename), 'src' => $filename, 'tumbsrc' => 'game_folders/'.$sess_game_id."/tumb_".basename($filename));
					}
				}
				
				$response['map_list'] = $posts;
			} else throw new Exception();
		} catch (Exception $e) {
			$response['map_list'] = array();
		}
		
		/*get_last_entry.php + list_entries.php*/
		try {
			/*Szükséges paraméterek ellenőrzése*/
			if (!( isset($sess_user_id)  ))
				throw new Exception();
				
			$fp = @fopen('game_folders/'.$sess_game_id."/last_entry.txt", "r");
			if ($fp===false) throw new Exception();
			if((($last_entry = @fgets($fp, 4096)) === false)) throw new Exception();
			fclose($fp);
			$last_entry = floatval($last_entry);
			
			if ( $last_sync < $last_entry ) {
				$posts = array();
				
				$db = new MysqlHandler();
				$db->start();
					$query = "SELECT * FROM `events` WHERE `gameID` = '".$sess_game_id."'";
					$result = @mysql_query($query);
					if ($result==false) {$db->stop(); throw new Exception();}
					if (@mysql_num_rows($result)>0) {
						while ($row = @mysql_fetch_assoc($result)) {
							$file = @fopen('game_folders/'.$sess_game_id."/".$row['eventID']."/allowed.txt", "a+");
							$line = @fgets($file);
							$allowed = explode(";", $line);
							if ($line == "" || ($sess_game_owner_id == $sess_user_id) || in_array($sess_user_id, $allowed)) {
								$eventID = $row['eventID'];
								$label = $row['label'];
								$description = $row['description'];
								$last_updated = strtotime($row['last_updated']);
								
								$posts[] = array('eventID' => $eventID, 'label' => $label, 'description' => $description, 'last_updated' => $last_updated);
							}
							@fclose($file);
						}
					}
				$db->stop();
				
				
				$response['entry_list'] = $posts;
				
			} else throw new Exception();
		} catch (Exception $e) {
			$response['entry_list'] = array();
		}
		
		/*
			Behívható barátok:
				- Aki nem részese a játéknak,
				- Aki részese ugyan, de éppen nem aktív.
		*/
		if (isset($sess_act_user_is_gm) && $sess_act_user_is_gm==true) {
			$inviteable_friends = array();
			
			$db = new MysqlHandler();
			$db->start();
				$active_users = array();
				$active_users[] = $sess_user_id;
				$arr = glob('game_folders/'.$sess_game_id."/user_*.txt");
				if (is_array($arr)) {
					foreach($arr as $user) {
						$f = @fopen($user, "r");
						$user_time = @fgets($f);
						$user_time = explode(";", $user_time); $user_time = $user_time["1"];
						if ((time()-10)<$user_time) {
							$user_id = preg_split( "/[_.]/", $user );
							$active_users[] = $user_id[1];
						}
					}
				}
			
				$active_users = join("','", $active_users);
				
				/*Baráti kapcsolatok lekérdezése*/
				$query = "
					(
						SELECT (SELECT `nick` FROM `users` WHERE `usersID` = `initiaterID`) AS `name`,
						 `initiaterID` AS `id`,
					  	 (
						 	SELECT `toID` FROM `usersmessages` WHERE `toID` = `friendconnections`.`initiaterID` AND `gameRequestID` = '".$sess_game_id."' LIMIT 1
						 ) AS `invited`
						  FROM `friendconnections` WHERE `accepterID` = '".$sess_user_id."' AND `initiaterID` NOT IN ('$active_users')
					)
					UNION
					(SELECT (SELECT `nick` FROM `users` WHERE `usersID` = `accepterID`) AS `name`, `accepterID` AS `id`,
					  	 (
						 	SELECT `toID` FROM `usersmessages` WHERE `toID` = `friendconnections`.`accepterID` AND `gameRequestID` = '".$sess_game_id."' LIMIT 1
						 ) AS `invited`
						  FROM `friendconnections` WHERE `initiaterID` = '".$sess_user_id."' AND `accepterID` NOT IN ('$active_users'))
				";
				$result = mysql_query($query);
				
				while ($row = mysql_fetch_assoc($result)) {
					$inviteable_friends[] = array("id"=>$row["id"], "name"=>$row["name"], "invited"=>(($row["invited"]!=NULL)?true:false));
				}
			$db->stop();
			
			$response["inviteable_friends"] = $inviteable_friends;
		}
		
		echo json_encode($response);
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
?>