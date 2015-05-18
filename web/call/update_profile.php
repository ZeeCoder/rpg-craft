<?php
	
	session_start();
		if (isset($_SESSION["user_id"]))
			$sess_user_id = $_SESSION["user_id"];
	session_write_close();
	if (isset($sess_user_id)) {
		
		include_once("../mysql_handler.php");
		$db = new MysqlHandler();
		$db->start();
		
			$registerMail = $db->clean($_POST['registerMail']);
			$registerBorn = $db->clean($_POST['registerBorn']);
			$registerGender = $db->clean($_POST['registerGender']);
			
			if (!empty($_POST['registerPass'])) {
				$registerPass = md5($db->clean($_POST['registerPass']));
				$query = "
					UPDATE `users` SET
						`mail` = '$registerMail',
						`born` = '$registerBorn',
						`gender` = '$registerGender',
						`pass` = '$registerPass'
					WHERE `usersID` = '".$sess_user_id."';
				";
			} else {
				$query = "
					UPDATE `users` SET
						`mail` = '$registerMail', 
						`born` = '$registerBorn', 
						`gender` = '$registerGender'
					WHERE `usersID` = '".$sess_user_id."';
				";
			}
			echo mysql_query($query);
			
			session_start();
				$_SESSION['user_mail'] = $registerMail;
				$_SESSION['user_born'] = $registerBorn;
				$_SESSION['user_gender'] = $registerGender;
			session_write_close();
		
		$db->stop();
		
	}
	
?>