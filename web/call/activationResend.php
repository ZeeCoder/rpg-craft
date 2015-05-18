<?php
	/**
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *	Aktivációs kód újraküldése.
	 */
	
	session_start();
		$sess_redirect_to = $_SESSION['redirect_to'];
	session_write_close();

	include_once("../config.php");
	include_once("../mysql_handler.php");
	include_once( '../config_loader.php' );
	include_once( '../plugins/hv_alert/hv_alert.php' );
	
	$configini = parse_ini_file('../config.ini', true);
	
	$full_url = explode('/', $_SERVER['SERVER_PROTOCOL']);
	$full_url = $full_url[0];
	$full_url = strtolower( $full_url.'://'.$_SERVER['HTTP_HOST'].$configini['root'] );
	
	$hva = new HVAlert( array(
		'setup' => false
	) );
	
	$l_config = config_loader( array(
		'../langs/l_main'
	) );
	
	$db = new MysqlHandler();
	$db->start();
		$activationMail = $db->clean($_POST['activationMail']);
		if (empty($activationMail)) {
			$hva->add_alert( $l_config['login_missing_fields'] );
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
		
		$query = "SELECT active, code FROM users WHERE mail = '$activationMail' LIMIT 1";
		$userExists = mysql_fetch_row(mysql_query($query));
		$userExistsC = mysql_num_rows(mysql_query($query));
	$db->stop();

	if ( $userExistsC>0 ) {
		if ($userExists[0] == 0) {
			$message =
			"
			<strong>Tisztelt Uram/Hölgyem!</strong><br/><br/>A <a href=\"".$full_url."\" target=\"_blank\">".$full_url."</a> weboldalon regisztráltak az Ön címével!<br/>
			(Amennyiben nem Ön regisztrált, hagyja figyelmen kívül ezt az e-mailt.)<br/><br/>
			A következő linkre kattintással véglegesítheti a regisztrációt: <a href=\"".$full_url."activation/?code=".$userExists[1]."\" target=\"_blank\">Regisztráció aktiválása!</a><br/><br/>
			FONTOS: Az aktivációs link 3 napon belül elavul, a regisztráció törlésre kerül.<br/>
			Ha ez a levél valamilyen okból elveszne, úgy az oldalon újraküldheti a kódot az e-mail címe megadásával.<br/><br/>
			(<u>Ez egy automatikusan generált e-mail, nem kell válaszolnia.</u>)
			<br/><br/>
			<a href=\"".$full_url."\" target=\"_blank\">".$full_url."</a>";
			
			$headers .= "From: RPGCraft <".$configini['mail'].">\r\n";
			$headers .= "Organization: RPGCraft\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf8\r\n";
			$headers .= "X-Priority: 3\r\n";
			$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
			
			$subject = "Aktivációs kód";
			
			if (mail($activationMail, $subject, $message, $headers, "-f".$configini['mail']))
				$hva->add_alert( $l_config['activation_succesful'] );
			else
				$hva->add_alert( $l_config['activation_unsuccesful'] );
				
		} else
			$hva->add_alert( $l_config['already_active'] );
		
		header( 'Location: '.$sess_redirect_to );
	} else {
		$hva->add_alert( $l_config['no_such_user'] );
		header( 'Location: '.$sess_redirect_to );
	}
?>