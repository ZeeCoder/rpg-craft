<?php
	/**
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *	Regisztráció, aktivációs kód kiküldése.
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
		$registerMail = $db->clean($_POST['registerMail']);
		$registerName = $db->clean($_POST['registerName']);
		$registerPass = md5($db->clean($_POST['registerPass']));
		$registerBorn = $db->clean($_POST['registerBorn']);
		$registerGender = $db->clean($_POST['registerGender']);
		
		if ( empty($registerMail) || empty($registerName) || empty($registerPass) || empty($registerBorn) ) {
			$hva->add_alert( $l_config['login_missing_fields'] );
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
		
		//Véletlen karaktersorozat generálása
		function createRandomPassword($length) {
			$chars = "ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789"; 
			srand((double)microtime()*1000000);
			$i = 1; 
			$pass = ''; 
		
			while ($i <= $length) { 
				$num = rand() % 33; 
				$tmp = substr($chars, $num, 1); 
				$pass = $pass . $tmp; 
				$i++; 
			} 
		
			return $pass;
		}
		
		$query = "SELECT 1 FROM `users` WHERE `nick` = '$registerName' OR `mail` = '$registerMail' LIMIT 1";
		$table = @mysql_query($query);
		if (mysql_num_rows($table) > 0) {
			$hva->add_alert( $l_config['register_existing_user'] );
				session_start();
					$_SESSION['saved_reg_data'] = array(
						'mail' => $registerMail
						,'nick' => $registerName
						,'born' => $registerBorn
						,'gender' => $registerGender
					);
				session_write_close();
			header( 'Location: '.$sess_redirect_to );
			exit();
		} else {
			do {
				$code = createRandomPassword(10);
				$query = "SELECT COUNT(id) FROM users WHERE code = '$code'";
				$sameCodes = mysql_fetch_row(mysql_query($query));
			} while($sameCodes[0] > 0);
			$query = "INSERT INTO users (nick, mail, pass, born, gender, joined, code) VALUES ('$registerName', '$registerMail', '$registerPass', '$registerBorn', '$registerGender', CURDATE(), '$code')";
			if (mysql_query($query)) {
				//Aktiváló kód elküldése e-mailben
				$message =
				"
				<strong>Tisztelt Uram/Hölgyem!</strong><br/><br/>A <a href=\"".$full_url."\" target=\"_blank\">".$full_url."</a> weboldalon regisztráltak az Ön címével!<br/>
				(Amennyiben nem Ön regisztrált, hagyja figyelmen kívül ezt az e-mailt.)<br/><br/>
				A következő linkre kattintással véglegesítheti a regisztrációt: <a href=\"".$full_url."activation/?code=$code\" target=\"_blank\">Regisztráció aktiválása!</a><br/><br/>
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
				
				if (mail($registerMail, $subject, $message, $headers, "-f".$configini['mail']))
					$hva->add_alert( $l_config['register_succesful'] );
				else {
					$hva->add_alert( $l_config['register_unsuccesful'] );
					session_start();
						$_SESSION['saved_reg_data'] = array(
							'mail' => $registerMail
							,'nick' => $registerName
							,'born' => $registerBorn
							,'gender' => $registerGender
						);
					session_write_close();
				}
					
			} else
				$hva->add_alert( $l_config['database_error'] );
			
			header( 'Location: '.$sess_redirect_to );
			exit();
		}
?>