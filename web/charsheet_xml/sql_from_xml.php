<?php
	/*
		=============================================================
		RPGCraft ENGINE
		Készítette: Hubert Viktor
		Minden jog fenntartva.
		=============================================================
	
		sql_from_xml.php
			A paraméterben megadott karaterlapot validálja, telepíti, törli.
			Ha csak a game_type paraméter adott, csak validál.
		
		Paraméterek
			- game_type: kötelező. A karakterlap típusa. (Ebben a mappában szerepel.)
			- install_pass: opcionális: 'D0n0Tv4L1d4T3'. Ha az adott karakterlapot telepíteni is akarjuk, nem csak validálni.
			- forced_install: opcionális. Ha megadjuk, telepítéskor a létező táblákat DROP-olja.
			- remove_pass: opcionális: 'D0n0Tv4L1d4T3'. Ha az adott karakterlapot törölni akarjuk.
			- simple_output: Ha csak egyszerű 0/1 értéket akarunk visszakapni a sikerességről, ezt a paramétert kell megadnunk.
								Értéke nem számít, csak meg legyen adva. (&simple_output is jó, lehet NULL)
								0: sikeres, 1: sikertelen (Akkor is ha csak warningot kapunk)
		
		Visszatérési érték:
			A telepítés kiértékelése. (Error/Warning)
	*/
	session_start();
		$sess_lang = $_SESSION["lang"];
	session_write_close();
	header("Content-type: text/html");
	include("../mysql_handler.php");
	$problems_encountered=false;
	
	$configini = parse_ini_file('../config.ini', true);
	
	$full_url = explode('/', $_SERVER['SERVER_PROTOCOL']);
	$full_url = $full_url[0];
	$full_url = strtolower( $full_url.'://'.$_SERVER['HTTP_HOST'].$configini['root'] );
	
	define( 'LOAD_FROM', substr($full_url, 0, -1) );
	
	function readLastLine ($file) {
		$fp = @fopen($file, "r");
		
		$pos = -1;
		$t = " ";
		while ($t != "\n") {
			if (!fseek($fp, $pos, SEEK_END)) {
				$t = fgetc($fp);
				$pos = $pos - 1;
			} else {
				rewind($fp);
				break;
			}
		}
		$t = fgets($fp);
		fclose($fp);
		return $t;
	}
	try {
		/*Ha a paraméter hiányzik.*/
		if (!isset($_REQUEST["game_type"]))
			throw new Exception(1);
			
		$validator_mode=false;
		$remove_mode=false;
		$install_mode=false;
		$simple_output=false;
		$forced_install=false;
		
		if (
			(!isset($_REQUEST["install_pass"])||$_REQUEST["install_pass"]!="D0n0Tv4L1d4T3")
			&&
			(!isset($_REQUEST["remove_pass"])||$_REQUEST["remove_pass"]!="D0n0Tv4L1d4T3")
		) $validator_mode=true; /*Pass: 'DO NOT VALIDATE!'*/
		
		if (
			(isset($_REQUEST["forced_install"])&&$_REQUEST["forced_install"]=="D0n0Tv4L1d4T3")
		) $forced_install=true;
		
		if (isset($_REQUEST["remove_pass"])&&$_REQUEST["remove_pass"]=="D0n0Tv4L1d4T3") $remove_mode=true;
		if (isset($_REQUEST["install_pass"])&&$_REQUEST["install_pass"]=="D0n0Tv4L1d4T3") $install_mode=true;
		if (isset($_REQUEST["simple_output"])) $simple_output=true; /*0/1-es visszatérés csak, attól függően hogy van e hiba. (akár warning is.)*/
		if (isset($_REQUEST["generate_ini"]) && $_REQUEST["generate_ini"]=="true") $generate_ini=true;
		
		/*Load the XML.*/
		$game_type = $_REQUEST["game_type"];
		$xmlurl = LOAD_FROM."/charsheet_xml/".(($validator_mode)?"":"").$game_type."/".$game_type.".xml";
		//echo $xmlurl;
		if ( is_file( $xmlurl ) )
			echo $xmlurl;
			
		if (!$validator_mode && !$simple_output)
			echo "Attempt to load XML: $xmlurl<br/><br/>";
		
		$ch = curl_init($xmlurl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$xmlurl = curl_exec($ch);
		
		echo "<pre>";
		if (!($xmlO = simplexml_load_string($xmlurl)))
			throw new Exception(4);
		echo "</pre>";
		
		/*A már sikeresen telepített táblák listája, ha esetleg valami rosszul sülne el és törölnünk kellene őket.*/
		$installed_tables=array();
		/*Esetleges figyelmeztetések listája*/
		$warning=array();
		/*A funkció ami hiba esetén törli a már elkészült táblákat, 'roll back'-kel.*/
		function roll_back_installed($installed_tables) {
			$success=true;
			foreach ($installed_tables as $table_name)
				$success = ($success && mysql_query("DROP TABLE `$table_name`;"));
			
			/*Ha minden táblát sikeresen töröltünk: true.*/
			return $success;
		}
		
		$db = new MysqlHandler();
		$db->start();
		if (!$simple_output) {
			if ($validator_mode) echo "Validity check on uploaded character sheet...<br/><br/>";
			else if (!$remove_mode) echo "Installing '$game_type' character sheet...<br/><br/>";
			else echo "Removing '$game_type' character sheet...<br/><br/>";
		}
		
		if (!empty($_REQUEST["install_game_name"])) 
			$game_type=$_REQUEST["install_game_name"];
			
		if ($generate_ini) {
			$fp = fopen($game_type."/lang_".$game_type."_".$sess_lang.".ini", "w");
			@fwrite($fp, "author = \"\"\ndesc = \"\"\n");
		}
		
		foreach ($xmlO as $tags) {
				
			if ($tags->getName()=="description") {$warning[]=1;continue;} /*Nem támogatott tag, kompatibilitás miatt meghagyva.*/
			if ($tags->getName()=="break") continue; /*Ehhez nem kell adatbázist létrehozni.*/
			$table_name = "s_".$game_type."_".$tags->getName();
			
			if ($generate_ini) {
				@fwrite($fp, "\n;".$tags->getName()."\n");
				if ($tags->attributes()->display=="design")
					@fwrite($fp, $tags->getName()." = \"\"\n");
				if (isset($tags->attributes()->left_title))
					@fwrite($fp, $tags->getName().".left_title = \"\"\n");
				if (isset($tags->attributes()->right_title))
					@fwrite($fp, $tags->getName().".right_title = \"\"\n");
				if (isset($tags->attributes()->bot_title))
					@fwrite($fp, $tags->getName().".bot_title = \"\"\n");
				if (isset($tags->attributes()->top_title))
					@fwrite($fp, $tags->getName().".top_title = \"\"\n");
			}
			if ($tags->attributes()->display=="design")
				continue;
				
			if (!$simple_output) {
				if ($validator_mode) echo "Checking '".$tags->getName()."' tag...";
				else if (!$remove_mode) echo "Installing '$table_name' table...";
				else echo "Removing '$table_name' table...";
			}
			$table_exists = mysql_query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'hvwebhu_rpg' AND table_name = '$table_name';");
			$table_exists = mysql_num_rows($table_exists)>0;
			
			/*Ha van ilyen tábla, töröljük.*/
			if ($table_exists&&$forced_install&&!$validator_mode) {
				$remove_success = mysql_query("DROP TABLE `$table_name`;");
				if (!$remove_success) {
					if (!$remove_mode) $warning[] = 3;
					else throw new Exception(6);
				}
			} else if ($table_exists&&!$forced_install&&!$validator_mode) throw new Exception(7);
			
			if ($remove_mode) {
				if (!$simple_output) echo " success...<br/>";
				continue;
			}
			/*Lekérés előállítása a tábla létrehozásához.*/
			$query = "CREATE TABLE `$table_name` (`specID` INT(11) NOT NULL AUTO_INCREMENT, `gamesID` INT(11), `usersID` INT(11)";
			$rekord_counter = 0;
			foreach ($tags->record as $records) {
				$field_names = array();
				if ($generate_ini) {
					if (isset($records->attributes()->left_title))
						@fwrite($fp, $tags->getName().".record_$rekord_counter.left_title = \"\"\n");
					if (isset($records->attributes()->right_title))
						@fwrite($fp, $tags->getName().".record_$rekord_counter.right_title = \"\"\n");
					if (isset($records->attributes()->bot_title))
						@fwrite($fp, $tags->getName().".record_$rekord_counter.bot_title = \"\"\n");
					if (isset($records->attributes()->top_title))
						@fwrite($fp, $tags->getName().".record_$rekord_counter.top_title = \"\"\n");
				}
				$rekord_counter++;
				foreach ($records->field as $fields) {
					
					if ($generate_ini)
						fwrite($fp, $fields->attributes()->name." = \"\"\n");
					
					if ($validator_mode) {
						if ( in_array( strval($fields->attributes()->name) , $field_names) )
							throw new Exception(5);
						$field_names[] = strval($fields->attributes()->name);
						continue;
					}
					
					/*A mező láthatósága módosítható-e. Alapértelmezettként igen.*/
					$visibility = 1;
					if (isset($fields->attributes()->visible))
						$visibility = (strval($fields->attributes()->visible) == "false")?0:1;
					
					/*Mezőre vonatkozó lekérés összeállítása.*/
					if (isset($fields->attributes()->static_content))
						/*Ha a mező statikus.*/
						$query .= ", `vis_".$fields->attributes()->name."` TINYINT(1) NOT NULL DEFAULT '$visibility'";
					else {
						/*Ha a mező nem statikus, több tulajdonsággal rendelkezik.*/
						/*Mező típusa explicit adott-e? Alapértelmezett típus: VARCHAR.*/
						$type = "VARCHAR";
						if (isset($fields->attributes()->type)&&strval($fields->attributes()->type) == "number")
							$type = "INT";
						
						/*Mező hosszához tartozó validator. Alapértelmezett VARCHAR-hoz: 100. (VARCHAR(100) SQL-ben.)*/
						$length = "(100)";
						if ($type == "VARCHAR" && isset($fields->attributes()->length)) {
							$length = explode(",", strval($fields->attributes()->length)); /*A formátum: 'tól,ig'.*/
							$length = "(".$length[1].")";
						}
						/*Alapértelmezett érték beállítása, ha van.*/
						$default_val = "";
						if (isset($fields->attributes()->default_val))
							$default_val = " NOT NULL DEFAULT '".strval($fields->attributes()->default_val)."'";
						
						/*Lekérés összeállítása.*/
						$query .= ", `".$fields->attributes()->name."` ".$type.$length.$default_val.", `vis_".$fields->attributes()->name."` TINYINT(1) NOT NULL DEFAULT '$visibility'";
					}
				}
			}
			$query .= ", PRIMARY KEY (`specID`)) AUTO_INCREMENT=100;";
			
			//echo $query;
			
			/*Tábla létrehozásának megkísérlése*/
			if (!$validator_mode) {
				if (mysql_query($query)) {
					if (!$simple_output) echo " success.<br/>";
					$installed_tables[] = $table_name;
				} else {
					if (!$simple_output) echo " failure! Rolling back installation...<br/>";
					if (roll_back_installed($installed_tables)) throw new Exception(2);
					else throw new Exception(3);
				}
			} else if (!$simple_output) echo " success.<br/>";
		}
		
		if ($generate_ini)
			fclose($fp);
		
		$db->stop();
		
		/*Játék regisztrálása az all_games_list.ini-be.*/
		if ($install_mode) {
			$id = (substr(readLastLine("all_games_list.ini"), 0, 1) + 1);

			$f = fopen("all_games_list.ini", "a");
			fwrite($f, "\r\n".$id." = \"".$game_type."-".$_REQUEST["install_game_full_name"]."\"");
			fclose($f);
		}
		
		if (!$simple_output) echo "<br/>No errors.";
		
	} catch (Exception $e) {
		$problems_encountered=true;
		if (!$simple_output) {
			switch ($e->getMessage()) {
				case 1: $error_msg="Missing 'game_type' or 'game_url' GET parameter."; break;
				case 2: $error_msg="Error in the XML structure, the generated SQL couldn't finish. Rollback was succesful."; break;
				case 3: $error_msg="Error in the XML structure, the generated SQL couldn't finish. Rollback was unsuccesful, there might be some tables in the database left."; break;
				case 4: $error_msg="Error in the XML structure. Parser error."; break;
				case 5: $error_msg="Duplicate field name."; break;
				case 6: $error_msg="Some table were unable to remove."; break;
				case 7: $error_msg="One or more table already existed that stopped the installation. Use the 'forced_install' switch, to drop theese tables. (Be cautious, since every data stored in theese tables will be lost.)"; break;
				default: $error_msg="Unknown error.";
			}
			if ($remove_mode) echo "<br/><br/>An error occured, that stopped the deletion. See the details below.<br/><br/>";
			if ($validator_mode) echo "<br/><br/>An error occured, that stopped the validation. See the details below.<br/><br/>";
			else echo "<br/><br/>An error occured, that stopped the installation. See the details below.<br/><br/>";
			echo "Error code: ".$e->getMessage()."<br/>";
			echo "Error message: '".$error_msg."'";
		}
	}
	if (!$simple_output) {
		echo "<br/><br/>";
		if (!empty($warning)) {
			$problems_encountered=true;
			echo "The following warnings were encountered:<br/>";
			echo "<ul>";
			foreach ($warning as $w){
				switch ($w) {
					case 1: $w_msg="The 'description' tag is deprecated, and soon will be removed, and then this'll be a syntax error. Description information is now stored in the 'info_[charsheet_name]_[language].ini' files, respectively."; break;
					/*case 2: $w_msg="The 'break' tag shouldn't be used here. Ignored."; break; //Ebben nem vagyok biztos*/
					case 3: $w_msg="Some table already existed, but couldn't be removed."; break;
				}
				echo "<li>Code: $w Message: ' $w_msg'</li>";
			}
			echo "</ul>";
		} else echo "No warnings.";
	} else if(!empty($warning)) $problems_encountered=true;
	
	if ($validator_mode) {
		if (!$simple_output) {
			if ($problems_encountered) echo "<br/><br/>It seems you have some problems with your character sheet. See the details above.";
			else echo "<br/><br/>Congratulations! Your character sheet passed the validation! Note that there could be errors at the installation process, this is no guarantee, just the first step.";
		}
		else if ($problems_encountered) echo 1;
		else echo 0;
	}
	
?>