<?php
	/**
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *	Elfelejtett jelszó.
	 */
	
	// session_start();
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
		
	if (isset($_GET['code'])) {
		$forgottenCode = $_GET['code'];
		//Létezik-e ilyen kóddal rendelkező felhasználó?
		$query = "SELECT usersID, mail FROM users WHERE forgottenCode = '$forgottenCode' LIMIT 1";
		$userExists = mysql_fetch_row(mysql_query($query));
		$userExistsC = mysql_num_rows(mysql_query($query));
		if ($userExistsC>0) {
			//Új jelszó generálása, feltöltése
			$newPass = createRandomPassword(5);
			$newPassMd5 = md5($newPass);
			$query = "UPDATE users SET pass = '$newPassMd5', forgottenCode = NULL WHERE usersID = '$userExists[0]'";
			if (mysql_query($query)) {
				//Tájékoztató e-mail kiküldése
				$message =
				"
				Tisztelt Uram/Hölgyem!<br/>
				Az új jelszó generálása sikeres volt. Ezzel már be tud lépni, majd a profilban megváltoztatni a saját jelszavára.<br/>
				Jelszó: <strong>".$newPass."</strong>
				";
				
				$headers .= "From: RPGCraft <".$configini['mail'].">\r\n";
				$headers .= "Organization: RPGCraft\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=UTF-8\r\n";
				$headers .= "X-Priority: 3\r\n";
				$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
				
				$subject = "Elfelejtett jelszó: az új jelszó";
				
				if (mail($userExists[1], $subject, $message, $headers, "-f".$configini['mail']))
					$msg = $l_config['forgotten_code_generated']."$newPass</strong>)";
				else
					$msg = $l_config['forgotten_code_generated_no_mail'];
			} else
				$msg = $l_config['forgotten_code_not_generated'];
		} else
			$msg = $l_config['forgotten_bad_code'];
	} else
		$msg = $l_config['forgotten_missing_code'];
	
	$hva->add_alert( $msg );
	header( 'Location: '.$configini['root'] );
	$db->stop();
?>