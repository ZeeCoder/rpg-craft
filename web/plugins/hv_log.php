<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Log objektum.
	*/
	
	class HVLog {
		var $dest;
		var $prefix;
		var $c;
		/*
			Logfájlok mentése a logs mappába.
			
			Paraméterek
				A paraméter kétféleképp adható meg. Vagy csak a logfile neve, vagy több paraméter esetény egy asszociatív tömb.
			
				name - Log fájl neve.
				day_mapping - true/false, legyen-e napok szerinti mappázás.
				log_separator - A logfájlon belüli elválasztó a konstruktor meghívásakor.
				prefix - Az objektumhoz tartozó prefix. (Minden üzenet elején ott lesz.)
				root - Ide kerülnek a logfájlok.
		*/
		function __construct( $p = array() ) {
			
			$p_def = array(
				'name'=>'default'
				,'day_mapping'=>true
				,'log_separator'=>''
				,'prefix'=>''
				,'root' => NULL
			);
			
			if (!is_array($p))
				$p = array('name'=>$p);
			
			$p = array_merge($p_def, $p);
			
			$this->c = $p;
			
			$day='';
			if ($p['day_mapping']) $day = date('m-d').'/';
			$dir = $p['root'].$day;
			if (!is_dir($dir)) mkdir($dir);
			$this->dest=$dir.$p['name'].'.log';
			$this->prefix=$p['prefix'];
			$set = ini_set('error_log',$this->dest);
			$start = $this->message($p['log_separator'], false);
			
			return $set && $start;
		}
		
		/*
			Üzenet lementése a logfájlba.
			
			message - A konkrét üzenet.
			prefix - Az üzenethez tartozó prefix. A default: '[ 0000-00-00 00:00 ]' forma.
		*/
		function message($message='empty', $prefix=true) {
			if($prefix===true)
				$prefix = '[ '.date('Y-m-d H:i', time()).' ] '.$this->prefix;
			error_log($prefix.$message."\n", 3, $this->dest);
		}
		
	}
	
?>