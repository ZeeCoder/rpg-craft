<?php
	/*
		!DEPRECATED
	
		=============================================================
		RPGCraft ENGINE
		Készítette: Hubert Viktor
		Minden jog fenntartva.
		=============================================================
	
		config_loader.php
			A megadott ini-fájlokat tölti be. Az ini-fájlokat intelligensen rakja össze úgy, hogy közben a megfelelő nyelvet keresi ki, illetve ha a z nincs, akkor az alapértelmezettet.
	*/
	if (!function_exists('session_starter')) {
		function session_starter() {
			if ( !(session_id() && headers_sent()) ) {
				return session_start();
			}
			return false;
		}
	}

	if (session_starter()) {
		$c_sess_lang = $_SESSION["lang"];
		$c_sess_supported_lang = $_SESSION["supported_lang"];
		session_write_close();
	}

	function config_loader($name_arr=NULL, $lang=NULL){
		
		// Paraméterek kezelése
		if ($name_arr==NULL)return array();
		if (!is_array($name_arr))$name_arr=array($name_arr);
		if ($lang==NULL) $lang=$c_sess_lang;
		
		// Felsorolt config-fileok betöltése, összeolvasztása
		$conf=array();
		foreach ($name_arr as $name) {
			$no_lang_name = $name;
			if (substr($name, -1, 1)!="_") $name.="_";
			
			// Alap betöltése
			$src = $name.$lang.".ini";
			$act_can_load = is_file($src);
			$a_conf = false;
			if ($act_can_load) $a_conf = parse_ini_file($src, true);
			else {
				// Hátha nyelvfüggetlen az ini fájl.
				$src = $no_lang_name.".ini";
				$act_can_load = is_file($src);
				$a_conf = false;
				if ($act_can_load) $a_conf = parse_ini_file($src, true);
			}
			
			// Kiegészítés a defaulttal
			$src = $name.$c_sess_supported_lang[0].".ini";
			$def_can_load = is_file($src);
			$d_conf = false;
			if ($def_can_load) $d_conf = parse_ini_file($src, true);
			
			// Config tömb összeillesztése
			if ($a_conf && $d_conf) $conf_add=array_merge($d_conf, $a_conf);
			else if ($a_conf && !$d_conf) $conf_add=$a_conf;
			else if (!$a_conf && $d_conf) $conf_add=$d_conf;
			else {
				$confs = glob($name."??.ini");
				if (is_array($confs)&&!empty($confs)) $conf_add=parse_ini_file($confs[0], true);
				else $conf_add=array();
			}
			$conf += $conf_add;
		}
		
		return $conf;
		
	}
	
	// Visszaadja egy adott fájlhoz tartozó elérhető nyelvet. (Ideális esetben a user által használtat, egyébként az elérhetőt.)
	function get_available_js_lang($file_to_check) {
		if (is_file($file_to_check."_".$c_sess_lang.".js")) return $c_sess_lang;
		else {
			$langs = glob($file_to_check."_"."??.js");
			if (is_array($langs)&&!empty($langs)) return substr($langs[0], -5, 2);
		}
		return NULL;
	}
?>