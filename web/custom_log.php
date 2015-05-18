<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Saját logolás az RPG oldal számára
	*/
	if (strlen(session_id())==0) session_start();
	class custom_log {
		var $dest;
		
		//error_log beállítása
		function __construct($type=0) {
			if ($type==0) $type= "rpg_user_log_".$_SESSION['user_id'];
			else if ($type==1) $type= "rpg_game_log_".$_SESSION['user_id'];
			else if ($type==2) $type= "client_check_users_".$_SESSION['user_id'];
			else if ($type==3) $type= "client_update_user_".$_SESSION['user_id'];
			
			$this->dest=$_SERVER['DOCUMENT_ROOT']."/rpg/logs/".$type.".log";
			return ini_set("error_log",$this->dest);
			
		}
		
		function message($message="empty") {
			error_log("[ ".date("Y-m-d H:i", time())." ] ".$message."\n", 3, $this->dest);
		}
		
	}
	
?>