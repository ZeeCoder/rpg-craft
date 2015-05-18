<?php
	/**
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *	Elfelejtett jelszó.
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
		$forgottenMail = $db->clean($_POST['forgottenMail']);
		//$forgottenCode = $db->clean($_GET['forgottenCode']);
		
		if (empty($forgottenMail) && empty($forgottenCode)) {
			$hva->add_alert( $l_config['login_missing_fields'] );
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
		
		//Véletlen karaktersorozat generálása
		function createRandomPassword($length) {
			$chars = "ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789"; 
			srand((double)microtime()*1000000); 
			$i = 1; 
			$pass = '' ; 
		
			while ($i <= $length) { 
				$num = rand() % 33; 
				$tmp = substr($chars, $num, 1); 
				$pass = $pass . $tmp; 
				$i++; 
			} 
		
			return $pass;
		}
		
		$query = "SELECT usersID FROM users WHERE mail = '$forgottenMail' AND active = '1' LIMIT 1";
		$userExists = mysql_fetch_row(mysql_query($query));
		$userExistsC = mysql_num_rows(mysql_query($query));
		if ( $userExistsC>0) {
			//Új jelszó megerősítéséhez szükséges kód feltöltése az adatbázisba
			do {
				$code = createRandomPassword(10);
				$query = "SELECT COUNT(usersID) FROM users WHERE forgottenCode = '$code' LIMIT 1";
				$codeInUse = mysql_query($query);
			} while ($codeInUse[0] > 0);
			
			$query = "UPDATE users SET forgottenCode = '$code' WHERE usersID = '$userExists[0]'";
			if (!mysql_query($query)) {
				$hva->add_alert( $l_config['forgotten_code_unsuccesful'] );
				header( 'Location: '.$sess_redirect_to );
				exit();
			}
			
			//Tájékoztató e-mail kiküldése
			$message =
			"
			Tisztelt Uram/Hölgyem!<br/>
			A ".$full_url." weboldalon elfelejtett jelszavának megújítását kérte.<br/>
			(Amennyiben mégsem Ön kérte, hagyja figyelmen kívül ezt az e-mailt.)<br/><br/>
			A lentebb szereplő linkre kattintással erősítheti meg az új jelszó kérését.<br/>
			<a href=\"".$full_url."forgotten_pass/?code=$code\" target=\"_blank\"><strong>Új jelszót kérek!</strong></a><br/>
			";
			
			$headers .= "From: RPGCraft <".$configini['mail'].">\r\n";
			$headers .= "Organization: RPGCraft\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf8\r\n";
			$headers .= "X-Priority: 3\r\n";
			$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
			
			$subject = "Elfelejtett jelszó: kód";
			
			if (mail($forgottenMail, $subject, $message, $headers, "-f".$configini['mail']))
				$hva->add_alert( $l_config['forgotten_pass_succesful'] );
			else
				$hva->add_alert( $l_config['forgotten_pass_unsuccesful'] );
			
			header( 'Location: '.$sess_redirect_to );
		} else {
			$hva->add_alert( $l_config['no_such_user'] );
			header( 'Location: '.$sess_redirect_to );
		}
	$db->stop();
?>