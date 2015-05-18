<?php
	/**
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *	Aktivációs kód ellenőrzése, felhasználó aktiválása.
	 */
	
	session_start();
		$sess_redirect_to = $_SESSION['redirect_to'];
	session_write_close();
	
	include_once("../config.php");
	include_once("../mysql_handler.php");
	include_once( '../config_loader.php' );
	include_once( '../plugins/hv_alert/hv_alert.php' );
	$configini = parse_ini_file('../config.ini', true);
	
	$hva = new HVAlert( array(
		'setup' => false
	) );
	
	$l_config = config_loader( array(
		'../langs/l_main'
	) );
	
	$db = new MysqlHandler();
	$db->start();
		$code = $db->clean($_GET['code']);
		if (empty($code)) {
			$hva->add_alert( $l_config['login_missing_fields'] );
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
	
		$query = "SELECT usersID FROM users WHERE code = '$code' LIMIT 1";
		$table = mysql_fetch_row(mysql_query($query));
		
		if (!empty($table)) {
			$query = "UPDATE users SET active = 1, code = NULL WHERE usersID = '$table[0]'";
			if (mysql_query($query))
				$msg = $l_config['active'];
			else
				$msg = $l_config['notactive'];
			
		} else
			$msg = $l_config['activating_nouser'];
		
	$db->stop();
	
	$hva->add_alert( $msg );
	header( 'Location: '.$configini['root'] );
?>