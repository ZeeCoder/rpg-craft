<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Ez az osztály felel a config-ban szereplő adatok alapján a mySQL kapcsolat létesítéséért.
		A kapcsolat magyar ékezetes betűket támogat.
	*/
	
	class MysqlHandler {
		var $connection;
		var $db;
		//A kapcsolat létrehozása
		function start() {
			$config = parse_ini_file("config.ini", true);
			$this->connection = mysql_connect($config["db_host"], $config["db_user"], $config["db_pass"]);
			if (!$this->connection) die("Nem sikerült kapcsolódni az adatbázishoz!");
			$this->db = mysql_select_db($config["db_name"], $this->connection) or die("Nem sikerült kiválasztani az adatbázist!");
			mysql_query("SET NAMES UTF8");
			return true;
		}
		
		//A kapcsolat bezárása
		function stop() {
			mysql_close($this->connection);
		}
		
		//Az adatbázisba kerülő adatok "tisztítása". (Ne kerüljön fel pl.: JS kód egy profil nevébe.)
		function clean($str) {
			$str = @trim($str);
			if(get_magic_quotes_gpc()) {
				$str = stripslashes($str);
			}
			return mysql_real_escape_string($str);
		}
	}
	
?>