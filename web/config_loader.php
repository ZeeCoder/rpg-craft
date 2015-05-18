<?php
	/*
		=============================================================
		RPGCraft ENGINE
		Készítette: Hubert Viktor
		Minden jog fenntartva.
		=============================================================
	
		config_loader.php
			A megadott ini-fájlokat tölti be. Az ini-fájlokat intelligensen rakja össze úgy, hogy közben a megfelelő nyelvet keresi ki, illetve ha a z nincs, akkor az alapértelmezettet.
	*/
	
	if (strlen(session_id())==0) session_start();
	
	function config_loader($name_arr=NULL, $lang=NULL){
		
		/*Paraméterek kezelése*/
		if ($name_arr==NULL)return array();
		if (!is_array($name_arr))$name_arr=array($name_arr);
		if ($lang==NULL) $lang=$_SESSION["lang"];
		
		/*Felsorolt config-fileok betöltése, összeolvasztása*/
		$conf=array();
		foreach ($name_arr as $name) {
			$no_lang_name = $name;
			if (substr($name, -1, 1)!="_") $name.="_";
			
			/*Alap betöltése*/
			$src = $name.$lang.".ini";
			$act_can_load = is_file($src);
			$a_conf = false;
			if ($act_can_load) $a_conf = parse_ini_file($src, true);
			else {
				/*Hátha nyelvfüggetlen az ini fájl.*/
				$src = $no_lang_name.".ini";
				$act_can_load = is_file($src);
				$a_conf = false;
				if ($act_can_load) $a_conf = parse_ini_file($src, true);
			}
			
			/*Kiegészítés a defaulttal*/
			$src = $name.$_SESSION["supported_lang"][0].".ini";
			$def_can_load = is_file($src);
			$d_conf = false;
			if ($def_can_load) $d_conf = parse_ini_file($src, true);
			
			/*Config tömb összeillesztése*/
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
	
	/*Visszaadja egy adott fájlhoz tartozó elérhető nyelvet. (Ideális esetben a user által használtat, egyébként az elérhetőt.)*/
	function get_available_js_lang($file_to_check) {
		if (is_file($file_to_check."_".$_SESSION["lang"].".js")) return $_SESSION["lang"];
		else {
			$langs = glob($file_to_check."_"."??.js");
			if (is_array($langs)&&!empty($langs)) return substr($langs[0], -5, 2);
		}
		return NULL;
	}
	
?>