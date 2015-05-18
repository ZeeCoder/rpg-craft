<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Bejelentkezés az űrlapból kapott adatok alapján, SESSION tömb feltöltése adatokkal.
	*/
	
	session_start();
		$sess_PAGE_ROOT = $_SESSION['PAGE_ROOT'];
		$sess_lang = $_SESSION['lang'];
		$sess_redirect_to = $_SESSION['redirect_to'];
		$sess_user_id = $_SESSION['user_id'];
		$sess_user_name = $_SESSION['user_name'];
	session_write_close();

	include_once( '../mysql_handler.php' );
	include_once( '../plugins/hv_alert/hv_alert.php' );
	include_once( '../plugins/hv_log.php' );
	include_once( '../config_loader.php' );
		
	
	$l_config = config_loader( array(
		'../langs/l_main'
	) );
	
	$hva = new HVAlert( array(
		'setup' => false
	) );
	/*
	session_start();
			$hva->add_alert( $l_config['login_missing_fields'] );
	session_write_close();
	*/
	$log = new HVLog( array(
		'name' => 'log'
		,'root' => '../logs/logins/'
	) );
	
	$db = new MysqlHandler();
	$db->start();
		$loginMail = $db->clean( $_POST['loginMail'] );
		$loginPass = $db->clean( $_POST['loginPass'] );
		if ( empty( $loginMail ) || empty( $loginPass ) ) {
			$hva->add_alert( $l_config['login_missing_fields'] );
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
		
		$query = "SELECT * FROM `users` WHERE `mail` = '$loginMail' AND `pass` = '".md5($_POST['loginPass'])."' AND `active` = '1' LIMIT 1";
		$userLogin = mysql_query( $query );

		if ( mysql_num_rows( $userLogin )>0 ) {
			$user = mysql_fetch_assoc( $userLogin );

			session_start();
				$_SESSION['pass'] = 'Tr2CRAcr';
				$_SESSION['user_id'] = $user['usersID'];
				$_SESSION['user_name'] = $user['nick'];
				$_SESSION['user_mail'] = $user['mail'];
				$_SESSION['user_joined'] = $user['joined'];
				$_SESSION['user_rights'] = $user['rights'];
				$_SESSION['user_gender'] = $user['gender'];
				$_SESSION['user_born'] = $user['born'];
			session_write_close();
			
			$query = "UPDATE users SET lang = '".$sess_lang."' WHERE usersID = '".$sess_user_id."'";

			mysql_query( $query );
			$log->message('User "'.$sess_user_name.'" (#'.$sess_user_id.') logged in.');
		} else {
			$hva->add_alert( $l_config['login_unsuccesful'] );
			$location = $sess_PAGE_ROOT.'login/';
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
	$db->stop();
	header( 'Location: '.$sess_PAGE_ROOT.$sess_lang );
	exit();
?>