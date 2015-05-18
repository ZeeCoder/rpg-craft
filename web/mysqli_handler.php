<?php
	/**
		HV - Mysqli Handler
		@author: Hubert Viktor
		@since: 2012
	*/
	
	class MysqliHandler {
		var $obj;
		var $db;
		
		/*
			Setting up the obj.
			If the parameter is 'noconstruct', it won't start the obj.
		*/
		function __construct( $param ) {
			if ( $param=='noconstruct' ) return;
			$this->open( $param );
		}
		
		/*
			Starts the obj.
			The constructor calls this function too.
		*/
		function open( $conf_url = 'config.ini' ) {
			if ( !is_file($conf_url) ) { throw new Exception( 'MysqliHandler Error: The given configuration file doesn\'t exist.', -100 ); return false; }
			
			
			$c = parse_ini_file( $conf_url, true );
			
			$this->obj = new mysqli( $c['db_host'], $c['db_user'], $c['db_pass'], $c['db_name'] );
			if ($this->obj->connect_errno)
				throw new Exception( 'MysqliHandler Error: Couldn\'t setup obj. (' . $this->obj->connect_errno . ": " . $this->obj->connect_error . ')', -101 ); return;
			
			$this->query( 'SET NAMES UTF8' );
			
			return true;
		}
		
		/*
			Executes a query.
		*/
		function query( $q ) {
			$r = $this->obj->query( $q );
			if ( $r )
				return $r;
			else
				throw new Exception( 'MysqliHandler Error: Couldn\'t execute given query. ('.($this->obj->error).')', -102);
			return false;
		}
		
		/*
			Closing the obj.
		*/
		function close() {
			$this->obj->close();
		}
		
		/*
			Clean the incoming data.
		*/
		function clean( $str ) {
			$str = trim( $str );
			if(get_magic_quotes_gpc())
				$str = stripslashes( $str );
			return $this->obj->real_escape_string( $str );
		}
	}
	
?>